<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{DoctorController,
    SalesController,
    UserController,
    AppointmentController,
    BankDetailController,
    ClinicController};
use App\Http\Common\{ResponseHelper};

Route::prefix('sales')->controller(SalesController::class)->name('sales-')->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('signup', 'signup')->name('signup');
    Route::group(['middleware' => 'sales_auth'], function() {
        Route::get('logout', 'logout')->name('logout');
        Route::post('add-visit', 'salesVisit')->name('add-visit');
        Route::post('add-doctor', 'doctorRegistration')->name('add-doctor');
        Route::get('clinic', 'getClinics')->name('clinic');
        Route::get('clinic/{doctorId}', 'doctorClinic')->name('doctor-clinic');
        Route::get('doctors', 'doctors')->name('doctors');
        Route::get('visits', 'visits')->name('visits');
        Route::get('feedbacks', 'feedbacks')->name('feedbacks');
        Route::get('cities', 'getCities')->name('cities');
        Route::get('get-services', 'getServices')->name('get_services');
        Route::get('get-degrees', 'getDegrees')->name('get_degrees');
        Route::get('get-universities', 'getUniversities')->name('get_universities');
        Route::get('get-designations', 'getDesinations')->name('get_designation');
        Route::get('get-specialities', 'getSpecialities')->name('get_specialities');
    });
});
