<?php

namespace Modules\User\Providers;


use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    protected $module_name = 'User';

    protected $frontend_routes = [
        'routes.php',
    ];
    protected $dashboard_routes = [
        'routes.php',
    ];
    protected $vendor_routes = [
        'routes.php',
    ];
    protected $api_routes = [
        'routes.php',
    ];

    protected function apiGroups(){

        return [
            'middleware' => config('core.route-middleware.api.guest'),
            'prefix' => config('core.route-prefix.api')
        ];
    }
}
