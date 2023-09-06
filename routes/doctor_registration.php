<?php

use App\Http\Controllers\DoctorRegistrationController;
use Illuminate\Support\Facades\Route;

Route::controller(DoctorRegistrationController::class)->name('register_doctor_')->group(function () {
    Route::get('/register/doctor', 'index')->name('index');
    Route::get('/register/doctor/about/{id?}', 'about')->name('about');
    Route::post('/register/doctor/about/{id?}', 'about')->name('about');
    Route::get('/register/doctor/services', 'getServices')->name('get_services');
    Route::get('/register/doctor/education-experience/{id}', 'educationExperience')->name('education_experience');
    Route::post('/register/doctor/education-experience/{id}', 'educationExperience')->name('education_experience');
    Route::get('/register/doctor/availability/{id}', 'availability')->name('availability');
    Route::post('/register/doctor/availability/{id}', 'availability')->name('availability');
    Route::get('/register/doctor/online-consultation/{id}', 'onlineConsultation')->name('online_consultation');
    Route::post('/register/doctor/online-consultation/{id}', 'onlineConsultation')->name('online_consultation');
    Route::get('/register/doctor/thankyou', 'thankyou')->name('thankyou');
    Route::get('/register/progress', 'profileProgress')->name('progress');
});

Route::prefix('api')->group(function(){
    Route::prefix(Request()->segment(2) === "v2" ? "v2" : "v1")->group(function() {
        Route::controller(DoctorRegistrationController::class)->name('register_doctor_')->group(function () {
            // Route::post('/register/doctor/education-experience-api/{id}', 'educationExperienceApi')->name('education_experience_api')->middleware('api');
            Route::get('/register/doctor', 'index')->name('index');
            Route::get('/register/doctor/about/{id?}', 'about')->name('about');
            Route::post('/register/doctor/about/{id?}', 'about')->name('about');
            Route::get('/register/doctor/services', 'getServices')->name('get_services');
            Route::get('/register/doctor/education-experience/{id}', 'educationExperience')->name('education_experience');
            Route::post('/register/doctor/education-experience/{id}', 'educationExperience')->name('education_experience');
            Route::get('/register/doctor/availability/{id}', 'availability')->name('availability');
            Route::post('/register/doctor/availability/{id}', 'availability')->name('availability');
            Route::get('/register/doctor/online-consultation/{id}', 'onlineConsultation')->name('online_consultation');
            Route::post('/register/doctor/online-consultation/{id}', 'onlineConsultation')->name('online_consultation');
            Route::get('/register/doctor/thankyou', 'thankyou')->name('thankyou');

            // Route::get('/register/doctor/{id}', 'edit_register')->name('register_doctor_edit_public_url');
            // Route::post('/register/doctor/{id}', 'edit_register')->name('register_doctor_edit_public_url');

            // Route::get('/register/doctor-education/{id}', 'registerEducation')->name('register_doctor_education_public_url');
            // Route::post('/register/doctor-education/{id}', 'registerEducation')->name('register_doctor_education_public_url');

            // Route::get('/register/doctor-experience/{id}', 'registerExperience')->name('register_doctor_experience_public_url');
            // Route::post('/register/doctor-experience/{id}', 'registerExperience')->name('register_doctor_experience_public_url');


            // Route::get('/register/doctor-location/{id}', 'registerLocation')->name('register_doctor_location_public_url');
            // Route::post('/register/doctor-location/{id}', 'registerLocation')->name('register_doctor_location_public_url');
            // Route::post('/register/doctor-services', 'services')->name('register_doctor_services_public_url');
            Route::get('/register/progress', 'profileProgress')->name('progress');
        });
    });
});
