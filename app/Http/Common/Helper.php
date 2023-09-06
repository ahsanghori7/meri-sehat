<?php

namespace App\Http\Common;

use App\Activity\Activity;
use App\Models\{ApiToken, Appointment, LogActivity, ReferenceWidget, Setting, Transaction, User, UserNotification};
use App\Http\Common\{Constant, FcmHelper, ResponseHelper, EmailHelper};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Ladumor\OneSignal\OneSignal;

class Helper {

    public const STATIC_ASSET_DISK = "public";

    static public function getSql($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }

    static public function ApiErrorResponse($data,$code){
        return response()->json($data,$code);
    }

    static public function getSmsText($type,$code){
        return "Your MeriSehat code is ".$code.". For further information please visit https://www.merisehat.pk.";
    }

    static public function generateUniqueCode(){
        $id = \Str::random(6);
        $validator = \Validator::make(['id'=>$id],['id'=>'unique:users,unique_code']);
        if($validator->fails()){
            return $this->generateUniqueCode();
        }
        return $id;
    }

    static public function get_date_from_calender() {
        $current_month_count = date("t");
        $current_date = date('Y-m-d');
        $days_array = array();
        for ($i=0; $i < $current_month_count; $i++) {
            $date = date('d M', strtotime(date("d M", strtotime($current_date)) . " +" . $i . "days"));
            $day = date('D' ,strtotime(date("D", strtotime($current_date)) . " +" . $i . "days"));
            $full_date = date('Y-m-d' ,strtotime(date("D", strtotime($current_date)) . " +" . $i . "days"));
            array_push($days_array,
                [
                    'date' => $date,
                    'day' => $day,
                    'full_date' => $full_date
                ]
            );
        }
        return $days_array;
    }

    public static function toast($type,$message,$title='') {
        Session::flash('title',$title);
        Session::flash('type',$type);
        Session::flash('message',$message);
    }

    public static function sweatAlert($type,$message,$title='') {
        Session::flash('title',$title);
        Session::flash('type',$type);
        Session::flash('message',$message);
    }

    static public function createActivityLog( $template_id,$params = [] ){
    	try{
    	    if( Auth::check() ){
    	        $user = Auth::user()->id;
    	    }else {
    	        $user = 1;
    	    }
    	   Activity::create([
    			'activity_template_id' => $template_id,
    			'user_id' => $user,
    			'activity_time' => date('Y-m-d h:i:s'),
    			'json_params' => json_encode($params)
    		]);
    	}catch(\Exception $e) {
    		return false;
    	}
    	return true;
    }

    static public function group_by($key, $data) {
        $result = array();

        foreach($data as $val) {
            if(array_key_exists($key, $val)){
                $result[$val[$key]][] = $val;
            }else{
                $result[""][] = $val;
            }
        }

        return $result;
    }

    static public function generateOTP($n = 4) {
        if(env('APP_ENV') != 'production'){
            return '0000';
        }
        //
        // 'otp' => $otp = rand(1000, 9999),
        $generator = "1357902468";
        $result = "";
        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand()%(strlen($generator))), 1);
        }
        return $result;
    }

    static public function get_fcm_by_token($token){

        try{
            return ApiToken::where('token', $token)->first()->fcm_token;
    	}catch(\Exception $e) {
    		return null;
    	}
    }

    static public function sendOTP($user_id) {
        try {
            $user = User::find($user_id);

            if($user){
                $otp = Helper::generateOTP();
                $user->otp = $otp;
                $user->save();
                // $sms = new SmsHelper;
                // $sms->send($user->phone,'Your phone verification OTP is '.$otp);
                return [
                    'status' => true,
                    'code' => 200,
                    'message' => "Successfully sent OTP.",
                    'otp' => $otp
                ];
            }else{
                return [
                    'status' => false,
                    'code' => 200,
                    'message' => "No User Found"
                ];
            }
        } catch(\Exception $e) {
            return [
                'status' => false,
                'message' => "Something went wrong"
            ];
        }
    }

    static public function sendOTPViaEmail($user_id) {
        try {
            $user = User::find($user_id);

            if($user){
                $otp = Helper::generateOTP();
                $user->otp = $otp;
                $user->save();
                $settings = Setting::getValues(['email','terms_condition_content_id','privacy_content_id']);
                $otp_params = [
                    'otp' => $otp,
                    'policy_url' => 'https://web.skyfreightalarabia.com/term-condition',
                    'terms_url' => 'https://web.skyfreightalarabia.com/privacy-policy',
                    'email' => $settings['email'],
                    'year' => date("Y"),
                    'logo_url' => asset('admin/img/logo_new.png'),
                ];
                EmailHelper::sendEmail($user->name,$user->email,'Sky Freight - password reset OTP',EmailHelper::$password_change_otp_template,$otp_params);
                return [
                    'status' => true,
                    'code' => 200,
                    'message' => "Successfully sent OTP.",
                    'otp' => $otp
                ];
            }else{
                return [
                    'status' => false,
                    'code' => 200,
                    'message' => "Email address not found"
                ];
            }
        } catch(\Exception $e) {
            return [
                'status' => false,
                'message' => "Something went wrong"
            ];
        }
    }

    public static function get_distance($lat1, $lon1,$lat2, $lon2, $unit="km" ){


        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "km")
        {
            return ($miles * 1.609344);
        }
        else if ($unit == "n")
        {
        return ($miles * 0.8684);
        }
        else
        {
        return $miles;
      }

    }

    public static function generate_qr($qty = 0){
        $qr_codes = [];
        $row = 0;
        while($row < $qty){
            $random_number_1 = str_replace('0.','',substr(rand(),2,2));
            $random_number_2 = str_replace('0.','',substr(rand(),2,2));
            $random_number = $random_number_1.$random_number_2;
            $uniqid_1 = substr(uniqid(),4,4);
            $uniqid_2 = substr(uniqid(),9,10);
            $code = $uniqid_1."-".$random_number."-".$uniqid_2;
            $qr_code = "SF-".$code;
            if(!in_array($qr_code,$qr_codes)){
                array_push($qr_codes, $qr_code);
                $row++;
            }
        }
        return $qr_codes;

    }

    /**
     * This method is used to generate masking of an Email
     */
    public static function emailMasking($value)
    {
        if(!$value){
            return "";
        }
        $exploadedEmail   = explode("@", $value);
        $lengthCount = strlen($exploadedEmail[0]) > 3 ? 3 : strlen($exploadedEmail[0]);
        return substr($exploadedEmail[0], 0, $lengthCount) . str_repeat('*', strlen($exploadedEmail[0]) - $lengthCount) . "@" . end($exploadedEmail);
    }
    /**
     * This method is used to generate masking of a phone number
     */
    public static function phoneMasking($value)
    {
        return '+92'.substr($value, 1, 2) . str_repeat('*', 5) . substr($value, 8);
    }
    /**
     * This method is used to sanitize data
     */
    public static function sanitizeData($value){
        $value = str_replace(' ,', ', ', $value);
        $value = str_replace(', ,', ', ', $value);
        $value = str_replace(',,', ',', $value);
        $value = str_replace('  ', ' ', $value);
        $value = str_replace('.,', ',', $value);
        $value = str_replace(',.', ',', $value);
        return $value;
    }

    public static function getRandomPassword() {
        return "Doctor@123";
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public static function sendToUser(array $data)
    {
        foreach($data as $key => $d){
            if($d->send_via === 'notification'){
                foreach($d->to as $to){
                    $user = User::find($to);
                    if($user){
                        DB::statement('SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci');
                        $notification = UserNotification::create([
                            'user_id' => $user->id,
                            'ref_id' => $d->ref_id,
                            'title' => $d->title,
                            'sub_title' => $d->sub_title,
                            'type' => $d->type,
                            'type_data' => $d->type_data,
                            'text' => $d->text,
                            'module' => $d->module,
                            'payload' => $d->payload,
                            'read_status' => 0,
                        ]);
                        Self::sendPushNotificationsById($notification->user_id, $notification->title, $notification->text, $notification->payload);
                        Self::sendByOneSignal($notification->user_id, $notification->title, $notification->text, $notification->payload);
                    }
                }
            }elseif($d->send_via === 'email'){
                foreach($d->to as $to){
                    $user = User::find($to);
                    if($user){
                        if($user->email && $user->email!='null'){
                            EmailHelper::sendMail($d->templatePath, $d->templateData, $user->email, $d->subject);
                        }
                    }
                }
            }elseif($d->send_via === 'sms'){
                foreach($d->to as $to){
                    $user = User::find($to);
                    if($user){
                        if($user->phone){
                            $sms = new SmsHelper;
                            if(env('APP_ENV') == 'local' || env('APP_ENV') == 'production'){
                                $sms->send($user->phone, $d->message);
                            }
                        }
                    }
                }
            }
        }
    }

    public static function getTokenDetailsById($userId){
        return ApiToken::where('user_id', $userId)->where('fcm_token', '<>', '')->orderBy('id','desc')->get();
    }

    public static function getPlayerId($userId)
    {
        return ApiToken::where('user_id', $userId)->where('player_id', '<>', '')->orderBy('id', 'desc')->get();
    }

    public static function sendByOneSignal($userId, $title, $body, $payload = null)
    {
        $helper = new OnesignalHelper();
        $player = Self::getPlayerId($userId);
        if(count($player)){
            foreach ($player as $getPlayer) {
                if(isset($getPlayer->player_id)){
                    $message['app_id'] = env('ONE_SIGNAL_APP_ID');
                    $message['include_player_ids'] = [$getPlayer->player_id];
                    $message['contents'] = ['en' => $title];
                    if($payload && is_string($payload)){
                        $payload = json_decode($payload, true);
                        $message['custom_data'] = isset($payload['message']) ? $payload['message'] : '';
                    }
                    $helper->sendSignalPush(json_encode($message));
                }
            }
        }
    }

    public static function sendPushNotificationsById($userId, $title, $body, $payload = []){
        $getTokens = Self::getTokenDetailsById($userId);
        if(count($getTokens)){
            foreach($getTokens as $getToken){
                if(isset($getToken->fcm_token)){
                    FcmHelper::push($getToken->fcm_token, $title, $body, $payload);
                }
            }
        }
    }

    public static function base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){
        if (isset($_SERVER['HTTP_HOST'])) {
            $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

            $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
            $core = $core[0];

            $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
            $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
            $base_url = sprintf( $tmplt, $http, $hostname, $end );
        }
        else $base_url = 'http://localhost/';

        if ($parse) {
            $base_url = parse_url($base_url);
            if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
        }

        return $base_url;
    }

//    public static function addToLog($subject, $old_object, $new_object)
//    {
//        $log = [];
//        $log['subject'] = $subject;
//        $log['url'] = Request::fullUrl();
//        $log['method'] = Request::method();
//        $log['ip'] = Request::ip();
//        $log['agent'] = Request::header('user-agent');
//        $log['user_id'] = auth()->check() ? auth()->user()->id : 1;
//        LogActivity::create($log);
//    }
//
//
//    public static function logActivityLists()
//    {
//        return LogActivity::latest()->get();
//    }

    public static function module_chk($reference = null) {
        $module_slug = null;
        if ($reference) {
            $widget_found = ReferenceWidget::find($reference);
            if ($widget_found) {
                if ($widget_found->reference_type == 'article') {
                    $module_slug = 'article-management';
                } elseif ($widget_found->reference_type == 'page') {
                    $module_slug = 'default-page';
                } else {
                    $module_slug = 'default-page';
                }
            }
        } else {
            if (request()->headers->get('referer') != '') {
                if (str_contains(request()->headers->get('referer'), 'article')) {
                    $module_slug = 'article-management';
                } elseif (str_contains(request()->headers->get('referer'), 'disease')) {
//                    $module_slug = 'disease-page';
                    $module_slug = 'default-page';
                } elseif (str_contains(request()->headers->get('referer'), 'drug')) {
//                    $module_slug = 'drug-page';
                    $module_slug = 'default-page';
                } elseif (str_contains(request()->headers->get('referer'), 'page')) {
                    $module_slug = 'default-page';
                }
            }
        }
        return $module_slug;
    }

    public static function settings_permission_replace($slug = null) {
        if (Str::contains($slug, '(') || Str::contains($slug, ')') || Str::contains($slug, ' ')) {
            $slug = str_replace(array(' ','(',')'),array('-','',''), $slug);
        }
        return strtolower($slug);
    }

    public static function cancelAppointment($appointment)
    {
        $check_appointment = Appointment::find($appointment);
        if($check_appointment->booked_via_subscription == 'one_time_payment'){
            $transaction = Transaction::where('reference_id', '0')
            ->where('reference_type', 'one_time')
            ->where('is_avail', '0')
            ->where('status', '1')
            ->where('is_cancel', '0')
            ->where('user_id', $check_appointment->user_id)
            ->latest()->first();
            if($transaction){
                $transaction->update([
                    'is_cancel' => 1
                ]);
            }
        }
        return true;
    }
}
