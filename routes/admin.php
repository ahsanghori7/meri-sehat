<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\User\{ManagePasswordController, DetailsController, PatientController, UserController};
use App\Http\Controllers\Auth\{AuthenticatedSessionController};
use App\Http\Controllers\Admin\{ActivityLogsController,
    FaqController,
    FitnessExpertsController,
    MediaLanguageController,
    TopicsController,
    SettingsController,
    SiteContentController,
    InfoModalController,
    CityController,
    SubTopicController,
    DiseaseController,
    WidgetController,
    ArticleController,
    TagsController,
    SpecialityController,
    ServiceController,
    LanguageController,
    MenuController,
    ContentTestController,
    PageController,
    SubscriptionManagementController,
    RightsController,
    AdminController,
    DoctorManagementController,
    FaqCategoryController,
    AdController,
    DoctorEarningController,
    FooterMenuController,
    ArticleBadgeController,
    HealthScanController,
    AppointmentController,
    ArticleFactController,
    DrugController,
    QuestionaireController,
    ReportController,
    PromoCodeManagementController};
use App\Http\Controllers\{
    NewsletterController
};
use App\Http\Controllers\TestMailController;
use App\Http\Common\Queue;



Route::middleware(['auth','restrict_portal'])->prefix('admin')->group(function () {

    Route::get('home', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.home');

    Route::post('toggle-status/{id}/{val}/{table}', function(){
        return "Code to toggle status";
        // DB::table()->where('id',$id);
    })->name('toggle_status');
    Route::get('logout', [AuthenticatedSessionController::class,'destroy_get']);
    Route::get('/', function () {
        return redirect()->route('admin.home');
    });

    //--------- Right Management -----------------------------//
    Route::get('/roles-and-permission/roles',[RightsController::class,'show_roles'])->name('roles.show');
    Route::get('/roles-and-permission/roles/add',[RightsController::class,'add_role'])->name('role.add');
    Route::post('/roles-and-permission/roles/add',[RightsController::class,'create_role'])->name('role.create');
    Route::get('/roles-and-permission/roles/edit/{role}',[RightsController::class,'edit_role'])->name('roles.edit');
    Route::post('/roles-and-permission/roles/update/{role}',[RightsController::class,'update_role'])->name('role.update');


    //  password management
    Route::get('manage-password',[ManagePasswordController::class, 'show_update'])->name('manage-password');
    Route::post('manage-password',[ManagePasswordController::class, 'update'])->name('manage-password');
    // user management
    Route::get('user/view', [DetailsController::class, 'show_all']);
    Route::get('user/edit', [DetailsController::class, 'edit_profile'])->name('updateProfile');
    Route::post('user/edit', [DetailsController::class, 'edit_profile'])->name('updateProfile');
    Route::get('user/{id}/profile', [DetailsController::class,'show']);

    Route::get('users',[UserController::class,'view'])->name('user_management-view');
    Route::get('user/fetch',[UserController::class,'fetch'])->name('user_management-fetch');

    Route::prefix('patients')->controller(PatientController::class)->name('patient-')->group(function () {
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

    Route::prefix('activity-logs')->controller(ActivityLogsController::class)->name('activity-logs-')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('fetch', 'fetch')->name('fetch');
        Route::get('view/{id}', 'view')->name('view');
    });

    // settings managements
    Route::get('settings/list', [SettingsController::class, 'index'])->name('settings');
    Route::post('settings/list', [SettingsController::class, 'update'])->name('settings');


    // EMPLOYEE
    Route::get('admin-users',[AdminController::class,'view'])->name('admin_users-view');
    Route::get('admin-user/fetch',[AdminController::class,'fetch'])->name('admin_users-fetch');

    Route::get('admin-user/add',[AdminController::class,'add'])->name('admin_users-add');
    Route::post('admin-user/add',[AdminController::class,'add'])->name('admin_users-add');

    Route::get('admin-user/edit/{id}',[AdminController::class,'edit'])->name('admin_users-edit');
    Route::post('admin-user/edit/{id}',[AdminController::class,'edit'])->name('admin_users-edit');


    // content management
    Route::prefix('info-model')->controller(InfoModalController::class)->name('info-model-')->group(function () {
        include('general_routes.php');
    });

    Route::prefix('faq-category')->controller(FaqCategoryController::class)->name('faq-category-')->group(function () {
        include('general_routes.php');
    });

    Route::prefix('faq')->controller(FaqController::class)->name('faq-')->group(function () {

        //Draft
        Route::get('mark-draft/{id}', 'markDraft')->name('mark-draft');
        Route::get('goto-live/{id}', 'gotoLive')->name('goto-live');
        Route::get('draft', 'draftView')->name('draft');
//        Route::get('draft-fetch', 'draftFetch')->name('draft-fetch');
        Route::get('draft/add','add')->name('draft-add');
        Route::post('draft/add','add')->name('draft-add');
        Route::get('draft/edit/{id}','edit')->name('draft-edit');
        Route::post('draft/edit/{id}','edit')->name('draft-edit');

        Route::get('faq-archive/{id}',[FaqController::class, 'faqArchive'])->name('faqArchive');
        include('general_routes.php');
    });

    Route::prefix('city')->controller(CityController::class)->name('city-')->group(function () {
        include('general_routes.php');
    });


    Route::prefix('site-content')->controller(ContentTestController::class)->name('site-content-')->group(function () {
        include('general_routes.php');
    });

    Route::prefix('topics')->controller(TopicsController::class)->name('topics-')->group(function () {
        Route::get('linking-page/{id}','linkWithPage')->name('linking-page');
        Route::get('get-topics-parent-by-type',  'getParentByType')->name('get-parent-by-type');
        Route::get('fetch',[TopicsController::class,'fetch'])->name('topics-fetch');
        Route::get('topic-archive/{id}',[TopicsController::class, 'topicArchive'])->name('topicArchive');

        //Draft
        Route::get('mark-draft/{id}', 'markDraft')->name('mark-draft');
        Route::get('goto-live/{id}', 'gotoLive')->name('goto-live');
        Route::get('draft', 'draftView')->name('draft');
        Route::get('draft-fetch', 'draftFetch')->name('draft-fetch');
        Route::get('draft/add','add')->name('draft-add');
        Route::post('draft/add','add')->name('draft-add');
        Route::get('draft/edit/{id}','edit')->name('draft-edit');
        Route::post('draft/edit/{id}','edit')->name('draft-edit');
        include('general_routes.php');
    });

    Route::prefix('sub_topic')->controller(SubTopicController::class)->name('sub_topic-')->group(function () {
        include('general_routes.php');
    });

    Route::prefix('disease')->controller(DiseaseController::class)->name('disease-')->group(function () {
        Route::get('linking-page/{id}/{lang_id?}',  'linkWithPage')->name('linking-page');

        //Draft
        Route::get('mark-draft/{id}', 'markDraft')->name('mark-draft');
        Route::get('goto-live/{id}', 'gotoLive')->name('goto-live');
        Route::get('draft', 'draft')->name('draft');
        Route::get('draft-fetch', 'draftFetch')->name('draft-fetch');
        Route::get('draft/add','add')->name('draft-add');
        Route::post('draft/add','add')->name('draft-add');
        Route::get('draft/edit/{id}','edit')->name('draft-edit');
        Route::post('draft/edit/{id}','edit')->name('draft-edit');

        Route::get('disease-archive/{id}',[DiseaseController::class, 'diseaseArchive'])->name('diseaseArchive');
        include('general_routes.php');
    });

    Route::prefix('drug')->controller(DrugController::class)->name('drug-')->group(function () {
        Route::get('linking-page/{id}/{lang_id?}',  'linkWithPage')->name('linking-page');

        //Draft
        Route::get('mark-draft/{id}', 'markDraft')->name('mark-draft');
        Route::get('goto-live/{id}', 'gotoLive')->name('goto-live');
        Route::get('draft', 'draft')->name('draft');
        Route::get('draft-fetch', 'draftFetch')->name('draft-fetch');
        Route::get('draft/add','add')->name('draft-add');
        Route::post('draft/add','add')->name('draft-add');
        Route::get('draft/edit/{id}','edit')->name('draft-edit');
        Route::post('draft/edit/{id}','edit')->name('draft-edit');

        Route::get('drug-archive/{id}',[DrugController::class, 'DrugArchive'])->name('DrugArchive');
        include('general_routes.php');
    });

    Route::prefix('widget')->controller(WidgetController::class)->name('widget-')->group(function () {
        include('general_routes.php');
    });

    Route::prefix('article')->controller(ArticleController::class)->name('article-')->group(function () {
        Route::get('get-article-parent-by-type',  'getArticleParentByType')->name('get-parent-by-type');
        Route::get('goto-live/{id}', 'gotoLive')->name('goto-live');
        Route::get('delete-widget/{reference_id}',[ArticleController::class,'deleteWidgetByReference'])->name('delete-widget');
        //Draft
        Route::get('mark-draft/{id}', 'markDraft')->name('mark-draft');
        Route::get('goto-live/{id}', 'gotoLive')->name('goto-live');
        Route::get('draft', 'draft')->name('draft');
        Route::get('wellness-draft', 'draftWellness')->name('wellness-draft');
        Route::get('wellness-draft-fetch',[ArticleController::class,'wellnessdraftFetch'])->name('wellness-draft-fetch');
        Route::get('disease-draft', 'draftDisease')->name('disease-draft');
        Route::get('disease-draft-fetch',[ArticleController::class,'diseasedraftFetch'])->name('disease-draft-fetch');
        Route::get('draft/wellness/add','addWellness')->name('wellness-draft-add');
        Route::post('draft/wellness/add','addWellness')->name('wellness-draft-add');
        Route::get('draft/disease/add','addDisease')->name('disease-draft-add');
        Route::post('draft/disease/add','addDisease')->name('disease-draft-add');

        Route::get('wellness/edit/{id}','editWellness')->name('wellness-edit');
        Route::post('wellness/edit/{id}','editWellness')->name('wellness-edit');
        Route::get('draft/wellness/edit/{id}','editWellness')->name('draft-wellness-edit');
        Route::post('draft/wellness/edit/{id}','editWellness')->name('draft-wellness-edit');

        Route::get('draft/disease/edit/{id}','editDisease')->name('draft-disease-edit');
        Route::post('draft/disease/edit/{id}','editDisease')->name('draft-disease-edit');
        Route::get('disease/edit/{id}','editDisease')->name('disease-edit');
        Route::post('disease/edit/{id}','editDisease')->name('disease-edit');


        Route::get('draft/add','add')->name('draft-add');
        Route::post('draft/add','add')->name('draft-add');
        Route::get('draft/edit/{id}','edit')->name('draft-edit');
        Route::post('draft/edit/{id}','edit')->name('draft-edit');


        Route::get('archive-article/{id}',[ArticleController::class, 'archiveArticle'])->name('archiveArticle');
        Route::get('wellness-view',[ArticleController::class,'wellnessView'])->name('wellness-view');
        Route::get('wellness-fetch',[ArticleController::class,'wellnessFetch'])->name('wellness-fetch');

        Route::get('disease-view',[ArticleController::class,'diseaseView'])->name('disease-view');
        Route::get('disease-fetch',[ArticleController::class,'diseaseFetch'])->name('disease-fetch');

        Route::get('fetch',[ArticleController::class,'fetch'])->name('article-fetch');
        include('general_routes.php');
    });

    Route::prefix('tag')->controller(TagsController::class)->name('tag-')->group(function () {
//
        //Draft
        Route::get('mark-draft/{id}', 'markDraft')->name('mark-draft');
        Route::get('goto-live/{id}', 'gotoLive')->name('goto-live');
        Route::get('draft', 'draftView')->name('draft');
//        Route::get('draft-fetch', 'draftFetch')->name('draft-fetch');
        Route::get('draft/add','add')->name('draft-add');
        Route::post('draft/add','add')->name('draft-add');
        Route::get('draft/edit/{id}','edit')->name('draft-edit');
        Route::post('draft/edit/{id}','edit')->name('draft-edit');


        Route::get('tag-archive/{id}',[TagsController::class, 'tagArchive'])->name('tagArchive');

        include('general_routes.php');
    });

    Route::prefix('speciality')->controller(SpecialityController::class)->name('speciality-')->group(function () {
        Route::get('fetch',[SpecialityController::class,'fetch'])->name('speciality-fetch');
        include('general_routes.php');
    });

    Route::prefix('service')->controller(ServiceController::class)->name('service-')->group(function () {
        Route::get('fetch',[ServiceController::class,'fetch'])->name('service-fetch');
        include('general_routes.php');
    });

    Route::prefix('language')->controller(LanguageController::class)->name('language-')->group(function () {
        include('general_routes.php');
    });

    Route::prefix('promocode')->controller(PromoCodeManagementController::class)->name('promocode-')->group(function () {
        include('general_routes.php');
    });

    Route::prefix('media-language')->controller(MediaLanguageController::class)->name('media-language-')->group(function () {
        include('general_routes.php');
    });

    Route::prefix('menu')->controller(MenuController::class)->name('menu-')->group(function () {
        Route::get('get-menu-parent-by-type',  'getParentsByType')->name('get-parent-by-type');

        Route::get('view/{lang_id}','view')->name('view');
        Route::get('fetch/{lang_id}','fetch')->name('fetch');

        Route::post('update-sequence/{lang_id}/','update_sequence')->name('update_sequence');

        Route::get('add/{lang_id}','add')->name('add');
        Route::post('add/{lang_id}','add')->name('add');

        Route::get('edit/{id}/{lang_id}','edit')->name('edit');
        Route::post('edit/{id}/{lang_id}','edit')->name('edit');
    });

    Route::prefix('page')->controller(PageController::class)->name('page-')->group(function () {
        Route::get('linking-page/{id}/{lang_id?}',  'linkWithPage')->name('linking-page');
        Route::get('get-page-parent-by-type',  'getPageParentByType')->name('get-parent-by-type');
        Route::get('fetch',[PageController::class,'fetch'])->name('page-fetch');
        Route::post('remove-image',[PageController::class,'removeImage'])->name('remove-image');
        Route::get('delete-widget/{reference_id}',[PageController::class,'deleteWidgetByReference'])->name('delete-widget');

        //Draft
        Route::get('mark-draft/{id}', 'markDraft')->name('mark-draft');
        Route::get('goto-live/{id}', 'gotoLive')->name('goto-live');
        Route::get('draft', 'draftView')->name('draft');
        Route::get('draft-fetch', 'draftFetch')->name('draft-fetch');
        Route::get('draft/add','add')->name('draft-add');
        Route::post('draft/add','add')->name('draft-add');
        Route::get('draft/edit/{id}','edit')->name('draft-edit');
        Route::post('draft/edit/{id}','edit')->name('draft-edit');

        Route::get('page-archive/{id}',[PageController::class, 'pageArchive'])->name('pageArchive');
        include('general_routes.php');
    });

    Route::prefix('subscription')->controller(SubscriptionManagementController::class)->name('subscription-')->group(function () {
        include('general_routes.php');
    });

    // Route::get('rearrange/{reference_widget_id}',[ArticleController::class,'reArrangeReferenceWidgets']);
    Route::post('move-widget',[ArticleController::class, 'repositionWidget'])->name('repositionWidget');
    Route::post('add-widget',[ArticleController::class, 'addWidget'])->name('article-addWidget');
    Route::post('page/add-widget',[PageController::class, 'addWidget'])->name('page-addWidget');
    Route::post('delete-widget',[ArticleController::class, 'deleteWidget'])->name('deleteWidget');

    // Doctor management Routes
    Route::prefix('doctor')->controller(DoctorManagementController::class)->name('doctor-')->group(function () {
        include('general_routes.php');
        Route::get('view-profile/{doctor_id}','viewProfile')->name('viewProfile');
        Route::get('view-shifts/{doctor_id}','viewShifts')->name('viewShifts');
        Route::get('view-services/{doctor_id}','viewServices')->name('viewServices');
        Route::get('view-specialities/{doctor_id}','viewSpecialities')->name('viewSpecialities');
        Route::get('view-educations/{doctor_id}','viewEducations')->name('viewEducations');
        Route::get('view-experiences/{doctor_id}','viewExperiences')->name('viewExperiences');
        Route::get('view-ratings/{doctor_id}','viewRatings')->name('viewRatings');
        Route::get('view-diseases/{doctor_id}','viewDiseases')->name('viewDiseases');
        Route::post('diseases/delete/{doctor_id}','deleteDiseases')->name('delete-diseases');
        Route::get('view-videos/{doctor_id}','viewVideos')->name('viewVideos');
        Route::post('videos/delete/{doctor_id}','deleteVideos')->name('delete-videos');
        Route::post('post-ratings','postRatings')->name('postRating');
        Route::get('onboard/update/{id}', 'updateOnboardDoctor')->name('onboard-edit');
        Route::post('onboard/update/{id}', 'editOnboard')->name('onboard-update');

        Route::post('delete','delete')->name('delete');
        Route::get('reviews/{doctor_id}','reviews')->name('reviews');

        //Shifts
        Route::post('shifts/add/{doctor_id}','addShifts')->name('add-shifts');
        Route::post('shifts/delete/{doctor_id}','deleteShifts')->name('delete-shifts');

        // UPDATE SERVICES
        Route::get('services/update/{doctor_id}','updateServices')->name('update-services');
        Route::post('services/update/{doctor_id}','updateServices')->name('update-services');
        Route::get('services/edit/{service_id}','editServices')->name('edit-services');
        Route::post('services/edit/{service_id}','editServices')->name('edit-services');
        Route::post('services/delete/{doctor_id}','deleteServices')->name('delete-services');
        // UPDATE SPECICIALITIES
        Route::get('specialities/update/{doctor_id}','updateSpecialities')->name('update-specialities');
        Route::post('specialities/update/{doctor_id}','updateSpecialities')->name('update-specialities');
        Route::get('specialities/edit/{speciality_id}','editSpecialities')->name('edit-specialities');
        Route::post('specialities/edit/{speciality_id}','editSpecialities')->name('edit-specialities');
        Route::post('specialities/delete/{doctor_id}','deleteSpecialities')->name('delete-specialities');
        // UPDATE EXPERIENCES
        Route::get('experiences/update/{doctor_id}','updateExperiences')->name('update-experiences');
        Route::post('experiences/update/{doctor_id}','updateExperiences')->name('update-experiences');
        Route::get('experiences/edit/{experience_id}','editExperiences')->name('edit-experiences');
        Route::post('experiences/edit/{experience_id}','editExperiences')->name('edit-experiences');
        Route::post('experiences/delete/{doctor_id}','deleteExperiences')->name('delete-experiences');
        // UPDATE EDUCATIONS
        Route::get('educations/update/{doctor_id}','updateEducations')->name('update-educations');
        Route::post('educations/update/{doctor_id}','updateEducations')->name('update-educations');
        Route::get('educations/edit/{education_id}','editEducations')->name('edit-educations');
        Route::post('educations/edit/{education_id}','editEducations')->name('edit-educations');
        Route::post('educations/delete/{doctor_id}','deleteEducations')->name('delete-educations');

        Route::post('diseases/update/{doctor_id}','updateDiseases')->name('update-diseases');
        Route::post('videos/update/{doctor_id}','updateVideos')->name('update-videos');

    });

    // Wellness Experts Routes
    Route::prefix('fitness-experts')->controller(FitnessExpertsController::class)->name('fitness-experts-')->group(function () {
        include('general_routes.php');
        // PROFILE
        Route::get('view-profile/{doctor_id}','viewProfile')->name('viewProfile');
        Route::post('delete','delete')->name('delete');
        // SERVICES
        Route::get('view-services/{doctor_id}','viewServices')->name('viewServices');
        Route::get('services/update/{doctor_id}','updateServices')->name('update-services');
        Route::post('services/update/{doctor_id}','updateServices')->name('update-services');
        Route::get('services/edit/{service_id}','editServices')->name('edit-services');
        Route::post('services/edit/{service_id}','editServices')->name('edit-services');
        Route::post('services/delete/{doctor_id}','deleteServices')->name('delete-services');
        // SPECICIALITIES
        Route::get('view-specialities/{doctor_id}','viewSpecialities')->name('viewSpecialities');
        Route::get('specialities/update/{doctor_id}','updateSpecialities')->name('update-specialities');
        Route::post('specialities/update/{doctor_id}','updateSpecialities')->name('update-specialities');
        Route::get('specialities/edit/{speciality_id}','editSpecialities')->name('edit-specialities');
        Route::post('specialities/edit/{speciality_id}','editSpecialities')->name('edit-specialities');
        Route::post('specialities/delete/{doctor_id}','deleteSpecialities')->name('delete-specialities');
        // EXPERIENCES
        Route::get('view-experiences/{doctor_id}','viewExperiences')->name('viewExperiences');
        Route::get('experiences/update/{doctor_id}','updateExperiences')->name('update-experiences');
        Route::post('experiences/update/{doctor_id}','updateExperiences')->name('update-experiences');
        Route::get('experiences/edit/{experience_id}','editExperiences')->name('edit-experiences');
        Route::post('experiences/edit/{experience_id}','editExperiences')->name('edit-experiences');
        Route::post('experiences/delete/{doctor_id}','deleteExperiences')->name('delete-experiences');
        // EDUCATIONS
        Route::get('view-educations/{doctor_id}','viewEducations')->name('viewEducations');
        Route::get('educations/update/{doctor_id}','updateEducations')->name('update-educations');
        Route::post('educations/update/{doctor_id}','updateEducations')->name('update-educations');
        Route::get('educations/edit/{education_id}','editEducations')->name('edit-educations');
        Route::post('educations/edit/{education_id}','editEducations')->name('edit-educations');
        Route::post('educations/delete/{doctor_id}','deleteEducations')->name('delete-educations');
        // ARTICLES
        Route::get('view-articles/{doctor_id}','viewArticles')->name('viewArticles');
        Route::post('articles/delete/{doctor_id}','deleteArticles')->name('delete-articles');
        Route::post('articles/update/{doctor_id}','updateArticles')->name('update-articles');
        // VIDEOS
        Route::get('view-videos/{doctor_id}','viewVideos')->name('viewVideos');
        Route::post('videos/delete/{doctor_id}','deleteVideos')->name('delete-videos');
        Route::post('videos/update/{doctor_id}','updateVideos')->name('update-videos');
    });

    Route::prefix('ads')->controller(AdController::class)->name('ads-')->group(function () {
        include('general_routes.php');
    });
    Route::prefix('doctor-earning')->controller(DoctorEarningController::class)->name('doctor-earning-')->group(function () {
        // Route::get('add',[DoctorEarningController::class,'priceAdd'])->name('priceadd');
        // Route::post('add',[DoctorEarningController::class,'priceFrom'])->name('pricefrom');
        include('general_routes.php');
        Route::get('details',[DoctorEarningController::class,'doctorEarningDetails'])->name('details');
        Route::get('doctor/earning/list',[DoctorEarningController::class,'doctorEarning'])->name('doctor-earning-list');
        Route::get('doctor/earning/view/{id}',[DoctorEarningController::class,'doctorEarningView'])->name('doctor-earning-view');
        Route::get('doctor/earning/invoice/{id}',[DoctorEarningController::class,'doctorEarningInvoice'])->name('invoice');
        Route::get('delete/{id}',[DoctorEarningController::class,'delete'])->name('delete');

    });

    Route::prefix('footer')->controller(FooterMenuController::class)->name('footer-')->group(function () {
        Route::get('get-footer-parent-by-type',  'getParentsByType')->name('get-parent-by-type');
        Route::get('view/{lang_id}','view')->name('view');
        Route::get('fetch/{lang_id}','fetch')->name('fetch');

        Route::post('update-sequence','update_sequence')->name('update_sequence');

        Route::get('add/{lang_id}','add')->name('add');
        Route::post('add/{lang_id}','add')->name('add');

        Route::get('edit/{id}/{lang_id}','edit')->name('edit');
        Route::post('edit/{id}/{lang_id}','edit')->name('edit');
    });

    Route::prefix('article-badge')->controller(ArticleBadgeController::class)->name('article_badge-')->group(function () {
        include('general_routes.php');
    });

    Route::prefix('health-scan-management')->controller(HealthScanController::class)->name('health-scan-management-')->group(function () {
        include('general_routes.php');
    });

    Route::prefix('appointment')->controller(AppointmentController::class)->name('appointment-')->group(function () {
        Route::get('bookingnow','bookingNow')->name('booking_now');
        Route::get('get-form/{appointment_id}','getQuestionaireForm')->name('get_questionaire_form');
        Route::post('get-form/{appointment_id}','getQuestionaireForm')->name('get_questionaire_form');
        Route::get('detail/{id}','detail')->name('detail');
        Route::post('progress_change','changeProgress')->name('progress_change');
        Route::post('progress_cancelled','changeCancelled')->name('progress_cancelled');
        include('general_routes.php');
    });

    Route::prefix('questionaire-form')->controller(QuestionaireController::class)->name('questionaire_form-')->group(function () {
        include('general_routes.php');
    });

    Route::get('send-mail',[TestMailController::class, 'sendMail'])->name('sendMail');
    Route::get('send-sms',[TestMailController::class, 'sendSms'])->name('sendSms');
    Route::get('create-admin-notification',[TestMailController::class, 'createAdminNotification'])->name('create_admin_notification');
    Route::get('push-notification',[TestMailController::class, 'sendPushNotification'])->name('push_notification');

    Route::prefix('report')->controller(ReportController::class)->name('report-')->group(function () {
        Route::get('sehat-scan','SehatScanReports')->name('sehat_scan_report');
        Route::get('sehat-scan/fetch','FetchSehatScanReports')->name('sehat_scan_report-fetch');

    });

    Route::prefix('newsletter')->controller(NewsletterController::class)->name('newsletter-')->group(function () {
        include('general_routes.php');
    });

    Route::prefix('article-fact')->controller(ArticleFactController::class)->name('article-fact-')->group(function () {
        include('general_routes.php');
    });
    Route::get('doctors/payout', [DoctorEarningController::class, 'payoutTable'])->name('doctors-payout-table');
    Route::get('doctor/deduction', [DoctorEarningController::class, 'deductionLists'])->name('doctors-deduction-list');
    Route::get('doctor/payout/add', [DoctorEarningController::class, 'addScreen'])->name('doctor-payout-add');
    Route::get('doctor/payout/all-doctors', [DoctorEarningController::class, 'searchDoctors'])->name('doctor-payout-doctors');
    Route::get('doctor/payout/payable-appointments/{id}', [DoctorEarningController::class, 'getTransactionTable'])->name('doctor-payout-appointments');
    Route::get('doctor/payout/deduction-appointments/{id}', [DoctorEarningController::class, 'getDeductionTable'])->name('doctor-payout-deduction_appointments');
    Route::post('doctor/payout/add/transaction', [DoctorEarningController::class, 'addTransaction'])->name('doctor-payout-appointments-add');
    Route::post('doctor/payout/add/deduction', [DoctorEarningController::class, 'addDeduction'])->name('doctor-payout-appointments-add_deduction');
});