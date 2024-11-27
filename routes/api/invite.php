<?php

use App\Http\Controllers\InviteController;
use App\Models\Invite;
use Illuminate\Support\Facades\Route;

Route::get('/v1/invites/', [InviteController::class, 'index_v1'])
    ->name('index.v1')
    ->can('viewAny', Invite::class);

Route::post('/v1/invites/', [InviteController::class, 'store_v1'])
    ->name('post.v1')
    ->can('create', Invite::class);

Route::get('/v1/invites/{invite}', [InviteController::class, 'show_v1'])
    ->name('get.v1')
    ->can('view', 'invite');

Route::put('/v1/invites/{invite}', [InviteController::class, 'update_v1'])
    ->name('update.v1')
    ->can('update', 'invite');

Route::delete('/v1/invites/{invite}', [InviteController::class, 'delete_v1'])
    ->name('delete.v1')
    ->can('delete', 'invite');
