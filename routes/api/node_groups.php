<?php

use App\Http\Controllers\NodeGroupController;
use App\Models\NodeGroup;
use Illuminate\Support\Facades\Route;

Route::get('/v1/nodegroups/', [NodeGroupController::class, 'index_v1'])
    ->name('viewAny.v1')
    ->can('viewAny', NodeGroup::class);

Route::post('/v1/nodegroups/', [NodeGroupController::class, 'store_v1'])
    ->name('create.v1')
    ->can('create', NodeGroup::class);

Route::get('/v1/nodegroups/{nodeGroup}', [NodeGroupController::class, 'show_v1'])
    ->name('view.v1')
    ->can('view', 'nodeGroup');

Route::put('/v1/nodegroups/{nodeGroup}', [NodeGroupController::class, 'update_v1'])
    ->name('update.v1')
    ->can('update', 'nodeGroup');

Route::delete('/v1/nodegroups/{nodeGroup}', [NodeGroupController::class, 'delete_v1'])
    ->name('delete.v1')
    ->can('delete', 'nodeGroup');
