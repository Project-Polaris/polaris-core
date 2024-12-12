<?php

use App\Http\Controllers\Authentication;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [Authentication::class, 'login_v1'])
    ->name('login.v1');

Route::post('/auth/register', [Authentication::class, 'register_v1'])
    ->name('register.v1');

