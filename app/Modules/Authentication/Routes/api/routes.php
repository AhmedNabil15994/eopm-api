<?php

use Illuminate\Support\Facades\Route;
use Modules\Authentication\Http\Controllers\Api\LoginController;
use Modules\Authentication\Http\Controllers\Api\RegisterController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [LoginController::class,'login'])->name('api.auth.login');
    Route::post('register', [RegisterController::class,'register'])->name('api.auth.register');
    Route::group(['prefix' => '/', 'middleware' => 'auth:api'], function () {
        Route::post('logout', [LoginController::class,'logout'])->name('api.auth.logout');
    });
});
