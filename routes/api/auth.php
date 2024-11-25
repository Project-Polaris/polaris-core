<?php

use App\Http\Controllers\Authentication;
use Illuminate\Support\Facades\Route;

Route::post('/v1/auth/login', [Authentication::class, 'login_v1'])
    ->name('login.v1');

Route::post('/v1/auth/register', [Authentication::class, 'register_v1'])
    ->name('register.v1');

