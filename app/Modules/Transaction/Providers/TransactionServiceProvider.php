<?php

namespace Modules\Transaction\Providers;

use Illuminate\Support\ServiceProvider;

class TransactionServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->loadMigrationsFrom(base_path('app/Modules/Transaction/Database/Migrations'));
    }


    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/transaction');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'transaction');
        } else {
            $this->loadTranslationsFrom(base_path('app/Modules/Transaction/Resources/lang'), 'transaction');
        }
    }
}
