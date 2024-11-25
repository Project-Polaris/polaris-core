<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Services\Auth\JwtGuard;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\RequestGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

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
        Auth::extend('jwt', function (Application $app, string $name, array $config) {
            $guard = new JwtGuard(
                $name, $app,
                Auth::createUserProvider(data_get($config, 'provider', 'users')),
                \data_get($config, 'rehash_on_join', false),
                \data_get($config, 'alg'),
                \data_get($config, 'keys'),
                \data_get($config, 'current_key'),
            );

            if ($this->app instanceof \Illuminate\Container\Container) {
                $this->app->refresh('request', $guard, 'setRequest');
            }

            return $guard;
        });
    }
}
