<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    DoctorController,
    UserController,
    AppointmentController,
    BankDetailController,
    ClinicController,
};
use App\Http\Common\{ResponseHelper};

Route::prefix('dashboard')->controller(PatientController::class)->name('dashboard-')->group(function () {
    Route::prefix('dashboard')->controller(PatientController::class)->name('dashboard-')->group(function () {

    });
    Route::get('/', [PatientController::class, 'view'])->name('view');
    Route::get('fetch', 'fetch')->name('fetch');
    Route::get('edit/{id}', 'edit')->name('edit');
    Route::post('edit/{id}', 'edit')->name('edit');
    Route::get('delete/{id}', 'delete')->name('delete');
    Route::post('view-patient-family/{family_id}', 'viewPatientFamily')->name('viewFamily');
    Route::post('edit-patient-family/{family_id}', 'editPatientFamily')->name('editFamily');
    Route::get('view-profile/{patient_id}', 'viewProfile')->name('viewProfile');
    Route::get('view-appointments/{patient_id}', 'viewAppointments')->name('viewAppointments');
    Route::get('view-reports/{patient_id}', 'viewReports')->name('viewReports');
    Route::get('view-labhistory/{patient_id}', 'viewLabHistory')->name('viewLabHistory');
    Route::get('view-vitalhistory/{patient_id}', 'viewVitalHistory')->name('viewVitalHistory');
    Route::get('view-subscriptions/{patient_id}', 'viewSubscriptions')->name('viewSubscriptions');
    Route::get('view-wallet/{patient_id}', 'viewWallet')->name('viewWallet');
    Route::post('subscription/delete/{patient_id}','deleteSubscription')->name('delete-subscription');
    Route::post('transaction/view/{patient_id}','viewTransaction')->name('view-transaction');
});
