<?php

namespace Modules\Cart\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->loadMigrationsFrom(base_path('app/Modules/Cart/Database/Migrations'));
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/cart');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'cart');
        } else {
            $this->loadTranslationsFrom(base_path('app/Modules/Cart/Resources/lang'), 'cart');
        }
    }

}
