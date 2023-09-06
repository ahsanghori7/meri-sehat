<?php

namespace App\Http\Controllers\Api;

use App\Http\Common\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\{FamilyMemberResource, HealthScanResource};
use App\Models\{HealthScan, Settings, User, UserFamilyMember, Language, UserSubscription};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Jenssegers\Agent\Agent;

class HealthScanController extends Controller
{
    /**
     * This method is used to create or insert a new health record
     */
    public function createHealthScan(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'family_member_id' => ['sometimes', 'exists:family_members,id'],

                'blood_pressure' => ['sometimes'],
                'stress_level' => ['sometimes'],
                'respiratory_rate' => ['sometimes'],
                'spo2' => ['sometimes'],
                'heart_rate' => ['sometimes'],
                'bmi' => ['sometimes'],

                'diagnosis' => ['sometimes'],
                'recommendation' => ['sometimes'],
                'ip_address' => ['sometimes'],
                'sdnn' => ['sometimes'],

                'score' => ['sometimes'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            if(Request()->segment(2) === "v2"){
                $user = \Auth::user() ?? \Auth::guard("api")->user();
                if($request->has('user')){
                    $input['user_id'] = $request->user;
                }else{
                    if($user){
                        if(isset($user->id)){
                            $input['user_id'] = $user->id;
                        }else{
                            return $this->returnResponse(403, 'Unauthorized');
                        }
                    }else{
                        $input['user_id'] = null;
                    }
                }
            }else{
                $input['user_id'] = $this->getUserIdFromHeader($request->header()) == 0 ? null : $this->getUserIdFromHeader($request->header());
            }
            $agent = new Agent();
            $platform = $agent->platform();
            if($request->has('ip_address')){
                $input['ip_address'] = $request->ip_address;
            }else{
                $input['ip_address'] = $_SERVER['REMOTE_ADDR'];
            }
            $input['user_agent'] = $platform;
            $input['requestData'] = json_encode($request->all()) . json_encode($request->headers->all());
            $user_Id=$input['user_id'];
            $getSubscriptionCheck = User::with('subscription')->find($user_Id);
            if($getSubscriptionCheck){
                if($getSubscriptionCheck->subscription != null){
                    if($getSubscriptionCheck->subscription->receipt_data){
                    $consume_scan_limit = json_decode($getSubscriptionCheck->subscription->receipt_data);
                        if(property_exists($consume_scan_limit, 'consume_scan_limit') && property_exists($consume_scan_limit, 'scan_limit')){
                            if($consume_scan_limit->scan_limit != 'unlimited'){
                                if($consume_scan_limit->consume_scan_limit >= $consume_scan_limit->scan_limit){
                                    return $this->returnResponse(400, 'You have reached your limit. Please buy our Subscription Packages.');
                                }
                                $consume_scan_limit_new = $consume_scan_limit->consume_scan_limit + 1;
                                $data=UserSubscription::where('user_id',$user_Id)->update(['receipt_data->consume_scan_limit' => $consume_scan_limit_new]); 
                            }
                        }
                    }
                }
            }
            $getUser = User::find($user_Id);
            $getHealthScan = HealthScan::create($this->validateUndefined($input));
            if(!$getHealthScan){
                return $this->returnResponse(400, 'Unable to create health scan.');
            }

            if($getHealthScan){
                // if ($request->has('score') && $request->score < 5) {
                //     $name = $getUser->name ?? 'User';
                //     $notification = new stdClass;
                //     $notification->send_via = 'notification';
                //     $notification->to = [$getUser->id ?? 0];
                //     $notification->ref_id = null;
                //     $notification->title = "Try SehatScan again";
                //     $notification->sub_title = '';
                //     $notification->key = null;
                //     $notification->type = 'health_scans_critical';
                //     $notification->type_data = $getUser->id ?? null;
                //     $notification->text = "Dear $name, your SehatScan results are not normal 😓😓, tap here to try again and if it persist seek medical advice.";
                //     $notification->module = 'health_scans';
                //     $notification->message = "Dear $name, your SehatScan results are not normal 😓😓, tap here to try again and if it persist seek medical advice.";
                //     $notification->payload = json_encode($notification);
                //     Helper::sendToUser([$notification]);
                // } elseif ($request->has('score') && $request->score >= 5) {
                //     $name = $getUser->name ?? 'User';
                //     $notification = new stdClass;
                //     $notification->send_via = 'notification';
                //     $notification->to = [$getUser->id ?? 0];
                //     $notification->ref_id = null;
                //     $notification->title = "We wish you good health! 🙌🙌";
                //     $notification->sub_title = '';
                //     $notification->key = null;
                //     $notification->type = 'health_scans_normal';
                //     $notification->type_data = $getUser->id ?? null;
                //     $notification->text = "Dear $name, your SehatScan results are normal 💖💖, don’t forget to check your vitals periodically.";
                //     $notification->module = 'health_scans';
                //     $notification->message = "Dear $name, your SehatScan results are normal 💖💖, don’t forget to check your vitals periodically.";
                //     $notification->payload = json_encode($notification);
                //     Helper::sendToUser([$notification]);
                // }
            }
            return $this->returnResponse(200, 'Health scan created successfully.', new HealthScanResource($getHealthScan));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to get all the health scans
     */
    public function getHealthScanDetails(Request $request, HealthScan $healthScan)
    {
        return $this->returnResponse(200, '', new HealthScanResource($healthScan));
    }
    /**
     * This method is used to get all the health scans
     */
    public function listHealthScans(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => ['date'],
        ]);
        if ($validator->fails()) {
            return $this->returnResponse(400, $validator->errors()->first());
        }

        $chk_req = Request()->segment(2) === "v2" ? "v2" : "v1";
        $userId = $request->header('user_id') ?? $request->header('user-id');
        if ($chk_req=='v2') {
            $userId = auth()->user()->id;
        }

        //check if doctor is checking health scans of specific user
        $check_role = User::where('id', $userId)->first()->role_id;
        if($check_role === 3){
            $getFamilyMembers = UserFamilyMember::where('user_id', $request->user_id)->get();
            $getAllHealthScans = HealthScan::getAllHealthScans($request->user_id, $request);
            return $this->returnResponse(200, '', [
                "health_scans" => isset($getAllHealthScans) ? $getAllHealthScans : [],
                "family_members" => isset($getFamilyMembers) ? FamilyMemberResource::collection($getFamilyMembers) : []
            ]);
        }
        // Subscription restriction is here
        $subscription = UserSubscription::where('user_id',$userId)
        ->where('status', true)
        ->where('end_date', '>=', Carbon::now()->toDateTimeString())
        ->where('is_expired', 0)
        ->first();
        if(!empty($subscription->receipt_data) && property_exists(json_decode($subscription->receipt_data), 'detail_history')){
            $getFamilyMembers = UserFamilyMember::where('user_id', $userId)->get();
            $getAllHealthScans = HealthScan::getAllHealthScans($userId, $request);
            return $this->returnResponse(200, '', [
                "health_scans" => isset($getAllHealthScans) ? $getAllHealthScans : [],
                "family_members" => isset($getFamilyMembers) ? FamilyMemberResource::collection($getFamilyMembers) : []
            ]);
        }
        return $this->returnResponse(400, 'Your Subscription Is Expired!');
    }

    public function listHealthScansDate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'year_month' => ['sometimes','date_format:Y-m-d'],
        ]);
        if ($validator->fails()) {
            return $this->returnResponse(400, $validator->errors()->first());
        }
        $chk_req = Request()->segment(2) === "v2" ? "v2" : "v1";
        $userId = $request->user_id ?? $request->header('user-id');
        if ($chk_req=='v2') {
            $userId = auth()->user()->id;
        }
        // Subscription restriction is here
        // $user = User::where('id', $userId);
        $user = User::where('id', $userId);
        if($user = $user->whereHas('subscription')->first()){
            $getAllHealthScans = HealthScan::getCurrentMonthHealthScans($user, $request);
        }
        return $this->returnResponse(200, '',
            isset($getAllHealthScans) ? $getAllHealthScans : [],
        );

    }
    /**
     * This method is used to give permission to the user to scan yourself
     */
    public function checkLimitation(Request $request)
    {
        $now = Carbon::now();
        $tomorrow = Carbon::tomorrow();
        $totalDuration = $tomorrow->diffInSeconds($now);
        if($request->header('platform') && $request->header('platform') !== 'web'){
            $validator = Validator::make($request->all(), [
                'ip_address' => ['required'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $ip_address = $request->ip_address;
        }else{
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        if(Request()->segment(2) === "v2"){
            $user = \Auth::user() ?? \Auth::guard("api")->user();
            if($request->has('user')){
                $userId = $request->user;
            }else{
                if($user){
                    if(isset($user->id)){
                        $userId = $user->id;
                    }else{
                        $userId = null;
                    }
                }else{
                    $userId = null;
                }
            }
        }else{
            $userId = $this->getUserIdFromHeader($request->header()) == 0 ? null : $this->getUserIdFromHeader($request->header());
        }       
        $settings = Settings::getValues([
            'guest_health_scan_count',
            'non_subscribed_member_daily_health_scan_count',
            'subscribed_member_health_scan_count',
            'non_subscribed_member_health_scan_count',
            'non_subscribed_member_health_scan_days_limit'
        ]);
        $affirmativeMessage = "Scan yourself.";
        $affirmativeMessageUrdu = "اپنے آپ کو اسکین کریں۔";
        // For Guest User
        if(!$userId){
            return $this->returnResponse(400, "Please verify phone number",['next_scan_time' => 0]);
        }
        $errorMessage = "You have reached your limit. Please buy our Subscription Packages.";
        $errorMessageInUrdu = "آپ اپنی حد کو پہنچ چکے ہیں۔ براہ کرم ہمارے سبسکرپشن پیکجز خریدیں۔";

        //check user scan limit
        $getSubscriptionCheck = User::with('subscription')->find($userId);
        if($getSubscriptionCheck->subscription != null){
            if($getSubscriptionCheck->subscription->receipt_data){
            $checkScanLimit = json_decode($getSubscriptionCheck->subscription->receipt_data);
                if(property_exists($checkScanLimit, 'scan_limit')){
                    if($checkScanLimit->scan_limit != 'unlimited'){
                        if($checkScanLimit->scan_limit <= $checkScanLimit->consume_scan_limit){
                            $mess = ($checkScanLimit->scan_limit == 1 ) ? "Only " . $checkScanLimit->scan_limit . " scan allowed per day" : "Only " . $checkScanLimit->scan_limit . " scans allowed per day";
                            return $this->returnResponse(400, $mess, ['next_scan_time' => $totalDuration]);
                        }
                        else{

                            return $this->returnResponse(200, $affirmativeMessage);
                        }
                    }
                    else{
                        return $this->returnResponse(200, $affirmativeMessage);
                    }
                }
            }
        }
        
        $getSubscription = User::find($userId)->subscription;
        if(!$getSubscription || $getSubscription && Carbon::now()->toDateString() < Carbon::now()->toDateString()){
            return $this->returnResponse(400, "Please subscribe to check your Blood Pressure for free",['next_scan_time' => 0]);
        }

        $getHealthScanCount = HealthScan::where(['user_id' => $userId])->whereDate('created_at', '=', Carbon::now()->toDateString())->count();
        if($getHealthScanCount >= $settings['subscribed_member_health_scan_count']){
            if($request->header('locale') == Language::ENGLISH){
                return $this->returnResponse(400, $errorMessage, ['next_scan_time' => $totalDuration]);
            }else{
                return $this->returnResponse(400, $errorMessageInUrdu, ['next_scan_time' => $totalDuration]);
            }
        }
        if($request->header('locale') == Language::ENGLISH){
            $userSubscriptionHistory = new User();
            $data = null;
            if($getSubscription == null && $userSubscriptionHistory->subscription_history($userId) != null){
                $data['is_subscription_expired'] = true;
            }
            return $this->returnResponse(200, $affirmativeMessage, $data);
        }else{
            return $this->returnResponse(200, $affirmativeMessageUrdu);
        }
    }
    /**
     * This method is used to get details of the health scans details
     */
    public function getHealthScanByHash(Request $request, $id)
    {
        try{
            $getHealthScan = HealthScan::find(intval($id,36));
            if(!$getHealthScan){
                return $this->returnResponse(400, "Invalid hash value.");
            }
            return $this->returnResponse(200, '', new HealthScanResource(HealthScan::find(intval($id,36))));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function deleteHealthScan(Request $request,$healthScan){
        try {
            if(Request()->segment(2) === "v2"){
                $userId = \Auth::user()->id;
            }else{
                $userId = $request->user_id ? $request->user_id : $request->header('user-id');
            }
            $healthScan =healthScan::find($healthScan)->delete();
            if(!$healthScan){
                return $this->returnResponse(400, 'Unable to delete health scan record.');
            }
            return $this->returnResponse(200, 'health scan record has been deleted successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function deleteHealthScanAll(Request $request){
        try {
            if(Request()->segment(2) === "v2"){
                $userId = \Auth::user()->id;
            }else{
                $userId = $request->user_id ? $request->user_id : $request->header('user-id');
            }
            $healthScan =healthScan::whereDate('created_at',$request->date)->delete();
            if(!$healthScan){
                return $this->returnResponse(400, 'Unable to delete health scan records.');
            }
            return $this->returnResponse(200, 'health scan records has been deleted successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function shareSehatScanMessage(Request $request, HealthScan $healthScan)
    {
        $text = "MERI SEHAT measured my vitals:";
        if($healthScan->blood_pressure){
            $text .= ($healthScan->blood_pressure) ? "Blood Pressure: " . $healthScan->blood_pressure : '';
        }
        if($healthScan->respiratory_rate){
            $text .= ($healthScan->respiratory_rate) ? "Respiration Rate: " . $healthScan->respiratory_rate : '';
        }
        if($healthScan->heart_rate){
            $text .= ($healthScan->heart_rate) ? " Heart Rate: " . $healthScan->heart_rate : '';
        }
        if($healthScan->sdnn){
            $text .= ($healthScan->sdnn) ? " Sdnn: " . $healthScan->sdnn : '';
        }
        if($healthScan->stress_level){
            $text .= ($healthScan->stress_level) ? " Stress Level: " . $healthScan->stress_level : '';
        }
        if($healthScan->spo2){
            $text .= ($healthScan->spo2) ? " Oxygen Saturation: " . $healthScan->spo2 : '';
        }
        if($healthScan->score){
            $text .= ($healthScan->score) ? " Sehat Score: " . $healthScan->score."/10" : '';
        }
        if($healthScan->created_at){
            $text .= ($healthScan->created_at) ? " Taken on: " . $healthScan->created_at->format('dd/mm/yyyy') . "at" . $healthScan->created_at->format('H:i A') : '';
        }
        $text .= " Click on the link to get a free scan:";
        $data = [
            'title' => 'Health Check results',
            'chooserTitle' => '',
            'linkUrl' =>  'www.merisehat.pk/sehatscan',
            'text' => $text
        ];
        return $this->returnResponse(200, 'Health Check results', $data);
    }

    public function validateUndefined($request)
    {
        if(isset($request) && is_array($request))
        {
            foreach($request as $key => $value)
            {
                if($value == 'undefined/undefined'){
                    $request[$key] = null;
                }
                if($value == 'undefined'){
                    $request[$key] = null;
                }
            }
        }
        return $request;
    }
}
