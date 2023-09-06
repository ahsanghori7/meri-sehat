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
use App\Http\Controllers\DoctorRegistrationController;
use App\Http\Common\{ResponseHelper};


if(Request()->segment(2) === "v2"){
    Route::group(['middleware' => "auth:api"], function () {
        Route::prefix('doctor')->group(function(){
            Route::get('/profile-details', [DoctorController::class, 'getUpdateDoctorDetails'])->name('getUpdateDoctorDetails');
            Route::get('listing', [DoctorController::class, 'getDoctors']);
            Route::get('current-appointments', [DoctorController::class, 'currentAppointments']);
            Route::post('/update', [UserController::class, 'updateDoctor']);
            Route::post('/update-profile', [UserController::class, 'updateDoctorProfile']);
            Route::get('/services', [UserController::class, 'getServices']);
            Route::get('/service/delete/{id}', [UserController::class, 'deleteService']);
            Route::get('/education/delete/{id}', [UserController::class, 'deleteEducation']);
            Route::post('/password/update', [UserController::class, 'updatePassword']);
            Route::get('/earning', [DoctorController::class, 'getEarning']);
            Route::get('/earning-details', [DoctorController::class, 'getEarningDetails']);
            Route::get('earning-break-down', [DoctorController::class, 'earningBreakDown']);
            Route::get('/pending-appointment', [AppointmentController::class, 'pendingAppointments']);
            Route::get('/all-appointment-download', [AppointmentController::class, 'downloadAllAppointments']);
            Route::get('/all-appointment', [AppointmentController::class, 'allAppointments']);
            Route::get('/payment-history', [AppointmentController::class, 'paymentHistory']);
            Route::get('/payment-history/{id}', [AppointmentController::class, 'paymentHistoryDetail']);
            Route::get('/deduction-list', [AppointmentController::class, 'deductionList']);
            Route::get('/deduction-list/{id}', [AppointmentController::class, 'deductionListDetail']);
            Route::post('/time-slots', [DoctorController::class, 'createTimeSlots']);
            Route::get('/time-slots', [DoctorController::class, 'createTimeSlots']);
            Route::post('/personal-info', [DoctorController::class, 'createPersonalInfo']);
            Route::post('/register/doctor/education-experience-api', [ DoctorRegistrationController::class,'educationExperienceApi'])->name('education_experience_api');
            Route::get('/register/doctor/education-experience-api', [ DoctorRegistrationController::class,'educationExperienceApi'])->name('education_experience_get_api');
            Route::get('/certifications', [ DoctorRegistrationController::class,'getCertifications'])->name('getCertification');
            Route::get('/register/doctor/education-experience-api/{id}', [ DoctorRegistrationController::class,'educationExperienceApi'])->name('education_experience_api');
            Route::post('/register/doctor/education-api',[ DoctorRegistrationController::class,'educationApi'])->name('education_api');
            Route::get('/register/doctor/education-api',[ DoctorRegistrationController::class,'educationApi'])->name('education_api');
            Route::post('/register/doctor/experience-api',[ DoctorRegistrationController::class,'experienceApi'])->name('experience_api');
            Route::post('/register/doctor/practice-detail', [ DoctorRegistrationController::class,'practiceDetails'])->name('practice_detail_api');
            Route::get('/institute',[ DoctorRegistrationController::class,'getInstitute'])->name('institute_api');
            Route::get('/designation',[ DoctorRegistrationController::class,'getDesignation'])->name('designation_api');
            Route::put('/minute-meeting', [ DoctorRegistrationController::class,'minuteMeeting']);
            Route::get('/progress', [ DoctorRegistrationController::class, 'profileProgress']);
            Route::put('/update-personal-information', [DoctorRegistrationController::class, 'updatePersonalInformation']);
            Route::prefix('appointment')->group(function(){
                Route::get('/list', [AppointmentController::class, 'getDoctorAppointments']);
                Route::get('/dashboard', [AppointmentController::class, 'dashboard']);
                Route::get('/{appointmentId}', [AppointmentController::class, 'getAppointments']);
                Route::get('/next-appointment/data', [AppointmentController::class, 'doctorNextAppointment']);
                Route::post('/details/add/{appointment}', [AppointmentController::class, 'addAppointmentDetails']);
            });
            Route::get('/check-online', [DoctorController::class, 'checkOnline']);
            Route::post('/instant-online-offline', [DoctorController::class, 'onlineOffline']);

            Route::get('/availability', [DoctorController::class, 'updateAvailablity']);
            Route::get('/clinic-listing', [DoctorController::class, 'clinicListing']);
            Route::get('/all-clinic-listing', [DoctorController::class, 'allClinicListing']);
            Route::get('/clinic-status/{clinicId}', [ClinicController::class, 'updateStatus']);
            Route::delete('/clinic-delete/{clinicId}', [ClinicController::class, 'deleteClinic']);
        });

        // Routes for Clinic Routes
        Route::prefix('clinic-record')->group(function(){
            Route::post('/{id?}', [ClinicController::class, 'createClinic']);
        });

        // Routes for Doctor's bank details
        Route::prefix('bank-detail')->group(function(){
            Route::post('/', [BankDetailController::class, 'createBankdetail']);
            Route::post('/updateBankDetails', [BankDetailController::class, 'updateBankdetail']);
            Route::get('/', [BankDetailController::class, 'getBankDetail']);
            Route::delete('/', [BankDetailController::class, 'deleteBankDetail']);
        });
    });
}else{
    Route::prefix('doctor')->group(function(){
        Route::get('/profile-details', [DoctorController::class, 'getUpdateDoctorDetails'])->name('getUpdateDoctorDetails');
        Route::get('earning-break-down', [DoctorController::class, 'earningBreakDown']);
        Route::get('listing', [DoctorController::class, 'getDoctors']);
        Route::get('current-appointments', [DoctorController::class, 'currentAppointments']);
        Route::post('/registration', [UserController::class, 'doctorRegistration']);
        Route::post('/registration-full', [UserController::class, 'doctorRegistrationAfterOTP']);
        Route::post('/login-via-phone', [UserController::class, 'doctorLoginViaPhone']);
        Route::post('/login-via-phone-otp', [UserController::class, 'doctorLoginViaPhoneOTP']);
        Route::post('/login', [UserController::class, 'doctorLogin']);
        Route::post('/update', [UserController::class, 'updateDoctor']);
        Route::post('/update-profile', [UserController::class, 'updateDoctorProfile']);
        Route::get('/services', [UserController::class, 'getServices']);
        Route::get('/service/delete/{id}', [UserController::class, 'deleteService']);
        Route::get('/education/delete/{id}', [UserController::class, 'deleteEducation']);
        Route::post('/password/update', [UserController::class, 'updatePassword']);
        Route::get('/earning', [DoctorController::class, 'getEarning']);
        Route::get('/earning-details', [DoctorController::class, 'getEarningDetails']);
        Route::get('/pending-appointment', [AppointmentController::class, 'pendingAppointments']);
        Route::get('/all-appointment', [AppointmentController::class, 'allAppointments']);
        Route::get('/all-appointment-download', [AppointmentController::class, 'downloadAllAppointments']);
        Route::get('/payment-history', [AppointmentController::class, 'paymentHistory']);
        Route::get('/payment-history/{id}', [AppointmentController::class, 'paymentHistoryDetail']);
        Route::get('/deduction-list', [AppointmentController::class, 'deductionList']);
        Route::get('/deduction-list/{id}', [AppointmentController::class, 'deductionListDetail']);
        Route::post('/time-slots', [DoctorController::class, 'createTimeSlots']);
        Route::get('/time-slots', [DoctorController::class, 'createTimeSlots']);
        Route::post('/personal-info', [DoctorController::class, 'createPersonalInfo']);
        Route::get('/progress', [ DoctorRegistrationController::class, 'profileProgress']);

        Route::get('/certifications', [ DoctorRegistrationController::class,'getCertifications'])->name('getCertification');
        Route::post('/register/doctor/education-experience-api',[ DoctorRegistrationController::class,'educationExperienceApi'])->name('education_experience_api');
        Route::get('/register/doctor/education-experience-api', [ DoctorRegistrationController::class,'educationExperienceApi'])->name('education_experience_get_api');
        Route::get('/register/doctor/education-experience-api/{id}',[ DoctorRegistrationController::class,'educationExperienceApi'])->name('education_experience_api');
        Route::post('/register/doctor/education-api',[ DoctorRegistrationController::class,'educationApi'])->name('education_api');
        Route::get('/register/doctor/education-api',[ DoctorRegistrationController::class,'educationApi'])->name('education_api');
        Route::post('/register/doctor/experience-api',[ DoctorRegistrationController::class,'experienceApi'])->name('experience_api');
        Route::post('/register/doctor/practice-detail', [ DoctorRegistrationController::class,'practiceDetails'])->name('practice_detail_api');
        Route::get('/institute',[ DoctorRegistrationController::class,'getInstitute'])->name('institute_api');
        Route::get('/designation',[ DoctorRegistrationController::class,'getDesignation'])->name('designation_api');


        Route::prefix('appointment')->group(function(){
            Route::get('/list', [AppointmentController::class, 'getDoctorAppointments']);
            Route::get('/dashboard', [AppointmentController::class, 'dashboard']);
            Route::get('/{appointmentId}', [AppointmentController::class, 'getAppointments']);
            Route::get('/next-appointment/data', [AppointmentController::class, 'doctorNextAppointment']);
            Route::post('/details/add/{appointment}', [AppointmentController::class, 'addAppointmentDetails']);
        });
        Route::get('/check-online', [DoctorController::class, 'checkOnline']);
        Route::post('/instant-online-offline', [DoctorController::class, 'onlineOffline']);

        Route::get('/availability', [DoctorController::class, 'updateAvailablity']);
        Route::get('/clinic-listing', [DoctorController::class, 'clinicListing']);
        Route::get('/all-clinic-listing', [DoctorController::class, 'allClinicListing']);
        Route::get('/clinic-status/{clinicId}', [ClinicController::class, 'updateStatus']);
        Route::delete('/clinic-delete/{clinicId}', [ClinicController::class, 'deleteClinic']);
    });

    // Routes for Clinic Routes
    Route::prefix('clinic-record')->group(function(){
        Route::post('/{id?}', [ClinicController::class, 'createClinic']);
    });

    // Routes for Doctor's bank details
    Route::prefix('bank-detail')->group(function(){
        Route::post('/', [BankDetailController::class, 'createBankdetail']);
        Route::post('/updateBankDetails', [BankDetailController::class, 'updateBankdetail']);
        Route::get('/', [BankDetailController::class, 'getBankDetail']);
        Route::delete('/', [BankDetailController::class, 'deleteBankDetail']);
    });
}
    // Routes for Doctors
    Route::prefix('doctor')->group(function(){
        Route::get('listing', [DoctorController::class, 'getDoctors']);
        Route::post('/registration', [UserController::class, 'doctorRegistration']);
        Route::post('/registration-full', [UserController::class, 'doctorRegistrationAfterOTP']);
        Route::post('/login-via-phone', [UserController::class, 'doctorLoginViaPhone']);
        Route::post('/login-via-phone-otp', [UserController::class, 'doctorLoginViaPhoneOTP']);
        Route::post('/login', [UserController::class, 'doctorLogin']);
        Route::get('/services', [UserController::class, 'getServices']);
        Route::get('/service/delete/{id}', [UserController::class, 'deleteService']);
        Route::get('/education/delete/{id}', [UserController::class, 'deleteEducation']);
        Route::post('/password/update', [UserController::class, 'updatePassword']);
        Route::get('/earning', [DoctorController::class, 'getEarning']);
        Route::get('/check-online', [DoctorController::class, 'checkOnline']);

        Route::get('/availability', [DoctorController::class, 'updateAvailablity']);
        Route::get('/clinic-listing', [DoctorController::class, 'clinicListing']);
        Route::get('/clinic-status/{clinicId}', [ClinicController::class, 'updateStatus']);
        Route::delete('/clinic-delete/{clinicId}', [ClinicController::class, 'deleteClinic']);
    });
