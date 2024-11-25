<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authentication\LoginV1;
use App\Http\Requests\Authentication\RegisterV1;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Authentication extends Controller {

    public function __construct(
        protected AuthFactory $auth,
        protected UserRepository $userRepository,
    ) { }

    public function login_v1(LoginV1 $request): JsonResponse {
        /** @var \Illuminate\Contracts\Auth\StatefulGuard */
        $guard = $this->auth->guard();

        $loggedIn = $guard->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (!$loggedIn) {
            throw new AuthenticationException;
        }

        /** @var \App\Models\User $user */
        $user = $guard->user();

        $response = [
            'token' => $user->createToken(),
            'user' => $user,
        ];

        return response()->json($response);
    }

    public function register_v1(RegisterV1 $request): JsonResponse {
        $user = $this->userRepository->create(
            $request->email, $request->password
        );

        $response = [
            'token' => $user->createToken(),
            'user' => $user,
        ];

        return response()->json($response);
    }
}
