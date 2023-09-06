<?php

use App\Http\Controllers\Admin\Widget\{ArticleHelpfulController,
    BannerController,
    CallByReferenceCardController,
    CallToActionController,
    MediaCustomController,
    TopicsController,
    HeadingAndDescriptionController,
    CardController,
    ReferenceController,
    TopDoctorController,
    TopArticleController,
    TagSpecialityController,
    TopicPillsController,
    ReferencesController,
    DiseaseController,
    VitalHealthScanController,
    SearchController,
    SehatController,
    FaqCategoryController,
    InFeedArticleController,
    MediaController,
    MostSearchSpeciality,
    SearchDoctorController,
    SubcsriptionController,
    AboutFeatureController};
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/widget/')->name('widget-')->group(function () {

    Route::prefix('banner')->controller(BannerController::class)->name('banner-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('card')->controller(CardController::class)->name('card-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('card-with-slider')->controller(CardController::class)->name('card-with-slider-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('article-custom')->controller(TopArticleController::class)->name('article-custom-')->group(function () {
        include('general_widget_routes.php');
        Route::post('edit-sequence', [TopArticleController::class, 'editSequence'])->name('edit-sequence');
        Route::get('delete-record/{id}', [TopArticleController::class, 'deleteRecord'])->name('delete-record');
    });

    Route::prefix('article')->controller(TopArticleController::class)->name('article-')->group(function () {
        include('general_widget_routes.php');
        Route::post('edit-sequence', [TopArticleController::class, 'editSequence'])->name('edit-sequence');
        Route::get('delete-record/{id}', [TopArticleController::class, 'deleteRecord'])->name('delete-record');
    });

    Route::prefix('doctor')->controller(TopDoctorController::class)->name('doctor-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('speciality')->controller(TagSpecialityController::class)->name('speciality-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('topic-pills')->controller(TopicPillsController::class)->name('topic-pills-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('references')->controller(ReferencesController::class)->name('references-')->group(function () {
        include('general_widget_routes.php');
        Route::get('delete/{reference_id}/{id}', 'delete')->name('delete');
    });

    Route::prefix('references-custom')->controller(ReferencesController::class)->name('references-custom-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('disease')->controller(DiseaseController::class)->name('disease-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('vital-health-scan')->controller(VitalHealthScanController::class)->name('vital-health-scan-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('heading-and-description')->controller(HeadingAndDescriptionController::class)->name('heading-and-description-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('call-to-action')->controller(CallToActionController::class)->name('call-to-action-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('call-by-reference-card')->controller(CallByReferenceCardController::class)->name('call-by-reference-card-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('call-by-reference-card-custom')->controller(\App\Http\Controllers\Admin\Widget\CallByReferenceCardCustomController::class)->name('call-by-reference-card-custom-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('search')->controller(SearchController::class)->name('search-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('sehat-a-z')->controller(SehatController::class)->name('sehat-a-z-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('faq-category')->controller(FaqCategoryController::class)->name('faq-category-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('most-searched-specialties')->controller(MostSearchSpeciality::class)->name('most-searched-specialties-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('media')->controller(MediaController::class)->name('media-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('media-custom')->controller(MediaCustomController::class)->name('media-custom-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('subscription')->controller(SubcsriptionController::class)->name('subscription-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('article-helpful')->controller(ArticleHelpfulController::class)->name('article-helpful-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('about-feature')->controller(AboutFeatureController::class)->name('about-feature-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('in-feed-article')->controller(InFeedArticleController::class)->name('in-feed-article-')->group(function () {
        include('general_widget_routes.php');
    });

    Route::prefix('search-doctor')->controller(SearchDoctorController::class)->name('search-doctor-')->group(function () {
        include('general_widget_routes.php');
    });


});
