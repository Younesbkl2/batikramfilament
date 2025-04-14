<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\FilamentAuth\LoginResponse;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as FilamentLoginResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FilamentLoginResponse::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
