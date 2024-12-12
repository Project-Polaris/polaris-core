<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Services\Auth\JwtGuard;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\RequestGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        $this->app->singleton(UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {

    }
}
