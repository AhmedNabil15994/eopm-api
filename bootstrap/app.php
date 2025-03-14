<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            $modulesArray = (array) json_decode(file_get_contents(base_path('./modules.json')));
            foreach ($modulesArray as $module => $status) {
                if($status && file_exists(base_path("app/Modules/{$module}/Routes/api/routes.php"))){
                    Route::prefix('api')
                        ->group( base_path("app/Modules/{$module}/Routes/api/routes.php") );
                }
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->append(\App\Http\Middleware\SetLocalization::class);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
