<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Api\OrderController;
use Modules\Order\Http\Controllers\Api\OrderStatusController;

Route::get('/orders_statues', [OrderStatusController::class,'index'])->name('api.orders_statues.index');

Route::group(['middleware' => 'auth:api' ,'prefix' => '/orders'], function () {
    Route::post('/create', [OrderController::class,'create'])->name('api.order.create');
    Route::get('/', [OrderController::class,'index'])->name('api.orders.index');
    Route::get('/{id}', [OrderController::class,'show'])->name('api.orders.show');
    Route::post('/{id}/checkout', [OrderController::class,'checkout'])->name('api.orders.checkout');
    Route::post('/{id}/accept', [OrderController::class,'accept'])->name('api.orders.accept');
    Route::post('/{id}/cancel', [OrderController::class,'cancel'])->name('api.orders.cancel');
    Route::delete('/{id}/delete', [OrderController::class,'delete'])->name('api.orders.delete');
});

Route::get('/orders/success/{payment}', [OrderController::class,'success']);
Route::get('/orders/failed/{payment}', [OrderController::class,'failed']);



