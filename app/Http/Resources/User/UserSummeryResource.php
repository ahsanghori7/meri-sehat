<?php

namespace App\Http\Resources\User;

use App\Http\Common\Constant;
use App\Models\{Degree, Language, Service, Speciality, Review, SharedMedicalReport};
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserSummeryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(Request()->segment(2) === "v2"){
            $user = \Auth::user() ?? \Auth::guard("api")->user();
            $userId = ($user == null) ? $request->user_id : $user->id;
        }else{
            $userId = $request->user_id ? $request->user_id : $request->header('user-id');
        }

        $getReviews = Review::getApprovedReviews($this->id);
        if ($this->role_id == (new Constant)->DOCTOR_ROLE_ID) {
            $image = $this->image ? url(env('ASSETS_STORAGE'). "$this->image") : null;
            if ($this->image != '') {
                $image_arr = explode('/',$this->image);
                if ($image_arr > 0) {
                    $file_name = $image_arr[0].'/'.preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($image_arr[1])).'_transparent.png';
                    $file_name_new = $image_arr[0].'/'.preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($image_arr[1])).'_app_new.png';

                } else {
                    $file_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($image_arr[0])).'_transparent.png';
                    $file_name_new = preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($image_arr[0])).'_app_new.png';
                }
            }
            $image_transparent = $this->image ? url(env('ASSETS_STORAGE').$file_name) : null;
            $image_transparent_new = $this->image ? url(env('ASSETS_STORAGE').$file_name_new) : null;

        } else {
            $image = $this->image ? (str_contains($this->image, 'user/') || str_contains($this->image, 'fitness-experts/') ? env('ASSETS_STORAGE'). $this->image : url(Constant::FILE_UPLOAD_PATH . "$this->id/$this->image")) : null;
            $image_transparent = $this->image ? (str_contains($this->image, 'user/') || str_contains($this->image, 'fitness-experts/') ? env('ASSETS_STORAGE'). $this->image : url(Constant::FILE_UPLOAD_PATH . "$this->id/$this->image")) : null;
            $image_transparent_new = $this->image ? (str_contains($this->image, 'user/') || str_contains($this->image, 'fitness-experts/') ? env('ASSETS_STORAGE'). $this->image : url(Constant::FILE_UPLOAD_PATH . "$this->id/$this->image")) : null;

        }
        if (Request()->segment(4) == 'home-new') {
            if ($request->header('platform') == "app") {
                $image_transparent = $image_transparent;
                $image_transparent_new=$image_transparent_new;

            } else {
                $image_transparent = null;
                $image_transparent_new =null;
            }
        } else {
            $image_transparent = null;
            $image_transparent_new =null;
        }
        $data = [
            'id' => $this->id,
            'role' => $this->role_id ? $this->role->name : null,
            'name' => $this->name,
            'city_id' => $this->city_id ?? null,
            'city' => isset($this->city_id) ? ($this->city)->name : null,
            'image' => $image,
            'image_transparent' => $image_transparent,
            'new_image' => $image_transparent_new,
            'age' => $this->birth_date ? \Carbon\Carbon::parse($this->birth_date)->age : null,
            'gender' => $this->gender ?? null,
            'is_subscribed' => ($this->is_subscribed && $this->subscription || $this->subscription) ? true : false,
            'subscription' => $this->subscription ?? null,
            'is_social_login' => $this->socialAccounts->count() > 0 ? true : false,
            'is_sign_up' => ($this->birth_date == null || $this->height == null || $this->weight == null) ? true : false,
        ];
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
            if($request->header('locale') != Language::ENGLISH){
                $doctorServices = Service::where('translation_of', $this->doctorServiceDetails->pluck('id'))->get();
                $doctorSpecialities = Speciality::where('translation_of', $this->doctorSpecialityDetails->pluck('id'))->get();
            }
            $is_shared = false;
            $SharedMedicalReportId = 0;
            if($request->has('is_appointment') && $request->has('medical_record_id')){
                $SharedMedicalReport = SharedMedicalReport::where('user_id', $userId)->where('doctor_id', $this->id)->where('medical_record_id', $request->medical_record_id)->first();
                if($SharedMedicalReport){
                    $is_shared = $SharedMedicalReport ? true : false;
                    $SharedMedicalReportId = $SharedMedicalReport->id;
                }
            }
            $prefix = $doctorDetails ? $doctorDetails->prefix : null;
            $redirect_url = isset($this->city_id) && count($this->doctorSpecialityDetails) > 0 && $this->role_id == 3 ? '/doctor/' . \Str::slug(($this->city)->name) . '/' . \Str::slug($this->doctorSpecialityDetails->pluck('name')[0]) . '/' . \Str::slug($this->doctorDetail->prefix) . '-' . \Str::slug($this->name) . '/' . $this->id : '#';
            if($doctorDetails->is_staff){
                $prefix = null;
                $redirect_url = null;
            }
            $data = array_merge($data, [
                'prefix' => $prefix,
                'is_instant_consultation' => (bool) $doctorDetails->is_instant_consultation,
                'is_physical_consultancy' => $doctorDetails->is_physical_consultancy,
                'waiting_time' => $doctorDetails->waiting_time,
                'consultation_duration' => isset($consultationDurationTime) ? $consultationDurationTime : null,
                'about' => $doctorDetails->about,
                'badge' => $doctorDetails->badge,
                'is_featured' => (bool) $doctorDetails->is_featured,
                'is_verified' => (bool) $doctorDetails->is_verified,
                'pmc_no' => $doctorDetails->pmc_no,
                'experience_year' => $doctorDetails->experience_year,
                'collaborations' => json_decode($doctorDetails->collaborations),
                'is_available' => $doctorDetails->is_available,
                // 'redirect_url' => $this->doctorSpecialityDetails,
                'redirect_url' => $redirect_url,
                'doctor_services' => count($this->doctorServiceDetails) ? ($request->header('locale') == Language::ENGLISH ? $this->doctorServiceDetails->pluck('name') : (count($doctorServices) ? $doctorServices->pluck('name') : null)) : null,
                'doctor_specialities' => count($this->doctorSpecialityDetails) ? ($request->header('locale') == Language::ENGLISH ? $this->doctorSpecialityDetails->pluck('name') : (count($doctorSpecialities) ? $doctorSpecialities->pluck('name') : null)) : null,
                'doctor_educations' => count($this->doctorEducation) ? $this->doctorEducation->pluck('degree')->all() : null,
                'average_rating' => isset($getReviews['average_rating']) ? $getReviews['average_rating'] : 0,
                'total_reviews' => isset($getReviews['reviews']) ? $getReviews['total_rating'] : 0,
                'review_count' => isset($getReviews['reviews']) ? count($getReviews['reviews']) : 0,
                'doctor_clinics' => count($this->doctorClinics) ? DoctorClinicResource::collection($this->doctorClinics->slice(0, 3)) : null,
                'is_shared' => (isset($is_shared) && $is_shared) ? true : false,
                'shared_medical_report_id' => (int) $SharedMedicalReportId
            ]);
        }
        return $data;
    }
}
