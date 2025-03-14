<?php

return [
    App\Providers\AppServiceProvider::class,
    \Modules\Authentication\Providers\AuthenticationServiceProvider::class,
    \Modules\Product\Providers\ProductServiceProvider::class,
    \Modules\Cart\Providers\CartServiceProvider::class,
    \Modules\Order\Providers\OrderServiceProvider::class,
    \Modules\Transaction\Providers\TransactionServiceProvider::class,
];
