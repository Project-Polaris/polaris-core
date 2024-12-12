<?php

use Illuminate\Support\Facades\Route;

Route::name('users.')->group(base_path('routes/api/users.php'));
Route::name('invites.')->group(base_path('routes/api/invites.php'));
Route::name('nodes.')->group(base_path('routes/api/nodes.php'));
