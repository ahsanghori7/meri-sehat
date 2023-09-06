<?php   namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;
use App\Http\Common\Helper;
use Illuminate\Support\Collection;
use stdClass;

class ApiGeneralController extends Controller
{
    public function preferences(Request $request){
        try{
            $settings = Settings::getValues($this->requiredSettings);
            $data = [
                'settings' => $settings
            ];
            return $this->returnResponse(200, 'Content Fetched.',$data);
        }catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function sendToUserTestingMethod(Request $request)
    {
        // $notification = new stdClass;
        // $notification->to = [442];
        // $notification->send_via = 'notification';
        // $notification->ref_id = 0;
        // $notification->title = 'title';
        // $notification->sub_title = 'sub_title';
        // $notification->type = 'type';
        // $notification->type_data = 'type_data';
        // $notification->text = 'text';
        // $notification->module = 'module';
        // $notification->payload = 'payload';
        

        $email = new stdClass;
        $email->to = [442];
        $email->send_via = 'email';
        $email->templatePath = 'email_templates.release_1.subscription_expiry_soon';
        $email->templateData = ['name' => 'Zuhair Khan', 'link' => 'https://www.merisehat.pk', 'end_date' => '12-12-2023 07:05:00'];
        $email->subject = 'Your Meri Sehat subscription is expiring soon! Hurry and renew today!';
        

        $email1 = new stdClass;
        $email1->to = [442];
        $email1->send_via = 'email';
        $email1->templatePath = 'email_templates.release_1.one_time_payment';
        $email1->templateData = ['name' => 'Zuhair Khan', 'link' => env('WEB_URL').'start', 'subscription_type' => 'Lite'];
        $email1->subject = 'Success! Your Doctor Now appointment payment is confirmed!';
        

        $email2 = new stdClass;
        $email2->to = [442];
        $email2->send_via = 'email';
        $email2->templatePath = 'email_templates.release_1.subscription_paid';
        $email2->templateData = ['name' => 'Zuhair Khan', 'link' =>env('WEB_URL').'start', 'subscription_type' => 'Lite', 'otp' => 1451];
        $email2->subject = 'Success! Your Subscription payment is confirmed!';
                    



        $email3 = new stdClass;
        $email3->to = [442];
        $email3->send_via = 'email';
        $email3->templatePath = 'email_templates.release_1.otp';
        $email3->templateData = ['name' => 'Zuhair Khan', 'otp' => 1451, 'subscription_type' => 'Lite', 'link' =>env('WEB_URL')];
        $email3->subject = 'OTP verification';


        $email4 = new stdClass;
        $email4->to = [442];
        $email4->send_via = 'email';
        $email4->templatePath = 'email_templates.release_1.welcome';
        $email4->templateData = ['name' => 'Zuhair Khan', 'consult_link' => env('WEB_URL').'doctor-now', 'vitals_link' => env('WEB_URL').'page/sehat-scan/', 'link' =>env('WEB_URL')];
        $email4->subject = 'Welcome to Meri Sehat! Your FREE scan and consultation is waiting for you!';

        // $sms = new stdClass;
        // $sms->to = [442, 663];
        // $sms->send_via = 'sms';
        // $sms->message = $request->message;

        
        Helper::sendToUser([$email, $email1, $email2, $email3, $email4]);
    }

    public function runCron(Request $request){
        // \Artisan::call('usersubscription:expire_refill');
        // print_r("usersubscription:expire_refill");    
        // \Artisan::call('send:every-night-notification');
        // print_r("send:every-night-notification");    
        \Artisan::call('scan:limit');
        print_r("scan:limit");    
        // \Artisan::call('remove:preappointment');
        // print_r("remove:preappointment");    
        // \Artisan::call('instant:off');
        // print_r("instant:off");    
    }

    public function getWeightAndHeight(Request $request){
        if($request->has('type')){
            $data = $this->getDataFromTypeWise($request->type);
        }else{
            $data = [
                'weight' => $this->createWeight(),
                'height' => $this->createHeight()
            ];
        }
        return $this->returnResponse(200, 'Content Fetched.',$data);
    }

    private function createWeight(){
        $d = [];
        for($i=1; $i<=450; $i++){
            $kg = $i;
            $d[] = $kg . "kg";
        }
        return $d;
    }

    private function createHeight(){
        $d = [];
        for($i=1; $i<=8; $i++){
            for($y=0; $y<= 11; $y++){
                $ft = $i."ft";
                $in = ($y != 0) ? ' '.$y."in" : '';
                $d[] = $ft.$in;
            }
        }
        return $d;
    }

    private function getDataFromTypeWise($type){
        if($type === 'kg/cm'){
            for($i=1; $i<=450; $i++){
                $weight[] = $i.'kg';
            }
            for($j=1; $j<=250; $j++){
                $height[] = $j.'cm';
            }
        }else{
            for($i=1; $i<=1000; $i++){
                $weight[] = $i.'lb';
            }
            for($j=1; $j<=100; $j++){
                $height[] = $j.'inch';
            }
        }

        return [ 'weight' => $weight, 'height' => $height];
    }
}
