<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentShield\FilamentShield;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentShield::configureNavigationGroups([
            'User Management' => [
                'User Management', // Group label
                [
                    'role_navigation' => true, // Keep shield resources in this group
                    'sort' => 2, // Group order in navigation
                ]
            ]
        ]);
    }
}
