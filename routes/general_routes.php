<?php

use Illuminate\Support\Facades\Route;

Route::get('view','view')->name('view');
Route::get('fetch','fetch')->name('fetch');

Route::post('update-sequence/','update_sequence')->name('update_sequence');

Route::get('add','add')->name('add');
Route::post('add','add')->name('add');

Route::get('edit/{id}','edit')->name('edit');
Route::post('edit/{id}','edit')->name('edit');

Route::get('status/{id}','changeStatus')->name('changeStatus');

Route::post('toggle-status','toggleStatus')->name('toggle_status');

