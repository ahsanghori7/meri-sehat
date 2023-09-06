<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Common\Constant;
use App\Http\Resources\Article\ArticleSummeryResource;
use App\Models\{Review, Transaction};

class UserResource extends JsonResource
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
        $image = $this->image? env('ASSETS_STORAGE').$this->image:env('ASSETS_STORAGE')."assets/img/default.png";
        $trial_consultation = 0;
        if($this->trial_consultation > 0){
            $difference = Carbon::now()->diffInDays(Carbon::parse($this->created_at));
            if($difference < 30){
                $trial_consultation = 1;
            }
        }
        $transactionCount = Transaction::where([
            ['reference_type', 'one_time'],
            ['user_id', $this->id],
            ['status', 1],
            ['is_avail', 0],
        ])->count();
        $redirect_url = '#';
        if(isset($this->city_id) && $this->role_id == 3 ){
            if(isset($this->doctorSpecialityDetails[0])){
                $redirect_url = '/doctor/' . \Str::slug(($this->city)->name) . '/' . \Str::slug($this->doctorSpecialityDetails->pluck('name')[0]) . '/' . \Str::slug($this->doctorDetail->prefix) . '-' . \Str::slug($this->name) . '/' . $this->id;
            }
        }
        $data =  [
            'id' => $this->id,
            'trial_consultation' => $trial_consultation,
            'city_id' => $this->city_id ?? null,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email ?? "",
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
            'redirect_url' => $redirect_url,
            'role' => $this->role,
            'city' => $this->city,
            'parent' => $this->parent,
            'subscription' => $this->subscription ?? null,
            'doctor_detail' => $this->doctorDetail,
            'doctor_services' => $this->doctorServiceDetail,
            'is_social_login' => $this->socialAccounts->count() > 0 ? true : false,
            'articles' => ArticleSummeryResource::collection($this->articles),
            'doctor_specialities' => count($this->doctorSpecialityDetails) ? $this->doctorSpecialityDetails : null,
            'doctor_educations' => count($this->doctorEducation) ? $this->doctorEducation : null,
            //'doctor_clinics' => count($this->doctorClinics) ? DoctorClinicResource::collection($this->doctorClinics) : null,
            'total_reviews' => isset($getReviews['reviews']) ? $getReviews['total_rating'] : 0,
            'review_count' => isset($getReviews['reviews']) ? count($getReviews['reviews']) : 0,
            'waiting_time_rating' => isset($getReviews['reviews']) ? ($getReviews['waiting_time']) : 0,
            'cleanliness_rating' => isset($getReviews['reviews']) ? ($getReviews['cleanliness']) : 0,
            'bedside_manners_rating' => isset($getReviews['reviews']) ? ($getReviews['bedside_manners']) : 0,
            'staff_friendliness_rating' => isset($getReviews['reviews']) ? ($getReviews['staff_friendliness']) : 0,
            'reviews' => isset($getReviews['reviews']) ? ($getReviews['reviews']) : 0,
            'transaction_count' => $transactionCount,
            'is_journey' => $this->is_journey
        ];

        $tokenData = [];
        if($request->route()->uri && Request()->segment(2) === "v2"){
            $getEndPoint = explode('/', $request->route()->uri);
            if(end($getEndPoint) == 'verify-otp' || end($getEndPoint) == 'login-via-phone-otp'){
                \DB::table('oauth_access_tokens')->where('user_id', $this->id)->delete();
                $tokenData = $this->createTokenUser($this);
                $tokenData = [
                    'access_token' => $tokenData->Authorization,
                    'expires_at' => $tokenData->expires_at
                ];
            }
        }
        $data = array_merge($data, $tokenData);
        return $data;

    }

    public function createTokenUser($user)
    {
        \DB::table('oauth_access_tokens')->where('user_id', $user->id)->update(['revoked'=> 1]);
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();
        $user->access_token = $tokenResult->accessToken;
        $user->token_type = 'Bearer';
        $user->Authorization = 'Bearer '. $tokenResult->accessToken;
        $user->expires_at = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();
        return $user;
    }
}
