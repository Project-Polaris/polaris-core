<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authentication\LoginV1;
use App\Http\Requests\Authentication\RegisterV1;
use App\Http\Resources\UserResource;
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
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $request->session()->regenerate();

        $user = $guard->user();

        $response = [
            'user' => new UserResource($user),
        ];

        return response()->json($response);
    }

    public function register_v1(RegisterV1 $request): JsonResponse {
        // TODO captcha

        $user = $this->userRepository->create(
            $request->email,
            $request->password
        );

        $response = [
            'user' => new UserResource($user),
        ];

        return response()->json($response);
    }
}
