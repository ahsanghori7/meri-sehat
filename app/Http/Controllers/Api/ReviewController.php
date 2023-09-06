<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Review, User};
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * This method is used to Add new Family Members
     */
    public function createReview(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'appointment_id' => ['required', 'exists:appointments,id','unique:reviews,appointment_id'],
                'description' => ['sometimes'],
                'rating' => ['required', 'numeric'],
                'consultation_rating' => ['sometimes', 'string'],
                'hide_my_name' => ['sometimes', 'boolean']
            ],[
                'unique' => 'Review Already Submitted.',
                'required' => 'Please fill the feedback form to proceed'
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $input['user_id'] = $this->getUserIdFromHeader($request->header());
            $getReview = Review::create($input);
            if(!$getReview){
                return $this->returnResponse(400, 'Unable to review the doctor.');
            }
            return $this->returnResponse(200, 'Reviewed the doctor successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to Add new Family Members
     */
    public function getReview(Request $request, User $doctor)
    {
        try {
            $getReviews = Review::getReviews($doctor->id);
            if(!count($getReviews)){
                return $this->returnResponse(400, 'No one review this doctor.');
            }
            return $this->returnResponse(200, '', $getReviews);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getSingleReview($id)
    {
        try {
            $getReview = Review::getSingleReview($id);
            if(is_null($getReview)){
                return $this->returnResponse(404, 'Not Found');
            }
            return $this->returnResponse(200, '', $getReview);
        } catch (\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
}
