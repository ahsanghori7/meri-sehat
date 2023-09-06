<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Common\Constant;
use App\Http\Resources\Article\ArticleSummeryResource;
use App\Models\Review;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $getReviews = Review::getReviews($this->id);
        $image = $this->image ? (str_contains($this->image, 'user/') ? env('ASSETS_STORAGE').$this->image : url(Constant::FILE_UPLOAD_PATH . "$this->id/$this->image")) : null;
        return [
            'id' => $this->id,
            'city_id' => $this->city_id ?? null,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email ?? "",
            'unique_code' => $this->unique_code,
//            'otp' => $this->otp,
            'image' => $image,
            'gender' => $this->gender ?? "",
            'height' => $this->height ?? null,
            'weight' => $this->weight ?? "",
            'network' => $this->network ?? "",
            'birth_date' => $this->birth_date ?? "",
            'age' => $this->birth_date ? \Carbon\Carbon::parse($this->birth_date)->age : "",
            'is_subscribed' => ($this->is_subscribed && $this->subscription || $this->subscription) ? true : false,
            'status' => $this->status,
            'language' => $this->language ?? "",
            'is_sign_up' => ($this->birth_date == null || $this->height == null || $this->weight == null) ? true : false,
//            'redirect_url' => isset($this->city_id) && $this->role_id == 3 ? '/doctor/' . \Str::slug(($this->city)->name) . '/' . \Str::slug($this->doctorSpecialityDetails->pluck('name')[0]) . '/' . \Str::slug($this->doctorDetail->prefix) . '-' . \Str::slug($this->name) . '/' . $this->id : '#',

            'role' => $this->role,
            'city' => $this->city,
            'parent' => $this->parent,
            'subscription' => $this->subscription ?? null,
            'doctor_detail' => $this->doctorDetail,
            'doctor_services' => $this->doctorServiceDetail,

            'articles' => ArticleSummeryResource::collection($this->articles),

            'doctor_specialities' => count($this->doctorSpecialityDetails) ? $this->doctorSpecialityDetails : null,
            'doctor_educations' => count($this->doctorEducation) ? $this->doctorEducation : null,
            'doctor_clinics' => count($this->doctorClinics) ? DoctorClinicResource::collection($this->doctorClinics) : null,

            'total_reviews' => isset($getReviews['reviews']) ? $getReviews['total_rating'] : 0,
            'review_count' => isset($getReviews['reviews']) ? count($getReviews['reviews']) : 0,

            'waiting_time_rating' => isset($getReviews['reviews']) ? ($getReviews['waiting_time']) : 0,
            'cleanliness_rating' => isset($getReviews['reviews']) ? ($getReviews['cleanliness']) : 0,
            'bedside_manners_rating' => isset($getReviews['reviews']) ? ($getReviews['bedside_manners']) : 0,
            'staff_friendliness_rating' => isset($getReviews['reviews']) ? ($getReviews['staff_friendliness']) : 0,

            'reviews' => isset($getReviews['reviews']) ? ($getReviews['reviews']) : 0,
        ];

    }
}
