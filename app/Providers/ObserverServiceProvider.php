<?php

namespace App\Providers;

use App\Models\Ads;
use App\Models\AdsWindow;
use App\Models\Appointment;
use App\Models\AppointmentCondition;
use App\Models\Article;
use App\Models\ArticleBadge;
use App\Models\ArticleFact;
use App\Models\ArticleLabel;
use App\Models\ArticleTags;
use App\Models\City;
use App\Models\Clinic;
use App\Models\ClinicTiming;
use App\Models\Condition;
use App\Models\ContentFeedback;
use App\Models\Degree;
use App\Models\Disease;
use App\Models\DiseaseSyptom;
use App\Models\DoctorBankDetail;
use App\Models\DoctorClinic;
use App\Models\DoctorDetail;
use App\Models\DoctorEducation;
use App\Models\DoctorExperience;
use App\Models\DoctorReview;
use App\Models\DoctorService;
use App\Models\DoctorSpeciality;
use App\Models\Drug;
use App\Models\Expert;
use App\Models\ExpertFollow;
use App\Models\ExpertSpeciality;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\FeatureDoctor;
use App\Models\Feedbacks;
use App\Models\FindADoctor;
use App\Models\FitnessDetail;
use App\Models\FitnessEducation;
use App\Models\FitnessExperience;
use App\Models\FitnessService;
use App\Models\FitnessSpeciality;
use App\Models\Footer;
use App\Models\GetALink;
use App\Models\HeadingAndDescription;
use App\Models\HealthScan;
use App\Models\HealthScanSuggestion;
use App\Models\HelperText;
use App\Models\InfoModal;
use App\Models\InstantMedicalRecord;
use App\Models\InstantMedicalRecordFile;
use App\Models\Language;
use App\Models\MediaLanguage;
use App\Models\MedicalRecord;
use App\Models\MedicalRecordFile;
use App\Models\Menu;
use App\Models\MostSearchSpeciality;
use App\Models\Newsletter;
use App\Models\Page;
use App\Models\PageTag;
use App\Models\PageWidget;
use App\Models\PatientInfo;
use App\Models\Permission;
use App\Models\PreAppointment;
use App\Models\PrescribedElement;
use App\Models\Prescription;
use App\Models\PrescriptionElement;
use App\Models\PrescriptionElementType;
use App\Models\PrescriptionMedicineType;
use App\Models\PrescriptionMedicineUnit;
use App\Models\PromoCode;
use App\Models\ReferenceWidget;
use App\Models\ResourceReference;
use App\Models\Review;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Sales;
use App\Models\SalesVisit;
use App\Models\Service;
use App\Models\Settings;
use App\Models\SharedMedicalReport;
use App\Models\Shift;
use App\Models\SiteContent;
use App\Models\Speciality;
use App\Models\Subscription;
use App\Models\SubTopic;
use App\Models\Symptom;
use App\Models\Tags;
use App\Models\Topics;
use App\Models\Transaction;
use App\Models\University;
use App\Models\User;
use App\Models\UserArticleReview;
use App\Models\UserFamilyMember;
use App\Models\UserPromoCode;
use App\Models\UserSocialAccount;
use App\Models\UserSubscription;
use App\Models\VisitType;
use App\Models\Widget;
use App\Models\WidgetArticle;
use App\Models\WidgetBanner;
use App\Models\WidgetCallByReferenceCard;
use App\Models\WidgetCallToAction;
use App\Models\WidgetCard;
use App\Models\WidgetDisease;
use App\Models\WidgetDoctor;
use App\Models\WidgetFaq;
use App\Models\WidgetImage;
use App\Models\WidgetInFeedArticle;
use App\Models\WidgetMedia;
use App\Models\WidgetMostSearchSpeciality;
use App\Models\WidgetReference;
use App\Models\WidgetSearch;
use App\Models\WidgetSpeciality;
use App\Models\WidgetText;
use App\Models\WidgetTopicPill;
use App\Models\WidgetVitalHealthScan;
use App\Observers\GlobalObserver;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use PhpCsFixer\DocBlock\Tag;

class ObserverServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        self::registerGlobalObserver();
    }

    private static function registerGlobalObserver()
    {
        /** @var \Illuminate\Database\Eloquent\Model[] $MODELS */
        $MODELS = [
            Ads::class,
            AdsWindow::class,
            Appointment::class,
            AppointmentCondition::class,
            Article::class,
            ArticleBadge::class,
            ArticleFact::class,
            ArticleLabel::class,
            ArticleTags::class,
            City::class,
            Clinic::class,
            ClinicTiming::class,
            Condition::class,
            ContentFeedback::class,
            Degree::class,
            Disease::class,
            DiseaseSyptom::class,
            DoctorBankDetail::class,
            DoctorClinic::class,
            DoctorDetail::class,
            DoctorEducation::class,
            DoctorExperience::class,
            DoctorReview::class,
            DoctorService::class,
            DoctorSpeciality::class,
            Drug::class,
            Expert::class,
            ExpertFollow::class,
            ExpertSpeciality::class,
            Faq::class,
            FaqCategory::class,
            FeatureDoctor::class,
            Feedbacks::class,
            FindADoctor::class,
            FitnessDetail::class,
            FitnessEducation::class,
            FitnessExperience::class,
            FitnessService::class,
            FitnessSpeciality::class,
            Footer::class,
            GetALink::class,
            HeadingAndDescription::class,
            HealthScan::class,
            HealthScanSuggestion::class,
            HelperText::class,
            InfoModal::class,
            InstantMedicalRecord::class,
            InstantMedicalRecordFile::class,
            Language::class,
            MediaLanguage::class,
            MedicalRecord::class,
            MedicalRecordFile::class,
            Menu::class,
            MostSearchSpeciality::class,
            Newsletter::class,
            Page::class,
            PageTag::class,
            PageWidget::class,
            PatientInfo::class,
            Permission::class,
            PreAppointment::class,
            PrescribedElement::class,
            Prescription::class,
            PrescriptionElement::class,
            PrescriptionElementType::class,
            PrescriptionMedicineType::class,
            PrescriptionMedicineUnit::class,
            PromoCode::class,
            ReferenceWidget::class,
            ResourceReference::class,
            Review::class,
            Role::class,
            RolePermission::class,
            Sales::class,
            SalesVisit::class,
            Service::class,
            Settings::class,
            SharedMedicalReport::class,
            Shift::class,
            SiteContent::class,
            Speciality::class,
            Subscription::class,
            SubTopic::class,
            Symptom::class,
            Tags::class,
            Topics::class,
            Transaction::class,
            University::class,
            User::class,
            UserArticleReview::class,
            UserFamilyMember::class,
            UserPromoCode::class,
            UserSocialAccount::class,
            UserSubscription::class,
            VisitType::class,
            Widget::class,
            WidgetArticle::class,
            WidgetBanner::class,
            WidgetCallByReferenceCard::class,
            WidgetCallToAction::class,
            WidgetCard::class,
            WidgetDisease::class,
            WidgetDoctor::class,
            WidgetFaq::class,
            WidgetImage::class,
            WidgetInFeedArticle::class,
            WidgetMedia::class,
            WidgetMostSearchSpeciality::class,
            WidgetReference::class,
            WidgetSearch::class,
            WidgetSpeciality::class,
            WidgetText::class,
            WidgetTopicPill::class,
            WidgetVitalHealthScan::class,
            // ...... more models here
        ];

        foreach ($MODELS as $MODEL) {
            $MODEL::observe(GlobalObserver::class);
        }
    }
}
