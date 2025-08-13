<?php

namespace App\Providers;

use App\Models\Student;
use App\Observers\StudentObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(MiddlewareServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length for MariaDB/MySQL older versions
        Schema::defaultStringLength(191);
        
        // Register observers
        Student::observe(StudentObserver::class);
        
        // Auto-fix functionality disabled to prevent 500 server errors
        // Left Schema::defaultStringLength(191) in place as it's important for MySQL compatibility
    }
}
