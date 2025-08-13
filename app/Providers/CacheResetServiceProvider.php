<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

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
        // Only run this in production to avoid slowing down development
        if (app()->environment('production')) {
            try {
                // Create a flag file to ensure this only runs once after deployment
                $flagFile = storage_path('framework/cache/auto_cleared.flag');
                
                // Check if we've cleared the cache already
                if (!file_exists($flagFile) || (time() - filemtime($flagFile) > 86400)) {
                    // Clear all Laravel caches
                    Artisan::call('route:clear');
                    Artisan::call('config:clear');
                    Artisan::call('cache:clear');
                    Artisan::call('view:clear');
                    
                    // Create or touch the flag file to prevent repeated clearing
                    file_put_contents($flagFile, date('Y-m-d H:i:s'));
                    
                    // Log that caches were cleared
                    Log::info('Application caches automatically cleared after deployment');
                }
            } catch (\Exception $e) {
                Log::error('Failed to auto-clear caches: ' . $e->getMessage());
            }
        }
    }
}
