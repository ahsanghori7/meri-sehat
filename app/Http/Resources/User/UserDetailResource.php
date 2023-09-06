<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Disease\DiseaseResource;
use App\Http\Resources\Page\PageResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Common\Constant;
use App\Http\Resources\Article\ArticleSummeryResource;
use App\Models\{Degree, Faq, Language, Review, Service, Speciality, Settings, Transaction, DoctorEducation};
use Carbon\Carbon;
use App\Http\Common\Helper as CustomHelper;
class UserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $instant_consultation_discount_percent = Settings::where('key', 'instant_consultation_discount_percent')->get()->pluck('value');
        $instant_consultation_fees = Settings::where('key', 'instant_consultation_fees')->get()->pluck('value');
        $instant_consultation_discounted_fees = Settings::where('key', 'instant_consultation_discounted_fees')->pluck('value');
        $image = $this->image? env('ASSETS_STORAGE').$this->image:env('ASSETS_STORAGE')."assets/img/default.png";

        if ($this->role_id == (new Constant)->DOCTOR_ROLE_ID) {
            $image = $this->image ? env('ASSETS_STORAGE').$this->image : null;
        }
        $getReviews = Review::getApprovedReviews($this->id);
        $trial_consultation = 0;
        if($this->trial_consultation > 0){
            $difference = Carbon::now()->diffInDays(Carbon::parse($this->created_at));
            if($difference < 30){
                $trial_consultation = 1;
            }
        }
        if($image){
            $image = $image;
        }else{
            $image = asset('assets/img/default-user.png');
        }
        $transactionCount = Transaction::where([
            ['reference_type', 'one_time'],
            ['user_id', $this->id],
            ['status', 1],
            ['is_avail', 0],
        ])->count();
        $transactionCancelCount = Transaction::where([
            ['reference_type', 'one_time'],
            ['user_id', $this->id],
            ['status', 1],
            ['is_avail', 0],
            ['is_cancel', 1]
        ])->count();
        $data = [

            'id' => $this->id,
            'trial_consultation' => $trial_consultation,
            'name' => $this->name,
            'role' => ($this->role)->name,
            'city_id' => $this->city_id ?? null,
            'city' => $this->city ? ($this->city)->name : null,
            'phone' => $request->has('user') ? CustomHelper::phoneMasking($this->phone) : $this->phone,
            'email' => $request->has('user') ? CustomHelper::emailMasking($this->email) : $this->email,
            'image' => $image ?? asset('/images') . '/default-pic.png',
            'gender' => $this->gender ?? null,
            'height' => $this->getHeight($this->height),
            'weight' => $this->getWeight($this->weight),
            'network' => $this->network ?? null,
            'birth_date' => $this->birth_date ?? null,
            'age' => $this->birth_date ? \Carbon\Carbon::parse($this->birth_date)->age : null,
            'is_subscribed' => ($this->is_subscribed && $this->subscription || $this->subscription) ? true : false,
            'status' => $this->status,
            'language' => $this->language ?? null,
            'subscription_history' => $this->subscription_history($this->id),
            'subscription' => $this->subscription ?? null,
            'is_subscription_expired' => ($this->subscription == null && count($this->subscription_history($this->id)) > 0) ? true : false,
            'is_sign_up' => ($this->birth_date == null || $this->height == null || $this->weight == null) ? true : false,
            'member_since' => $this->member_since,
            'instant_consultation_fees'=> (isset($instant_consultation_fees[0]))?$instant_consultation_fees[0]:env('INSTANT_CONSULTATION_FEES'),
            'instant_consultation_discounted_fees'=> (isset($instant_consultation_discounted_fees[0]))?$instant_consultation_discounted_fees[0]:env('INSTANT_CONSULTATION_FEES'),
            'instant_consultation_discount_percent'=> (isset($instant_consultation_discount_percent[0]))?$instant_consultation_discount_percent[0]:env('instant_consultation_discount_percent'),
            'transaction_count' => $transactionCount,
            'transaction_cancel_count' => $transactionCancelCount,
            'created_at' => $this->created_at,
            'is_journey' => $this->is_journey,
        ];
        $articles_speciality = null;
        if($this->role_id == (new Constant)->DOCTOR_ROLE_ID){
            if($request->header('locale') != Language::ENGLISH){
                $doctorServices = Service::where('translation_of', $this->doctorServiceDetailsApproved->pluck('id'))->get();
                $doctorSpecialities = Speciality::where('translation_of', $this->doctorSpecialityDetails->pluck('id'))->get();
            }
            $language = $request->header('locale');
            $articles = ArticleSummeryResource::collection(collect($this->articles->filter(function($item) use($language){
                return $item->lang_id == $language;
            })->take(3)->all()));
            if ($this->doctorSpecialityArticles) {
                $articles_speciality = ArticleSummeryResource::collection(collect($this->doctorSpecialityArticles->filter(function($item) use($language){
                    return $item->lang_id == $language;
                })->all()));
             //  $articles = $articles->merge($articles_speciality);
            }

        } if($this->role_id == (new Constant)->FITNESS_EXPERTS_ROLE_ID) {
            $articles = $this->doctorSpecialityArticles ? ArticleSummeryResource::collection(collect($this->doctorSpecialityArticlesLatest4)) : null;
        }
        if($this->role_id == (new Constant)->DOCTOR_ROLE_ID){
            $doctorDetails = $this->doctorDetail;
            if(isset($doctorDetails->consultation_duration)){
                if(str_contains($doctorDetails->consultation_duration, ":")){
                    $consultationDurationTime = \Carbon\Carbon::parse($doctorDetails->consultation_duration);
                    $consultationDurationTime = ($consultationDurationTime->hour * 60) + $consultationDurationTime->minute . " minutes";
                }else{
                    $consultationDurationTime = $doctorDetails->consultation_duration . " minutes";
                }
            }
            $redirect_url = '#';
            if(isset($this->city_id) && $this->role_id == 3 ){
                if(isset($this->doctorSpecialityDetails[0])){
                    $redirect_url = '/doctor/' . \Str::slug(($this->city)->name) . '/' . \Str::slug($this->doctorSpecialityDetails->pluck('name')[0]) . '/' . \Str::slug($this->doctorDetail->prefix) . '-' . \Str::slug($this->name) . '/' . $this->id;
                }
            }
            $data = array_merge($data, [
                //  'prefix' => $doctorDetails->prefix,
                'redirect_url' => $redirect_url,
                'is_available' => $doctorDetails->is_available,


                'prefix' => $doctorDetails->prefix,
                'is_instant_consultation' => (bool) $doctorDetails->is_instant_consultation,
                'is_physical_consultancy' => $doctorDetails->is_physical_consultancy,
                'consultation_duration' => isset($consultationDurationTime) ? $consultationDurationTime : null,
                'waiting_time' => $doctorDetails->waiting_time,
                'is_featured' => (bool) $doctorDetails->is_featured,
                'is_verified' => (bool) $doctorDetails->is_verified,
                'about' => $doctorDetails->about,
                'badge' => $doctorDetails->badge,
                'pmc_no' => $doctorDetails->pmc_no,
                'experience_year' => $doctorDetails->experience_year,
                'collaborations' => json_decode($doctorDetails->collaborations),
                'articles' => $articles,
                //'diseases_by_specialities_latest' => $this->doctorSpecialityDiseases ? PageResource::collection(collect($this->doctorSpecialityDiseasesLatest4)) : null,
                //'diseases_by_specialities' => $this->doctorSpecialityDiseases ? PageResource::collection(collect($this->doctorSpecialityDiseases)) : null,
                //'articles_by_specialities_latest' => ,
                'articles_by_specialities' => $articles_speciality,
                'doctor_faqs' => $this->dynamicFaq($doctorDetails->prefix . '. ' . $this->name, $language),
                'average_rating' => isset($getReviews['average_rating']) ? $getReviews['average_rating'] : 0,
                'total_reviews' => isset($getReviews['reviews']) ? $getReviews['total_rating'] : 0,
                'review_count' => isset($getReviews['reviews']) ? count($getReviews['reviews']) : 0,
                'waiting_time_rating' => isset($getReviews['reviews']) ? ($getReviews['waiting_time']) : 0,
                'cleanliness_rating' => isset($getReviews['reviews']) ? ($getReviews['cleanliness']) : 0,
                'bedside_manners_rating' => isset($getReviews['reviews']) ? ($getReviews['bedside_manners']) : 0,
                'staff_friendliness_rating' => isset($getReviews['reviews']) ? ($getReviews['staff_friendliness']) : 0,
                'reviews' => isset($getReviews['reviews']) ? ($getReviews['reviews']) : null,
                'doctor_services' => count($this->doctorServiceDetailsApproved) ? ($request->header('locale') == Language::ENGLISH ? $this->doctorServiceDetailsApproved->pluck('name') : (count($doctorServices) ? $doctorServices->pluck('name') : null)) : null,
                'doctor_services_full' => count($this->doctorServiceDetails) ? ($request->header('locale') == Language::ENGLISH ? $this->doctorServiceDetails : (count($doctorServices) ? $doctorServices->pluck('name') : null)) : null,
                'doctor_specialities' => count($this->doctorSpecialityDetails) ? ($request->header('locale') == Language::ENGLISH ? $this->doctorSpecialityDetails->pluck('name') : (count($doctorSpecialities) ? $doctorSpecialities->pluck('name') : null)) : null,
                'doctor_specialities_full' => count($this->doctorSpecialityDetails) ? ($request->header('locale') == Language::ENGLISH ? $this->doctorSpecialityDetails : (count($doctorSpecialities) ? $doctorSpecialities->pluck('name') : null)) : null,
                'doctor_educations' => count($this->doctorEducation) ? $this->doctorEducation->pluck('degree')->all() : null,
                'doctor_clinics' => count($this->doctorClinics) ? DoctorClinicResource::collection($this->doctorClinics) : null,
                'doctor_educations_with_institute' => $this->doctor_educations_with_institute($this->id) ?? null,
            ]);
        }
        return $data;
    }

    public function doctor_educations_with_institute($doctorId){
        $data = [];
        $educations = DoctorEducation::where('doctor_id', $doctorId)->get();
        foreach($educations as $key => $edu){
            $data[$key] = $edu['degree'].' from '.$edu['institute'];
        }
        return $data;
    }

    public function dynamicFaq($doctorName, $language)
    {
        ($language == Language::ENGLISH)
        ?   $getFaqs = Faq::getFaqs(7)
        :   $getFaqs = Faq::getFaqs(11);
        return $getFaqs->each(function ($item) use($doctorName){
            if($this->doctorDetail){
                $item['question'] = str_replace('{{doctor_name}}', $doctorName, $item['question']);
                $item['answer'] = str_replace('{{experience}}', $this->doctorDetail->experience_year, $item['answer']);
            }
            if(count($this->doctorEducation)){
                $item['question'] = str_replace('{{doctor_name}}', $doctorName, $item['question']);
                $item['answer'] = str_replace('{{doctor_name}}', $doctorName, $item['answer']);
                $item['answer'] = str_replace('{{doctor_education}}', implode(', ', $this->doctorEducation->pluck('degree')->all()), $item['answer']);
            }
            if($this->doctorDetail){
                $item['question'] = str_replace('{{doctor_name}}', $doctorName, $item['question']);
                $item['answer'] = str_replace('{{doctor_name}}', $doctorName, $item['answer']);
                $item['answer'] = str_replace('{{experience}}', $this->doctorDetail->experience_year, $item['answer']);
            }
            if(count($this->doctorClinics)){
                if($this->doctorLowPaidClinic->consultation_fee == $this->doctorHighPaidClinic->consultation_fee){
                    $item['question'] = str_replace('{{doctor_name}}', $doctorName, $item['question']);
                    $item['answer'] = str_replace('ranges from Rs. {{minimun_fees}} to Rs. {{maximum_fees}}', $this->doctorLowPaidClinic->consultation_fee, $item['answer']);
                }else{
                    $item['question'] = str_replace('{{doctor_name}}', $doctorName, $item['question']);
                    $item['answer'] = str_replace('{{minimun_fees}}', $this->doctorLowPaidClinic->consultation_fee, $item['answer']);
                    $item['answer'] = str_replace('{{maximum_fees}}', $this->doctorHighPaidClinic->consultation_fee, $item['answer']);
                }
            }
            if($this->doctorDetail && count($this->doctorEducation) && $this->doctorDetail && count($this->doctorClinics)){
                $item['question'] = str_replace('{{doctor_name}}', $doctorName, $item['question']);
                $item['answer'] = str_replace('{{doctor_name}}', $doctorName, $item['answer']);
            }
        });

    }
}
