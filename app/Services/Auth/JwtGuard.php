<?php

namespace App\Services\Auth;

use App\Contracts\Auth\GeneratesToken;
use App\Exceptions\NotImplementedException;
use App\Repositories\UserRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Validated;
use Illuminate\Auth\RequestGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Timebox;
use InvalidArgumentException;

class JwtGuard extends RequestGuard implements StatefulGuard, GeneratesToken {
    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected readonly ?Dispatcher $events;

    /**
     * The timebox instance.
     *
     * @var \Illuminate\Support\Timebox
     */
    protected readonly Timebox $timebox;

    /**
     * The user we last attempted to retrieve.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected ?Authenticatable $lastAttempted;

    /**
     * @param string $name 
     * @param \Illuminate\Contracts\Foundation\Application $app 
     * @param null|\Illuminate\Contracts\Auth\UserProvider $userProvider 
     * @param bool $rehashOnLogin 
     * @param string $alg 
     * @param array<string, array<string, string>> $keys 
     * @param string $currentKey 
     * @return void 
     * @throws \Illuminate\Contracts\Container\BindingResolutionException 
     */
    public function __construct(
        protected string $name,
        protected Application $app,
        ?UserProvider $userProvider = null,
        protected bool $rehashOnLogin = true,
        protected string $alg = "EdDSA",
        protected array $keys = [],
        protected string $currentKey = 'default',
    ) {
        $request = $app->make(Request::class);
        parent::__construct([$this, 'fromRequest'], $request, $userProvider);
        $this->events = $app->make(Dispatcher::class);
        $this->timebox = new Timebox;
    }

    protected function fromRequest(Request $request): ?Authenticatable {
        $token = $request->bearerToken();
        if (!is_null($token)) {
            $keys = $this->getPublicKeysFromConfig();
            $jwt = JWT::decode($token, $keys);
            $userId = $jwt->sub;
            $issuedAt = $jwt->iat;
            $user = $this->provider->retrieveById($userId);

            if (\is_null($user)) {
                return null;
            }

            if ($user instanceof \App\Models\User) {
                if ($issuedAt < $user->session_not_before) {
                    return null;
                }
            }

            return $user;
        }
        return null;
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array<string, string>  $credentials
     * @param  bool                   $remember (unused)
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false): bool {
        $this->fireAttemptEvent($credentials);

        $user = $this->provider->retrieveByCredentials($credentials);

        // If an implementation of UserInterface was returned, we'll ask the provider
        // to validate the user against the given credentials, and if they are in
        // fact valid we'll log the users into the application and return true.
        if ($this->hasValidCredentials($user, $credentials)) {
            $this->rehashPasswordIfRequired($user, $credentials);

            // If we have an event dispatcher instance set we will fire an event so that
            // any listeners will hook into the authentication events and run actions
            // based on the login and logout events fired from the guard instances.
            $this->fireLoginEvent($user);

            $this->setUser($user);

            return true;
        }

        // If the authentication attempt fails we will fire an event so that the user
        // may be notified of any suspicious attempts to access their account from
        // an unrecognized user. A developer may listen to this event as needed.
        $this->fireFailedEvent($user, $credentials);

        return false;
    }

    /**
     * Log a user into the application without sessions or cookies.
     * This is required to implement StatefulGuard, but not used in this implementation.
     *
     * @param  array<string, string>  $credentials
     * @return bool
     */
    public function once(array $credentials = []) {
        return $this->attempt($credentials);
    }

    /**
     * Log a user into the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    public function login(Authenticatable $user, $remember = false) {
        // If we have an event dispatcher instance set we will fire an event so that
        // any listeners will hook into the authentication events and run actions
        // based on the login and logout events fired from the guard instances.
        $this->fireLoginEvent($user, $remember);

        $this->setUser($user);
    }

    /**
     * Log the given user ID into the application.
     *
     * @param  mixed  $id
     * @param  bool  $remember
     * @return \Illuminate\Contracts\Auth\Authenticatable|false
     */
    public function loginUsingId($id, $remember = false) {
        if (! is_null($user = $this->provider->retrieveById($id))) {
            $this->login($user, $remember);

            return $user;
        }

        return false;
    }

    /**
     * Log the given user ID into the application without sessions or cookies.
     *
     * @param  mixed  $id
     * @return \Illuminate\Contracts\Auth\Authenticatable|false
     */
    public function onceUsingId($id) {
        if (! is_null($user = $this->provider->retrieveById($id))) {
            $this->setUser($user);

            return $user;
        }

        return false;
    }

    /**
     * Determine if the user was authenticated via "remember me" cookie.
     *
     * @return bool
     */
    public function viaRemember() {
        throw new NotImplementedException("Not implemented");
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout() {
        $this->user = null;
    }

    public function createToken(?Authenticatable $user): ?string {
        $user ??= $this->user();

        if (is_null($user)) {
            return null;
        }

        $keyId = null;
        $alg = null;
        $privateKey = $this->getCurrentPrivateKey($keyId, $alg);

        $payload = [
            'sub' => $user->getAuthIdentifier(),
            'iat' => time(),
        ];
        return JWT::encode($payload, $privateKey, $alg, $keyId);
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $user
     * @param  array<string, string>  $credentials
     * @return bool
     * @phpstan-assert-if-true \Illuminate\Contracts\Auth\Authenticatable $user
     */
    protected function hasValidCredentials(?Authenticatable $user, array $credentials) : bool {
        return $this->timebox->call(function ($timebox) use ($user, $credentials) {
            $validated = ! is_null($user) && $this->provider->validateCredentials($user, $credentials);

            if ($validated) {
                $timebox->returnEarly();

                $this->fireValidatedEvent($user);
            }

            return $validated;
        }, 200 * 1000);
    }

    /**
     * Rehash the user's password if enabled and required.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array<string, string>  $credentials
     * @return void
     */
    protected function rehashPasswordIfRequired(Authenticatable $user, #[\SensitiveParameter] array $credentials) {
        if ($this->rehashOnLogin) {
            $this->provider->rehashPasswordIfRequired($user, $credentials);
        }
    }

    /**
     * Fire the attempt event with the arguments.
     *
     * @param  array<string, string>  $credentials
     * @param  bool  $remember
     * @return void
     */
    protected function fireAttemptEvent(array $credentials, $remember = false) {
        $this->events?->dispatch(new Attempting($this->name, $credentials, $remember));
    }

    /**
     * Fire the login event if the dispatcher is set.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    protected function fireLoginEvent(Authenticatable $user, $remember = false) {
        $this->events?->dispatch(new Login($this->name, $user, $remember));
    }

    /**
     * Fires the validated event if the dispatcher is set.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function fireValidatedEvent(Authenticatable $user) {
        $this->events?->dispatch(new Validated($this->name, $user));
    }

    /**
     * Fire the failed authentication attempt event with the given arguments.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $user
     * @param  array<string, string>  $credentials
     * @return void
     */
    protected function fireFailedEvent(?Authenticatable $user, array $credentials) {
        $this->events?->dispatch(new Failed($this->name, $user, $credentials));
    }


    /**
     * @return array<string, Key>
     */
    protected function getPublicKeysFromConfig(): array {
        /** @var array<string, array<string, string>> $configKeys */
        $configKeys = $this->keys;
        /** @var array<string, Key> $publicKeys */
        $publicKeys = [];
        foreach ($configKeys as $kid => $key) {
            $keyMaterial = match ($key['type']) {
                'EdDSA' => $key['public'],
                'HS256', 'HS384', 'HS512' => $key['secret'],
                'RS256', 'RS384', 'RS512' => $key['public'],
                'ES256', 'ES256K', 'ES384' => $key['public'],
                default => throw new \InvalidArgumentException('Invalid key type'),
            };
            $publicKeys[$kid] = new Key($keyMaterial, $key['type']);
        }
        return $publicKeys;
    }

    /**
     * @param-out string $keyId
     * @param-out string $alg
     */
    protected function getCurrentPrivateKey(?string &$keyId, ?string &$alg): string {
        $currentKey = $keyId ?? $this->currentKey;
        $keys = config('jwt.keys');

        if (empty($keys)) {
            throw new \InvalidArgumentException('No keys found');
        }

        if ($currentKey === 'default' || !array_key_exists($currentKey, $keys)) {
            $keyId = \array_key_first($keys);
        }

        if (is_null($keyId)) {
            throw new \InvalidArgumentException('No key found');
        }

        $key = $keys[$keyId];

        $alg = $key['type'];
        return match ($key['type']) {
            'EdDSA' => $key['private'],
            'HS256', 'HS384', 'HS512' => $key['secret'],
            'RS256', 'RS384', 'RS512' => $key['private'],
            'ES256', 'ES256K', 'ES384' => $key['private'],
            default => throw new \InvalidArgumentException('Invalid key type'),
        };
    }
}
