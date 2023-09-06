<?php

use Illuminate\Support\Facades\Route;

Route::get('index/{reference_id}','index')->name('index');
Route::post('index/{reference_id}','index')->name('index');

Route::get('view/{reference_id}','view')->name('view');
Route::get('fetch/{reference_id}','fetch')->name('fetch');

Route::post('update-sequence/{reference_id}','update_sequence')->name('update_sequence');

Route::get('add/{reference_id}','add')->name('add');
Route::post('add/{reference_id}','add')->name('add');

Route::get('edit/{reference_id}/{id}','edit')->name('edit');
Route::post('edit/{reference_id}/{id}','edit')->name('edit');


Route::post('toggle-status','toggleStatus')->name('toggle_status');

