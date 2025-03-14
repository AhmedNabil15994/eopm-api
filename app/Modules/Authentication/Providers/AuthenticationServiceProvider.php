<?php

namespace Modules\Authentication\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthenticationServiceProvider extends ServiceProvider
{
    protected $middleware = [
        'Authentication' => [
            'auth'            => 'Authenticate',
        ],
    ];


    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMiddleware($this->app['router']);
        $this->registerTranslations();
        $this->loadMigrationsFrom(base_path('app/Modules/Authentication/Database/Migrations'));
    }


    /**
     * Register the filters.
     *
     * @param  Router $router
     * @return void
     */
    public function registerMiddleware(Router $router)
    {
        foreach ($this->middleware as $module => $middlewares) {
            foreach ($middlewares as $name => $middleware) {
                $class = "Modules\\{$module}\\Http\\Middleware\\{$middleware}";
                $router->aliasMiddleware($name, $class);
            }
        }
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/authentication');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'authentication');
        } else {
            $this->loadTranslationsFrom(base_path('app/Modules/Authentication/Resources/lang'), 'authentication');
        }
    }
}
