<?php
use Illuminate\Support\Facades\Route;
use Modules\Transaction\Http\Controllers\Api\TransactionController;


Route::group(['prefix' => '/payments' ,'middleware' => 'auth:api'], function () {
    Route::get('/', [TransactionController::class,'index'])->name('api.payments.index');
    Route::get('/{id}', [TransactionController::class,'show'])->name('api.payments.show');
});
