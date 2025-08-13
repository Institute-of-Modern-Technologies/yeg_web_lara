<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CacheResetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Empty service provider - disabled automatic cache clearing
        // to prevent server errors
    }
}
