<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\\Factories\\' . Arr::last(explode('\\', $modelName)) . 'Factory';
        });
    }
}
