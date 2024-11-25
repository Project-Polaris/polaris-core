<?php

namespace App\Traits;

use App\Contracts\Auth\GeneratesToken;
use App\Exceptions\GuardCannotProduceTokenException;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

trait HasTokens {

    /**
     * @param Guard|string|null $guard 
     * @return string 
     * @throws \Illuminate\Contracts\Container\BindingResolutionException 
     * @throws \InvalidArgumentException 
     * @throws \DomainException 
     * @throws \App\Exceptions\GuardCannotProduceTokenException 
     */
    public function createToken(mixed $guard = null): string {
        $authGuard = $guard instanceof Guard ? $guard : Auth::guard($guard);

        if ($authGuard instanceof GeneratesToken) {
            $token = $authGuard->createToken($this);
            if ($token !== null) {
                return $token;
            }
        }

        throw new GuardCannotProduceTokenException("Unable to acquire a token-generating guard.");
    }
}
