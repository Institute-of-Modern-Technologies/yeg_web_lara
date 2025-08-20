<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class PathHelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Create a custom blade directive for image paths
        Blade::directive('imagePath', function ($expression) {
            return "<?php echo app('App\Services\ImagePathService')->resolveImagePath($expression); ?>";
        });
    }
}
