<?php

namespace Stokoe\IpWhitelist;

use Illuminate\Support\Facades\Gate;
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

    protected $routeMiddleware = [
        'ip-whitelist' => IpWhitelistMiddleware::class,
    ];

    public function bootAddon()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ip-whitelist.php', 'ip-whitelist');

        $this->publishes([
            __DIR__ . '/../config/ip-whitelist.php' => config_path('ip-whitelist.php'),
        ], 'ip-whitelist-config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->commands([
            ManageIpWhitelist::class,
        ]);

        $this->app->singleton(IpWhitelistService::class);

        Permission::register('manage ip whitelist')
            ->label('Manage IP Whitelist');

        Gate::define('manage ip whitelist', function ($user) {
            return $user->isSuper() || $user->hasPermission('manage ip whitelist');
        });

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
    }
}
