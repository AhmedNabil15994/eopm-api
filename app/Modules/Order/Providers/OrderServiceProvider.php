<?php

namespace Modules\Order\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Order\Console\VideoWatermark;

class OrderServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->loadMigrationsFrom(base_path('app/Modules/Order/Database/Migrations'));
    }


    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/order');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'order');
        } else {
            $this->loadTranslationsFrom(base_path('app/Modules/Order/Resources/lang'), 'order');
        }
    }
}
