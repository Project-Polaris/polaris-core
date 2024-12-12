<?php

use App\Http\Controllers\NodeController;
use App\Models\Node;
use Illuminate\Support\Facades\Route;

Route::get('/v1/nodes/', [NodeController::class, 'index_v1'])
    ->name('viewAny.v1')
    ->can('viewAny', Node::class);

Route::post('/v1/nodes/', [NodeController::class, 'store_v1'])
    ->name('create.v1')
    ->can('create', Node::class);

Route::get('/v1/nodes/{node}', [NodeController::class, 'show_v1'])
    ->name('view.v1')
    ->can('view', 'node');

Route::put('/v1/nodes/{node}', [NodeController::class, 'update_v1'])
    ->name('update.v1')
    ->can('update', 'node');

Route::delete('/v1/nodes/{node}', [NodeController::class, 'delete_v1'])
    ->name('delete.v1')
    ->can('delete', 'node');
