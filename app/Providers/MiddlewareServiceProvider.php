<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * The middleware mappings for the application.
     *
     * @var array<string, class-string>
     */
    protected $middleware = [
        // Global middleware
    ];

    /**
     * The middleware groups for the application.
     *
     * @var array<string, array<string>>
     */
    protected $middlewareGroups = [
        'web' => [
            // Web middleware group
        ],
        'api' => [
            // API middleware group
        ],
    ];

    /**
     * The middleware aliases for the application.
     *
     * @var array<string, class-string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'user.type' => \App\Http\Middleware\CheckUserType::class,
    ];

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        // Register middleware aliases
        $this->registerMiddlewareAliases();
    }

    /**
     * Register middleware aliases.
     */
    protected function registerMiddlewareAliases(): void
    {
        foreach ($this->middlewareAliases as $alias => $middleware) {
            $this->aliasMiddleware($alias, $middleware);
        }
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // Rate limiting configuration
    }
}
