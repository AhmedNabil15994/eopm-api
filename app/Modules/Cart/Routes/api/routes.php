<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\Api\CartController;

Route::group(['prefix' => 'cart' ], function () {
    Route::get('/', [CartController::class,'index'])->name('api.cart.index');
    Route::post('add/{id}', [CartController::class,'createOrUpdate'])->name('api.cart.add');
    Route::delete('remove/{id}', [CartController::class,'remove'])->name('api.cart.remove');
    Route::post('clear', [CartController::class,'clear'])->name('api.cart.clear');
});
