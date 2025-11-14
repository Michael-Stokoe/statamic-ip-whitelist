<?php

namespace Stokoe\IpWhitelist;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Statamic\Providers\AddonServiceProvider;
use Stokoe\IpWhitelist\Console\Commands\ManageIpWhitelist;
use Stokoe\IpWhitelist\Http\Middleware\IpWhitelistMiddleware;
use Stokoe\IpWhitelist\Services\IpWhitelistService;

class ServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    protected $viewNamespace = 'ip-whitelist';

    protected $middlewareGroups = [
        'statamic.cp.authenticated' => [
            IpWhitelistMiddleware::class,
        ],
    ];

    public function bootAddon()
    {
        // Register config
        $this->mergeConfigFrom(__DIR__ . '/../config/ip-whitelist.php', 'ip-whitelist');
        
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/ip-whitelist.php' => config_path('ip-whitelist.php'),
        ], 'ip-whitelist-config');

        // Register migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Register commands
        $this->commands([
            ManageIpWhitelist::class,
        ]);

        // Register services
        $this->app->singleton(IpWhitelistService::class);

        // Register permissions
        Permission::register('manage ip whitelist')
            ->label('Manage IP Whitelist');

        // Register gate
        Gate::define('manage ip whitelist', function ($user) {
            return $user->isSuper() || $user->hasPermission('manage ip whitelist');
        });

        // Add CP navigation
        Nav::extend(function ($nav) {
            $nav->create('IP Whitelist')
                ->can('manage ip whitelist')
                ->section('Tools')
                ->route('ip-whitelist.index')
                ->icon('shield-key')
                ->children([
                    'Manage IPs' => cp_route('ip-whitelist.index'),
                    'Settings' => cp_route('ip-whitelist.settings'),
                ]);
        });

        // Apply middleware to additional routes if configured
        $this->applyMiddlewareToAdditionalRoutes();
    }

    protected function applyMiddlewareToAdditionalRoutes()
    {
        $protectedRoutes = config('ip-whitelist.protected_routes', []);
        
        if (!empty($protectedRoutes)) {
            foreach ($protectedRoutes as $routePattern) {
                Route::middleware([IpWhitelistMiddleware::class])
                    ->group(function () use ($routePattern) {
                        Route::any($routePattern, function () {
                            // This is just to apply middleware, actual routes are handled elsewhere
                        });
                    });
            }
        }
    }
}
