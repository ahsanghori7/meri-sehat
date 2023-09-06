<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{
    Subscription, 
    SubscriptionPackage, 
    Language,
    DoctorDetail,
};
use App\Http\Resources\Admin\{SubscriptionResource};

class SubscriptionController extends Controller
{
    /**
     * This method is used to Get all the Packages created from the admin dashboard
     */
    public function getPackages(Request $request)
    {
        try {
            $getSubscriptions = Subscription::getSubscriptions();
            if(!count($getSubscriptions)){
                return $this->returnResponse(400, 'There is no package available right now.');
            }
            return $this->returnResponse(200, '', SubscriptionResource::collection($getSubscriptions));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    } 

    public function getDoctorDetails(Request $request)
    {
        try {
            $locale = $request->header('locale');
            $doctors = DoctorDetail::where(['is_instant_consultation' => 1, 'is_available' => 1])->get();
            $doctor_strings = [];
            $degree_strings = "";
            $speciality_strings = "";
            if($doctors && count($doctors) > 0){
                foreach($doctors as $doctor_key  => $doctor){
                    $degrees = $doctor->user->doctorEducation->pluck('degree');

                    if($degrees && count($degrees) > 0){
                        $degree_strings .= ", (";
                        foreach($degrees as $key  => $degree){
                            $degree_strings .= $degree . ($key +1 != count($degrees) ?  ", ": "");
                        }
                        $degree_strings .= ")";
                    }


                    $specialities = [];
                    if($doctor->user->doctorSpecialities){
                        $specialities = $doctor->user->doctorSpecialities;;
                    }
                    
                    if(count($specialities) > 0){
                        $speciality_strings .= ", (";
                        foreach($specialities as $key  => $speciality){
                            $speciality_strings .= $speciality->speciality->name . ($key +1 != count($specialities) ?  ", ": "");
                        }
                        $speciality_strings .= ")";
                    }
                    
                    $doctor_strings[$doctor_key] = $doctor_key+1 .". ".$doctor->user->name . $degree_strings. $speciality_strings;
                }
            }

            $doctorDetails['doctors'] = $doctor_strings;
            
            $doctorDetails['heading'] = 'Connect with a certified telehealth doctor in less than 5 minutes!';
            $doctorDetails['before_doctors'] = "Explore our subscription options now to get access.";
            $doctorDetails['doctor_heading'] = "Our doctor’s panel:";
            $doctorDetails['after_doctors'] = "";

            if($locale != Language::ENGLISH){
                $doctorDetails['heading'] = '!پانچ منٹ سے بھی کم وقت میں ہمارے سرٹیفائیڈ ڈاکٹر سے کنیکٹ کریں';
                $doctorDetails['before_doctors'] = "رسا ئی کے لئے ، ابھی ہماری سبسکرپشن کی تفصیل جانیں۔";
                $doctorDetails['doctor_heading'] = ":ہمارے ڈاکٹرز کے پینل میں شامل ہیں";
                $doctorDetails['after_doctors'] = "";
            }
            return $this->returnResponse(200, '', $doctorDetails);
        } catch(\Exception $e) {
            return $e;
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getPackagesDetail(Request $request, Subscription $subscription)
    {
        try {
            $subscription->total = $subscription->discounted_price != 0 ? $subscription->discounted_price : 'Free';
            $subscription->total_yearly = $subscription->discounted_price_yearly != 0 ? $subscription->discounted_price_yearly : 'Free';
            return $this->returnResponse(200, '', $subscription);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    } 
}
