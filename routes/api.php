<?php

use Illuminate\Support\Facades\Route;

Route::name('auth.')->group(base_path('routes/api/auth.php'));
Route::name('user.')->group(base_path('routes/api/user.php'));
Route::name('invite.')->group(base_path('routes/api/invite.php'));
