<?php

namespace Modules\Product\Providers;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->loadMigrationsFrom(base_path('app/Modules/Product/Database/Migrations'));
    }


    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/product');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'product');
        } else {
            $this->loadTranslationsFrom(base_path('app/Modules/Product/Resources/lang'), 'product');
        }
    }
}
