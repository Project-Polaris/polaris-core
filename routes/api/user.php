<?php

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/v1/users/', [UserController::class, 'index_v1'])
    ->name('index.v1')
    ->can('viewAny', User::class);

Route::post('/v1/users/', [UserController::class, 'store_v1'])
    ->name('post.v1')
    ->can('create', User::class);

Route::get('/v1/users/{user}', [UserController::class, 'show_v1'])
    ->name('get.v1')
    ->can('view', 'user');

Route::put('/v1/users/{user}', [UserController::class, 'update_v1'])
    ->name('update.v1')
    ->can('update', 'user');

Route::delete('/v1/users/{user}', [UserController::class, 'delete_v1'])
    ->name('delete.v1')
    ->can('delete', 'user');
