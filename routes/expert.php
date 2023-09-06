<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExpertController;

Route::prefix('experts')->controller(ExpertController::class)->name('experts-')->group(function () {
    Route::get('/', 'get')->name('get');
    Route::get('/{id}', 'detail')->name('detail');
    Route::post('/', 'store')->name('store');
    Route::put('/{id}', 'update')->name('update');
    Route::delete('/{id}', 'delete')->name('delete');
});
