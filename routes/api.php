<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{ApiTokenController, FitnessController, NotificationController, UserController, FamilyMemberController,
    MedicalRecordController, ReviewController, ClinicController, SubscriptionController, AppointmentController, ArticleController,
    HealthScanController, PageController, PaymentController, SettingController, InstantConsultationController, ApiGeneralController,
    CronJobController, InstantMedicalRecordController, DoctorReviewsController, FeatureDoctorController};
use App\Http\Common\{OnesignalHelper, ResponseHelper};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix(Request()->segment(2) === "v2" ? "v2" : "v1")->group(function() {
    Route::get('share-sehat-scan-message/{healthScan}', [HealthScanController::class, 'shareSehatScanMessage'])->missing(function () {
        return ResponseHelper::returnJsonResponse(404, 'Invalid health scan id.');
    });
    Route::post('verify-mobile', [UserController::class, 'checkUserNumber']);
    Route::post('error_logs', [ApiTokenController::class, 'error_logs']);
    Route::get('/get-all-videos', [UserController::class, 'getAllVideos']);
    Route::get('/youtube-videos', [UserController::class, 'getYoutubeVideos']);
    Route::post('/get/token', [ApiTokenController::class, 'getToken']);
    Route::get('/testSms', [UserController::class, 'testSms']);
    Route::get('/get-weight-height', [ApiGeneralController::class, 'getWeightAndHeight']);
    Route::post('/bankalfalah/handshake', [PaymentController::class, 'bankalfalah_handshake']);
    // Routes for Search for Articles
    Route::get('/article/search', [ArticleController::class, 'searchArticle'])->name('article-searches');
    // Routes for Search by Symptoms
    Route::get('/search/symptoms', [ArticleController::class, 'SearchBySymptoms'])->name('search-symptoms');
    // Routes for Search by popular
    Route::get('/search/popular', [ArticleController::class, 'SearchByPopular'])->name('search-Popular');
    // Routes for Search by suggested
    Route::get('/search/suggested', [ArticleController::class, 'SearchBysuggested'])->name('search-suggested');
    // Routes for expire subscriptions
    Route::get('/cron/expire-subscription', [CronJobController::class, 'expireSubscription']);
    // Routes for appointment notifications or reminder
    Route::get('/cron/appointment-reminder', [CronJobController::class, 'appointmentReminder']);
    // Routes for Specialities Listing
    Route::get('/specialities', [ArticleController::class, 'getSpecialities']);
    Route::get('/feature/doctor', [FeatureDoctorController::class, 'index']);
    // Routes for Register Doctors
    Route::post('/doctor/registration-short', [UserController::class, 'doctorRegistrationShortPhoneOnly']);
    // Routes for Cities Listing
    Route::get('/cities', [ArticleController::class, 'getCities']);
    // Routes for Regenerate
    Route::post('/resend-otp', [UserController::class, 'resendOtp']);
    Route::post('/check-otp', [UserController::class, 'checkOtp']);
    Route::post('doctor/forgot/password', [UserController::class, 'forgotPasswordMail']);
    Route::post('doctor/forgot/password/otp', [UserController::class, 'forgotPassword']);
    Route::post('doctor/password/reset', [UserController::class, 'resetPassword']);
    Route::get('sendToUserTestingMethod', [ApiGeneralController::class, 'sendToUserTestingMethod']);
    require __DIR__.'/sale.php';
    require __DIR__.'/expert.php';
    Route::get('fitness-expert-listing', [FitnessController::class, 'getFitnessExperts']);
    Route::get('fitness-expert/{id}', [FitnessController::class, 'getFitnessExpertsById']);
    Route::post('/get-a-link', [UserController::class, 'getAlink']);
    Route::get('device-check', [ApiTokenController::class, 'deviceCheck']);
    // AUTH ROUTES
    if(Request()->segment(2) === 'v1'){
        Route::group(['middleware' => "api_auth"], function () {
            Route::post('/onesignal/player', [UserController::class, 'setOneSignalPlayer']);
            Route::post('/social-login', [UserController::class, 'socialLogin']);
            Route::post('/login', [UserController::class, 'login']);
            Route::post('/verify-otp', [UserController::class, 'verifyOtp']);
            Route::post('/doctor-otp', [UserController::class, 'doctorOtp']);
            // Routes for Diseases related API's
            Route::get('/disease', [ArticleController::class, 'getDiseases']);
            Route::post('/disease', [ArticleController::class, 'addDiseases']);
            // Routes for Drugs related API's
            Route::get('/drug', [ArticleController::class, 'getDrugs']);
            // Routes for Dynamic Menu related API's
            Route::get('/menu', [ArticleController::class, 'getMenu']);
            // Routes for Medicines Listing
            Route::get('/medicine', [ArticleController::class, 'getMedicines']);
            // Routes for Dynamic Footer Listing
            Route::get('/footer', [ArticleController::class, 'getFooters']);
            // Routes for Topics Listing
            Route::get('/topics', [ArticleController::class, 'getTopics']);
            // Routes for Sub Topics Listing
            Route::get('/sub-topics', [ArticleController::class, 'getSubTopics']);
            // Routes for Cities Listing
            Route::get('/cities', [ArticleController::class, 'getCities']);
            // Routes for Prescription Elements Listing
            Route::get('/prescription-elements', [ArticleController::class, 'getPrescriptionElementListing']);
            //Routes For Adding Prescription Elements
            Route::post('/prescription-elements', [ArticleController::class, 'addPrescription']);
            // Routes for Clinic Listing
            Route::get('/clinics', [ArticleController::class, 'getClinics']);
            // Routes for Prescription Element Types Listing
            Route::get('/prescription-element-types', [ArticleController::class, 'getPrescriptionElementTypeListing']);
            // Routes for Universities Listing
            Route::get('/universities', [ArticleController::class, 'getUniversities']);
            // Routes for Degrees Listing
            Route::get('/degrees', [ArticleController::class, 'getDegrees']);
            // Routes for services Listing
            Route::get('/services', [ArticleController::class, 'getServices']);
            // Routes for Specialities Listing
            Route::get('/specialities', [ArticleController::class, 'getSpecialities']);
            // Routes for services Listing
            Route::get('/services', [ArticleController::class, 'getServices']);
            // Routes for FAQ's
            Route::get('/faqs', [UserController::class, 'getFaqs']);
            // Routes for FAQ's
            Route::post('/newsletter', [UserController::class, 'subscribeNewsletter']);
            Route::get('/subscription', [SubscriptionController::class, 'getPackages']);

            Route::prefix('article')->group(function () {
                // Routes for Get the Next Article Details with respect to the current article tags OR related articles
                Route::get('/next-article', [ArticleController::class, 'getNextRelatedArticle']);
                // Routes for Search for Articles
                Route::get('/article/search', [ArticleController::class, 'searchArticle'])->name('article-search');
                // Get the article details
                Route::get('/{article}', [ArticleController::class, 'getArticles'])->missing(function () {
                    return ResponseHelper::returnJsonResponse(404, 'Invalid article id.');
                });
                Route::get('/list/{category}', [ArticleController::class, 'getArticlesList']);
            });

            Route::get('/page/{page}', [PageController::class, 'getPageDetails'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid page name.');
            });
            // Routes for Get the Nested Topic Details
            Route::get('/topic/{topic}/{subTopic}/{nestedTopic}', [PageController::class, 'getTopicDetails'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid nested topic name.');
            });
            // Routes for Get the Sub Topic Details
            Route::get('/topic/{topic}/{subTopic}', [PageController::class, 'getTopicDetails'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid sub topic name.');
            });
            // Routes for Get the Topic Details
            Route::get('/topic/{topic}', [PageController::class, 'getTopicDetails'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid topic name.');
            });
            // Routes for Get Privacy Policy
            Route::get('/privacy-policy', [SettingController::class, 'privacyContent']);
            // Routes for Get the Disease Details
            Route::get('/disease/{disease}', [PageController::class, 'getDiseaseDetails'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid disease name.');
            });
            // Routes for Get the Drug Details
            Route::get('/drug/{drug}', [PageController::class, 'getDrugDetails'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid drug name.');
            });
            // Routes for Get the Doctor Details by applying filters
            Route::get('/find-a-doctor', [UserController::class, 'searchDoctors']);
            Route::post('/find-a-doctor', [UserController::class, 'findADoctor']);

            // Routes for Get the Info Modal  Details
            Route::get('/info-modal/{key}', [SettingController::class, 'getInfoModalDetail']);
            // Routes for Get the Doctor Clinic Details
            Route::get('/doctor-clinic-time/{doctorClinicId}', [ClinicController::class, 'getAllTimeSlots']);
            require __DIR__ . '/doctor.php';
            Route::get('doctor-review/{doctor}', [DoctorReviewsController::class, 'getReview'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid doctor id.');
            });
            Route::get('/user', [UserController::class, 'getUser']);
            Route::post('/health-scan', [HealthScanController::class, 'createHealthScan']);
            Route::get('health-scan/scan-dates', [HealthScanController::class, 'listHealthScansDate']);
            Route::post('health-scan/check', [HealthScanController::class, 'checkLimitation']);
            Route::get('health-scan/{healthScan}', [HealthScanController::class, 'getHealthScanDetails'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid health scan id.');
            });
        });
    }else{
        if (\Illuminate\Support\Facades\Request::header('locale') == null) {
            $app = \App();
            $app->request->headers->set('locale', 1);
        }
        Route::post('/social-login', [UserController::class, 'socialLogin']);
        Route::post('/onesignal/player', [UserController::class, 'setOneSignalPlayer']);
        Route::post('/login', [UserController::class, 'login']);
        Route::post('/verify-otp', [UserController::class, 'verifyOtp']);
        Route::post('/doctor-otp', [UserController::class, 'doctorOtp']);
        // Routes for Diseases related API's
        Route::get('/disease', [ArticleController::class, 'getDiseases']);
        Route::post('/disease', [ArticleController::class, 'addDiseases']);
        // Routes for Drugs related API's
        Route::get('/drug', [ArticleController::class, 'getDrugs']);
        // Routes for Dynamic Menu related API's
        Route::get('/menu', [ArticleController::class, 'getMenu']);
        // Routes for Medicines Listing
        Route::get('/medicine', [ArticleController::class, 'getMedicines']);
        // Routes for Dynamic Footer Listing
        Route::get('/footer', [ArticleController::class, 'getFooters']);
        // Routes for Topics Listing
        Route::get('/topics', [ArticleController::class, 'getTopics']);
        // Routes for Sub Topics Listing
        Route::get('/sub-topics', [ArticleController::class, 'getSubTopics']);
        // Routes for Cities Listing
        Route::get('/cities', [ArticleController::class, 'getCities']);
        // Routes for Prescription Elements Listing
        Route::get('/prescription-elements', [ArticleController::class, 'getPrescriptionElementListing']);
        // Routes for Adding Prescription Elements
        Route::post('/prescription-elements', [ArticleController::class, 'addPrescription']);
        // Routes for Clinic Listing
        Route::get('/clinics', [ArticleController::class, 'getClinics']);
        // Routes for Prescription Element Types Listing
        Route::get('/prescription-element-types', [ArticleController::class, 'getPrescriptionElementTypeListing']);
        // Routes for Universities Listing
        Route::get('/universities', [ArticleController::class, 'getUniversities']);
        // Routes for Degrees Listing
        Route::get('/degrees', [ArticleController::class, 'getDegrees']);
        // Routes for services Listing
        Route::get('/services', [ArticleController::class, 'getServices']);
        // Routes for Specialities Listing
        Route::get('/specialities', [ArticleController::class, 'getSpecialities']);
        // Routes for services Listing
        Route::get('/services', [ArticleController::class, 'getServices']);
        // Routes for FAQ's
        Route::get('/faqs', [UserController::class, 'getFaqs']);
        // Routes for FAQ's
        Route::post('/newsletter', [UserController::class, 'subscribeNewsletter']);
        Route::get('/subscription', [SubscriptionController::class, 'getPackages']);

        Route::prefix('article')->group(function () {
            // Routes for Get the Next Article Details with respect to the current article tags OR related articles
            Route::get('/next-article', [ArticleController::class, 'getNextRelatedArticle']);
            // Routes for Search for Articles
            Route::get('/article/search', [ArticleController::class, 'searchArticle'])->name('article-search');
            // Get the article details
            Route::get('/{article}', [ArticleController::class, 'getArticles'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid article id.');
            });
            Route::get('/list/{category}', [ArticleController::class, 'getArticlesList']);
        });

        Route::get('/page/{page}', [PageController::class, 'getPageDetails'])->missing(function () {
            return ResponseHelper::returnJsonResponse(404, 'Invalid page name.');
        });
        // Routes for Get the Nested Topic Details
        Route::get('/topic/{topic}/{subTopic}/{nestedTopic}', [PageController::class, 'getTopicDetails'])->missing(function () {
            return ResponseHelper::returnJsonResponse(404, 'Invalid nested topic name.');
        });
        // Routes for Get the Sub Topic Details
        Route::get('/topic/{topic}/{subTopic}', [PageController::class, 'getTopicDetails'])->missing(function () {
            return ResponseHelper::returnJsonResponse(404, 'Invalid sub topic name.');
        });
        // Routes for Get the Topic Details
        Route::get('/topic/{topic}', [PageController::class, 'getTopicDetails'])->missing(function () {
            return ResponseHelper::returnJsonResponse(404, 'Invalid topic name.');
        });
        // Routes for Get Privacy Policy
        Route::get('/privacy-policy', [SettingController::class, 'privacyContent']);
        // Routes for Get the Disease Details
        Route::get('/disease/{disease}', [PageController::class, 'getDiseaseDetails'])->missing(function () {
            return ResponseHelper::returnJsonResponse(404, 'Invalid disease name.');
        });
        // Routes for Get the Drug Details
        Route::get('/drug/{drug}', [PageController::class, 'getDrugDetails'])->missing(function () {
            return ResponseHelper::returnJsonResponse(404, 'Invalid drug name.');
        });
        // Routes for Get the Doctor Details by applying filters
        Route::get('/find-a-doctor', [UserController::class, 'searchDoctors']);
        Route::post('/find-a-doctor', [UserController::class, 'findADoctor']);

        // Routes for Get the Info Modal  Details
        Route::get('/info-modal/{key}', [SettingController::class, 'getInfoModalDetail']);
        // Routes for Get the Doctor Clinic Details
        Route::get('/doctor-clinic-time/{doctorClinicId}', [ClinicController::class, 'getAllTimeSlots']);
        Route::get('doctor-review/{doctor}', [DoctorReviewsController::class, 'getReview'])->missing(function () {
            return ResponseHelper::returnJsonResponse(404, 'Invalid doctor id.');
        });
        require __DIR__ . '/doctor.php';
        Route::get('/user', [UserController::class, 'getUser']);
        Route::post('/health-scan', [HealthScanController::class, 'createHealthScan']);
        Route::get('health-scan/scan-dates', [HealthScanController::class, 'listHealthScansDate']);
        Route::post('health-scan/check', [HealthScanController::class, 'checkLimitation']);
        Route::get('health-scan/{healthScan}', [HealthScanController::class, 'getHealthScanDetails'])->missing(function () {
            return ResponseHelper::returnJsonResponse(404, 'Invalid health scan id.');
        });
    }

    Route::group(['middleware' => Request()->segment(2) === "v2" ? "auth:api" : "api_auth"], function () {
        Route::post('/update-profile', [UserController::class, 'updateProfile']);
        Route::post('/update-profile-image', [UserController::class, 'updateProfileImage']);
        Route::post('/update-username-email', [UserController::class, 'updateUserNameAndEmail']);
        Route::get('/logout', [UserController::class, 'logout']);
        Route::get('/notifications', [NotificationController::class, 'getNotificationByUserId']);
        Route::post('/notifications/update', [NotificationController::class, 'postNotificationByUserId']);
        Route::get('/markRead-notifications/{notification_id}', [NotificationController::class, 'markRead']);
        // Routes for Family Members
        Route::prefix('family-member')->group(function () {
            Route::post('/', [FamilyMemberController::class, 'createFamilyMember']);
            Route::post('/{familyMember}', [FamilyMemberController::class, 'updateFamilyMember'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid family member id.');
            });
            Route::get('/', [FamilyMemberController::class, 'getFamilyMember']);
            Route::delete('/{familyMember}', [FamilyMemberController::class, 'deleteFamilyMember'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid family member id.');
            });
        });
        // Routes for MedicalRecords
        Route::prefix('medical-record')->group(function () {
            Route::post('/', [MedicalRecordController::class, 'createMedicalRecord']);
            Route::post('/share', [MedicalRecordController::class, 'shareMedicalRecord']);
            Route::delete('/unshare/{medicalRecord}', [MedicalRecordController::class, 'unshareMedicalRecord'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid medical record id.');
            });
            Route::post('/update', [MedicalRecordController::class, 'updateMedicalRecord']);
            Route::get('/', [MedicalRecordController::class, 'getMedicalRecord']);
            Route::get('/download-medical-record-pdf', [MedicalRecordController::class, 'downloadMedicalRecordPdf']);
            Route::get('/search-medical-record', [MedicalRecordController::class, 'searchMedicalRecord']);
            Route::delete('/{medicalRecord}', [MedicalRecordController::class, 'deleteMedicalRecord'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid medical record id.');
            });
            Route::delete('file/{medicalRecordFile}', [MedicalRecordController::class, 'deleteMedicalRecordFile'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid medical record file id.');
            });
            Route::get('/{medicalRecord}', [MedicalRecordController::class, 'getMedicalRecordDetail'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid medical record id.');
            });
        });
        //Route for MedicalHistory
        Route::prefix('medical-history')->group(function () {
            Route::get('/', [MedicalRecordController::class, 'getMedicalhistory']);
            Route::get('/download-medical-history-pdf/{id}', [MedicalRecordController::class, 'downloadMedicalHistoryPdf']);
        });
        // Routes for InstantMedicalRecords
        Route::prefix('instant-medical-record')->group(function () {
            Route::post('/', [InstantMedicalRecordController::class, 'createMedicalRecord']);
            Route::post('/share', [InstantMedicalRecordController::class, 'shareMedicalRecord']);
            Route::delete('/unshare/{instantMedicalRecord}', [InstantMedicalRecordController::class, 'unshareMedicalRecord'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid medical record id.');
            });
            Route::post('/update', [InstantMedicalRecordController::class, 'updateMedicalRecord']);
            Route::get('/', [InstantMedicalRecordController::class, 'getMedicalRecord']);
            Route::get('/latest', [InstantMedicalRecordController::class, 'getLatestMedicalRecord']);
            Route::delete('/{instantMedicalRecord}', [InstantMedicalRecordController::class, 'deleteMedicalRecord'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid medical record id.');
            });
            Route::delete('file/{instantMedicalRecordFile}', [InstantMedicalRecordController::class, 'deleteMedicalRecordFile'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid medical record file id.');
            });
        });
        // Routes for Reviews Routes
        Route::prefix('review')->group(function () {
            Route::post('/', [ReviewController::class, 'createReview']);
            Route::get('/{doctor}', [ReviewController::class, 'getReview'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid doctor id.');
            });
        });
        // Routes for Subscription
        Route::prefix('subscription')->group(function () {
            Route::get('/{subscription}', [SubscriptionController::class, 'getPackagesDetail'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid subscription id.');
            });
        });

        Route::prefix('appointment')->group(function () {
            Route::post('/', [AppointmentController::class, 'createAppointment']);
            Route::get('/{appointment}', [AppointmentController::class, 'getAppointmentDetail'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid Appointment ID.');
            });
            Route::post('/{appointment}', [AppointmentController::class, 'updateAppointment'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid Appointment ID.');
            });
            Route::get('/', [AppointmentController::class, 'getAppointments']);
            Route::get('/cancel/{appointment}', [AppointmentController::class, 'cancelAppointment'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid Appointment ID.');
            });
            Route::get('/download-prescription/{id}', [AppointmentController::class, 'downloadPrescription']);
            Route::post('/user/check-user', [UserController::class, 'checkUser']);
            Route::post('/user/check-otp', [UserController::class, 'checkAppointmentOTP']);
            Route::post('/user/resend-otp', [UserController::class, 'checkAppointmentResendOTP']);

        });
        // Route::post('/str/compose',[AppointmentController::class,'strCompose']);
        // Routes for Articles related API's


        // Routes for Health Scans API's
        Route::prefix('health-scan')->group(function () {
            // Route::post('/', [HealthScanController::class, 'createHealthScan']);
            Route::get('/scan-dates', [HealthScanController::class, 'listHealthScansDate']);
            // Route::get('/{healthScan}', [HealthScanController::class, 'getHealthScanDetails'])->missing(function () {
            //     return ResponseHelper::returnJsonResponse(404, 'Invalid health scan id.');
            // });
            Route::get('/', [HealthScanController::class, 'listHealthScans']);
            // Route::post('/check', [HealthScanController::class, 'checkLimitation']);

            Route::delete('/delete-all-scan-record', [HealthScanController::class, 'deleteHealthScanAll'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid health scan id.');
            });
            Route::delete('/{healthScanId}', [HealthScanController::class, 'deleteHealthScan'])->missing(function () {
                return ResponseHelper::returnJsonResponse(404, 'Invalid health scan date.');
            });

        });
        // Routes for Get the Page Details
        Route::post('/content-feedback', [PageController::class, 'contentFeedback'])->name('content-feedback');

        // Routes for Like or Un-Like article
        Route::post('/review-article', [UserController::class, 'likeOrUnLike']);
        // Routes f or payments for appointment or subscription packages
        Route::post('/payment', [PaymentController::class, 'makePayment']);
        // Routes for User Dashboard Details
        Route::get('/dashboard', [UserController::class, 'getDashboardDetails']);
        // Routes for Instant Consultation
        Route::get('/instant-consultation/start', [InstantConsultationController::class, 'startConsultaion']);

        Route::get('/patient_connected/{id}', [InstantConsultationController::class, 'patient_connected']);
        // Routes for Agora Details
        Route::get('/generate-agora-link', [InstantConsultationController::class, 'generateAgoraLink']);
        Route::get('/generate-agora-rtm-token', [InstantConsultationController::class, 'rtmTokenGenrator']);
        // General ROUTES for getting the seeting details
        Route::get('/preferances', [ApiGeneralController::class, 'preferences']);
        // Routes for Get the specialities, services and cities with doctor counts
        Route::get('/search-doctor-by-category', [UserController::class, 'searchDoctorByCategory']);
        // Routes for Get the Header searching i.e articles, topics, diseases etc
        Route::get('/search', [UserController::class, 'headerSearch']);
        // Routes for get vitals or health scans details with hash id
        Route::get('/vitals/{id}', [HealthScanController::class, 'getHealthScanByHash']);
        // Routes for Buy subscriptions and get doctor details
        Route::get('/buy-subscription-doctor-details', [SubscriptionController::class, 'getDoctorDetails']);
        // Routes for get doctors and hospitals details
        Route::get('/widget-search-doctor', [UserController::class, 'widgetDoctorSearch']);
        // Routes for user delete
        Route::get('/delete-user', [UserController::class, 'deleteUserAccount']);
        Route::get('/check-trial-consultation', [UserController::class, 'checkTrialConsultation']);
        // Routes for Reviews Routes
        Route::prefix('doctor-review')->group(function () {
            Route::post('/', [DoctorReviewsController::class, 'createReview']);
        });
        Route::get('check-doctor-appointment/{doctor}', [DoctorReviewsController::class, 'checkDoctorAppointment'])->missing(function () {
            return ResponseHelper::returnJsonResponse(404, 'Invalid doctor id.');
        });
        // Routes for Instant Consultation
        Route::post('/instant-consultation/cancel', [InstantConsultationController::class, 'cancelInstantConsultaion']);
        Route::get('/instant-consultation/waiting-time', [InstantConsultationController::class, 'getWaitingTime']);
        Route::get('/verify-payment', [InstantConsultationController::class, 'verifyPayment']);
        Route::get('/patient-info', [UserController::class, 'patientInfoGet']);
        Route::post('/patient-info', [UserController::class, 'patientInfoCreate']);
        Route::post('/add-medicine', [ArticleController::class, 'addMedicine']);
        Route::get('/switch-doctor', [InstantConsultationController::class, 'switchDoctor']);
        Route::post('/switch-doctor', [InstantConsultationController::class, 'switchDoctorReason']);
        Route::get('/verify-payment-download', [InstantConsultationController::class, 'verifyPaymentDownload']);
        Route::get('/payment-receipt-download', [InstantConsultationController::class, 'paymentReceiptDownload']);
        Route::get('single-review/{id}', [ReviewController::class, 'getSingleReview']);
        Route::get('/journey-completed', [UserController::class, 'journeyCompleted']);
    });
    Route::get('/reson-for-visits', [InstantConsultationController::class, 'reasonForVisit']);
    Route::put('/reson-for-visits/{appointment}', [InstantConsultationController::class, 'updateReasonForVisit'])->missing(function () {
        return ResponseHelper::returnJsonResponse(404, 'Invalid appointment id.');
    });
    Route::post('/corporat-query', [UserController::class, 'CorporatQuery']);
    Route::get('/prescription-elements', [ArticleController::class, 'getPrescriptionElementListing']);
    Route::get('/total-experience', [UserController::class, 'totalExperience']);
});
