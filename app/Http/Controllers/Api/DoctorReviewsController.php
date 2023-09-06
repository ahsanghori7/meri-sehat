<?php

namespace App\Http\Controllers\Api;

use App\Http\Common\Constant;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserSummeryResource;
use Illuminate\Http\Request;
use App\Models\{User, Doctor, DoctorReview, Appointment, Review};
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class DoctorReviewsController extends Controller
{
    public function createReview(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'doctor_id' => ['required', 'exists:users,id'],
                'rating' => ['required'],
                'description' => ['required'],
                'consultation_rating' => ['required', 'string']
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $input['user_id'] = $this->getUserIdFromHeader($request->header());
            $getReview = DoctorReview::create($input);
            if(!$getReview){
                return $this->returnResponse(400, 'Unable to review the doctor.');
            }
            return $this->returnResponse(200, 'Reviewed the doctor successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
   
    public function getReview(Request $request, User $doctor)
    {
        try {
            if(Request()->segment(2) === "v1"){
                $getReviews = DoctorReview::with(['user', 'doctor'])->where('doctor_id', $doctor->id)->orderBy('id', 'desc')->paginate(10);
                
                if(!$getReviews){
                    return $this->returnResponse(400, 'No one review this doctor.');
                }
                
                return $this->returnResponse(200, '', $getReviews);
            }else{
                $data = [];
                $doctorid=$doctor->id;
                $getReviews = Review::whereHas('appointment', function($query) use($doctorid) {
                $query->where('doctor_id', $doctorid);
                })->where('status','approved')->orderBy('id', 'desc')->paginate(5)->withQueryString();
                if(!count($getReviews)){
                return [];
                }
                $data['reviews'] = $getReviews;
                if(!$getReviews){
                    return $this->returnResponse(400, 'No one review this doctor.');
                }
                return $this->returnResponse(200, '', $data);
            }
            
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function checkDoctorAppointment(Request $request, User $doctor)
    {
        try {
            $user_id = $this->getUserIdFromHeader($request->header());
            $getAppointment = Appointment::where('doctor_id', $doctor->id)->where('user_id', $user_id)->first();
            if(!$getAppointment){
                return $this->returnResponse(400, 'No appointment with this doctor.');
            }
            $checkReview = DoctorReview::where([
                ['user_id', $user_id],
                ['doctor_id', $doctor->id],
            ])->first();
            if($checkReview){
                return $this->returnResponse(409, 'Already submitted.');
            }
            return $this->returnResponse(200, '', $getAppointment);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
}
