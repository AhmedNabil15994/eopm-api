<?php
use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Api\ProductController;

Route::group(['prefix' => '/catalog/products' ], function () {
    Route::get('/', [ProductController::class,'index'])->name('api.products.index');
    Route::get('/{id}', [ProductController::class,'show'])->name('api.products.show');
});
