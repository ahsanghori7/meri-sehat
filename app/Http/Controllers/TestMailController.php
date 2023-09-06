<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Common\EmailHelper;
use App\Http\Common\SmsHelper;
use App\Http\Common\NotificationHelper;
use App\Http\Common\FcmHelper;

class TestMailController extends Controller
{
    public function sendMail(Request $request){
        // return view('email_templates.welcome_newsletter');
        $template_path = 'email_templates.welcome_newsletter';
        $template_data = [
            "age" => "26",
            "gender" => "male",
        ];
        $to_email = "harshamweuno@gmail.com";
        $subject = "Test Mail Template 2";
        return EmailHelper::sendMail($template_path, $template_data, $to_email, $subject);
    }

    public function sendSms(Request $request){
        $to = '03173194157';
        $message = "Testing Speed 22";
        $smsHelper = new SmsHelper();
        return $smsHelper->send($to, $message);
    }
    
    public function createAdminNotification(Request $request){
        $payload = [
            'module' => 'dashboard',
            'id' => 1,
        ];
        NotificationHelper::createAdminNotification("Test Notification",'Test Notification BODY',$payload);
        return true;
    }

        
    public function sendPushNotification(Request $request){
        $device_token="fixF7FlsRdqYBbBEkfpItH:APA91bFPMQmv_YC6q1Hor7umyO9KVMuG1mwstIhINbhhEFPSSvo52uEfkzMRtMhhUJkdZSV4rfAg7zFlGOJvQM70AA7ZZ301YLwkeYc7wl8D5ouDxw2_iA6o8nZHlP_-42INcXQxH_VT";
        $title = "Test Notification harsham";
        $text = "Test Notification From Code";
        $payload = [
            'module' => 'dashboard',
            'id' => 1,
        ];
        FcmHelper::push($device_token,$title,$text,$payload);
        return true;
    }
    
}
