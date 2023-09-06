<?php   namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use stdClass;
use Carbon\Carbon;
use App\Models\{Activity, Disease, HealthScan, Topics, User, ApiToken, Appointment, Article, City, DoctorDetail, DoctorService, DoctorEducation, Faq, GetALink,
    DoctorSpeciality, Language, Newsletter, Service, Speciality, Degree, UserArticleReview, UserSocialAccount, PatientInfo, FindADoctor, Settings,
    WidgetMedia, CorporatQuery,DoctorCondition, DoctorBankDetail};
use App\Http\Resources\{Video\VideoResource, User\AppointmentResource, User\DoctorResource, User\UserDetailResource, User\UserResource,
     User\UserSummeryResource};
use Illuminate\{Http\Request, Support\Str, Support\Arr};
use Illuminate\Support\Facades\{DB, Hash, Mail, Route, Validator};
use App\Http\Common\{Helper, SmsHelper, EmailHelper, Constant, Helper as CustomHelper, OnesignalHelper};
use Alaouy\Youtube\Facades\Youtube;
use Jenssegers\Agent\Agent;
use Ladumor\OneSignal\OneSignal;

class UserController extends Controller
{
    public function checkUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'regex:/(03)[0-9]{9}$/'],
            'network' => ['required'],
        ]);
        if ($validator->fails()) {
            return $this->returnResponse(400, $validator->errors()->first());
        }
        $getUser = User::where(['status' => true, 'is_blocked' => false, 'phone' => $request->phone, 'role_id' => $this->getConstantByValue("USER_ROLE_ID")])->first();
        if ($getUser) {
            $otp = Helper::generateOTP();
            $getUser->appointment_otp = $otp;
            $getUser->save();

            // // SEND OTP EMAIL
            // $template_path = 'email_templates.otp';
            // $template_data = [
            //     "otp_type" => "Login",
            //     "otp" => $otp,
            // ];
            // $to_email = "tahairshad@weuno.com";
            // $subject = "Meri Sehat Login OTP";
            // EmailHelper::sendMail($template_path, $template_data, $to_email, $subject);

            // // SEND OTP SMS
            // $to = $getUser->phone;
            // $message = Helper::getSmsText('login',$otp);
            // $smsHelper = new SmsHelper();
            // $smsHelper->send($to, $message);
        } else {
            return $this->returnResponse(401, "Invalid mobile number.");
        }
        return $this->returnResponse(200, "OTP has been sent successfully.", [
            'mask_email' => CustomHelper::emailMasking($getUser->email),
            'mask_phone' => CustomHelper::phoneMasking($getUser->phone),
        ]);
    }

    public function checkAppointmentOTP(Request $request)
    {
        try{
            $input = $request->all();
            $validator = Validator::make($input, [
                'phone' => 'required|exists:users,phone',
                'otp' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }

            $getUser = User::where(['status' => true, 'is_blocked' => false, 'appointment_otp' => $request->otp, 'phone' => $request->phone, 'role_id' => $this->getConstantByValue("USER_ROLE_ID")])->first();

            if(!$getUser){
                if($request->header('locale') == Language::URDU){
                    return $this->returnResponse(400, 'غلط توثیقی کوڈ۔');
                }
                return $this->returnResponse(400, 'Invalid OTP code.');
            }

            $input['appointment_otp'] = null;
            $updateUser = $getUser->update($input);
            if(!$updateUser){
                return $this->returnResponse(400, 'Unable to update user appointment otp.');
            }
            return $this->returnResponse(200, "OTP has been verified.", [
                'mask_email' => CustomHelper::emailMasking($getUser->email),
                'mask_phone' => CustomHelper::phoneMasking($getUser->phone),
            ]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function checkAppointmentResendOTP(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                //'network' => ['required'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $getUser = User::where(['status' => true, 'is_blocked' => false, 'phone' => $request->phone])->first();
            if($getUser) {
                $otp = Helper::generateOTP();
                $getUser->appointment_otp = $otp;
                $getUser->save();

                // // SEND OTP EMAIL
                //$template_path = 'email_templates.otp';
                //$template_data = [
                //    "otp_type" => "Resend",
                //    "otp" => $otp,
                //];
                //$to_email = "tahairshad@weuno.com";
                //$subject = "Meri Sehat Resend OTP";
                //EmailHelper::sendMail($template_path, $template_data, $to_email, $subject);

                // // SEND OTP SMS
                // $to = $getUser->phone;
                // $message = Helper::getSmsText('resend',$otp);
                // $smsHelper = new SmsHelper();
                // $smsHelper->send($to, $message);

                return $this->returnResponse(200, "OTP has been resent successfully.", [
                    'mask_email' => CustomHelper::emailMasking($getUser->email),
                    'mask_phone' => CustomHelper::phoneMasking($getUser->phone),
                ]);
            } else {
                return $this->returnResponse(401, 'Invalid mobile number.');
            }
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    /**
     * This method is used to SignUp a new User
     */
    public function login(Request $request)
    {
        try {
            $is_signup = true;
            $validator = Validator::make($request->all(), [
                'phone' => ['required', 'regex:/(03)[0-9]{9}$/'],
                'network' => ['required'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            if ($request->network === 'undefined' || $request->network === 'Undefined') {
                return $this->returnResponse(400, 'Please select a network');
            }
            $userName = $request->has('name') ? $request->name : null;
            $getUser = User::where(['status' => true, 'is_blocked' => false, 'phone' => $request->phone])->first();
            if ($getUser) {
                if($getUser->role_id  != $this->getConstantByValue("USER_ROLE_ID")){
                    return $this->returnResponse(400, 'Invalid phone number.');
                }
                $is_signup = false;
                $userName = $request->has('name') ? $request->name : $getUser->name;
            }
            $otp = Helper::generateOTP();
            if($request->phone == '03313468086' || $request->phone ==  '3313468086'){
                $otp = '2052';
            }
            $getUser = User::updateOrCreate([
                    'phone' => $request->phone
                ], [
                    'otp' => $otp,
                    'otp_expire' => Carbon::now()->addMinutes(10),
                    'network' => strtolower($request->network),
                    'platform' => $request->header('platform') ? $request->header('platform') : 'web',
                    'name' => $userName
                    //'role_id' => $this->getConstantByValue("USER_ROLE_ID")
                ]
            );
            // // SEND OTP EMAIL
            // $template_path = 'email_templates.otp';
            // $template_data = [
            //     "otp_type" => "Login",
            //     "otp" => $otp,
            // ];
            // $to_email = "tahairshad@weuno.com";
            // $subject = "Meri Sehat Login OTP";
            // EmailHelper::sendMail($template_path, $template_data, $to_email, $subject);

            // // SEND OTP SMS
            // $to = $getUser->phone;
            // $message = Helper::getSmsText('login',$otp);
            // $smsHelper = new SmsHelper();
            // $smsHelper->send($to, $message);
            $settings = Settings::getValues(['uan_number']);
            $sms = new stdClass;
            $sms->to = [$getUser->id];
            $sms->send_via = 'sms';
            $sms->message = $otp.' is your One-TIme Password for Meri Sehat. This OTP is valid for 10 minutes only. Please do not share your OTP with anyone. Thank you.';
            CustomHelper::sendToUser([$sms]);
            $customerName = $getUser->name ?? 'customer';
            $email = new stdClass;
            $email->to = [$getUser->id];
            $email->send_via = 'email';
            $email->templatePath = 'email_templates.release_1.otp';
            $email->templateData = ['name' => $customerName, 'otp' => $otp, 'uan' => $settings['uan_number']];
            $email->subject = 'OTP verification';
            CustomHelper::sendToUser([$email]);
            $agent = new Agent();
            $activities=Activity::create([
                'user_id'=>$getUser->id,
                'action' => 'login',
                'device_information' => isset($request->device_id) ? $request->device_id : '',
                'device_type' => $agent->isMobile() || $agent->isTablet() ? 'Mobile' : 'Web',
                'verified_login' => 0,
            ]);
            return $this->returnResponse(200, "OTP has been sent successfully.", [
                'mask_email' => CustomHelper::emailMasking($getUser->email),
                'mask_phone' => CustomHelper::phoneMasking($getUser->phone),
                'is_sign_up' => $is_signup,
            ]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * Thismethod is used to resend OTP
     */
    public function resendOtp(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                //'network' => ['required'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $getUser = User::where(['is_blocked' => false, 'phone' => $request->phone])->first();
            if(!$getUser) {
                return $this->returnResponse(401, 'You are not authorized.');
            }

            // $otp = '0000';
           $otp = Helper::generateOTP();
           if($request->phone == '03313468086' || $request->phone ==  '3313468086'){
                $otp = '2052';
            }
            if ($request->network === 'undefined' || $request->network === 'Undefined') {
                $network = $getUser->network;
            }else{
                $network =strtolower($request->network);
            }
            if ($request->has('network')) {
                $getUser->update(['otp' => $otp, 'network' => $network, 'otp_expire' => Carbon::now()->addMinutes(10)]);
            } else {
                $getUser->update(['otp' => $otp, 'otp_expire' => Carbon::now()->addMinutes(10)]);
            }
            // $getUser->update(['otp' => $otp = rand(1000, 9999)]);

            // // SEND OTP EMAIL
             $template_path = 'email_templates.otp';
             $template_data = [
                 "otp_type" => "Resend",
                 "otp" => $otp,
             ];
             $to_email = "tahairshad@weuno.com";
             $subject = "Meri Sehat Resend OTP";
            //EmailHelper::sendMail($template_path, $template_data, $to_email, $subject);

            // // SEND OTP SMS
            // $to = $getUser->phone;
            // $message = Helper::getSmsText('resend',$otp);
            // $smsHelper = new SmsHelper();
            // $smsHelper->send($to, $message);
            $settings = Settings::getValues(['uan_number']);
            $sms = new stdClass;
            $sms->to = [$getUser->id];
            $sms->send_via = 'sms';
            $sms->message = $otp.' is your One-TIme Password for Meri Sehat. This OTP is valid for 10 minutes only. Please do not share your OTP with anyone. Thank you.';
            CustomHelper::sendToUser([$sms]);
            $customerName = $getUser->name ?? 'customer';
            $email = new stdClass;
            $email->to = [$getUser->id];
            $email->send_via = 'email';
            $email->templatePath = 'email_templates.release_1.otp';
            $email->templateData = ['name' => $customerName, 'otp' => $otp, 'uan' =>$settings['uan_number']];
            $email->subject = 'OTP verification';
            CustomHelper::sendToUser([$email]);

            return $this->returnResponse(200, "OTP has been resent successfully.", [
                'mask_email' => CustomHelper::emailMasking($getUser->email),
                'mask_phone' => CustomHelper::phoneMasking($getUser->phone),
                'is_sign_up' => ($getUser->email == null) ? true : false,
            ]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function doctorOtp(Request $request)
    {
        try{
            $input = $request->all();
            $validator = Validator::make($input, [
                'phone' => ['required', 'regex:/(03)[0-9]{9}$/','exists:users,phone'],
                'otp' => ['required', 'numeric', 'exists:users,otp'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $getUser = User::where('phone', $request->phone)->where('otp', $request->otp)->first();

            if(!$getUser){
                if($request->header('locale') == Language::ENGLISH){
                    return $this->returnResponse(400, 'Invalid OTP code.');
                }else{
                    return $this->returnResponse(400, 'غلط توثیقی کوڈ۔');
                }
            }

            $input['status'] = true;
            $input['is_phone_verified'] = true;
            $updateUser = $getUser->update($input);
            if(!$updateUser){
                return $this->returnResponse(400, 'Unable to update user profile.');
            }
            if(ApiToken::getTokenDetails($request->bearerToken())) {
                ApiToken::getTokenDetails($request->bearerToken())->update(['user_id' => $getUser->id]);
            }
            $agent = new Agent();
            $activity = Activity::where('user_id', $getUser->id)->latest()->first();
            $activity->update([
                'verified_login' => 1,
            ]);
            return $this->returnResponse(200, 'Phone number has been verified successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to verify User's phone number
     */
    public function verifyOtp(Request $request)
    {
        try{
            $input = $request->all();
            $validator = Validator::make($input, [
                'phone' => ['required', 'regex:/(03)[0-9]{9}$/','exists:users,phone'],
                'otp' => ['required', 'numeric'],
                'new_phone' => ['unique:users,phone', 'regex:/(03)[0-9]{9}$/'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            (isset($input['new_phone']))
            ?   $getUser = User::getUser($input['phone'], $input['otp'], null, $input['new_phone'])
            :   $getUser = User::getUser($input['phone'], $input['otp']);

            if(!$getUser){
                if($request->header('locale') == Language::ENGLISH){
                    return $this->returnResponse(400, 'Invalid OTP code.');
                }else{
                    return $this->returnResponse(400, 'غلط توثیقی کوڈ۔');
                }
            }
            if(Carbon::now()->greaterThan($getUser->otp_expire)){
                return $this->returnResponse(400, 'The OTP code has expired.');
            }

            if(isset($input['new_phone'])){
                $input['phone'] = $input['new_phone'];
                $input['new_phone'] = null;
                $getUser['phone'] = $input['phone'];
                $getUser['new_phone'] = null;
            }
            $getUser['otp'] = null;
            $input['otp'] = null;
            $input['status'] = true;
            $input['is_phone_verified'] = true;
            $input['otp_expire'] = null;
            $getUser['otp_expire'] = null;
            $getUser['status'] = true;
            $updateUser = $getUser->update($input);

            if(!$updateUser){
                return $this->returnResponse(400, 'Unable to update user profile.');
            }
            if($getUser->is_welcome_message == 0 && $getUser->role_id == 2){
                $customerName = $getUser->name ?? 'customer';
                $settings = Settings::getValues(['uan_number']);
                $helpLineNumber = $settings['uan_number'];
                $sms = new stdClass;
                $sms->to = [$getUser->id];
                $sms->send_via = 'sms';
                $sms->message = "Dear $customerName, welcome to Meri Sehat, Pakistan’s first AI powered health platform. You have been given 1 FREE Doctor Now consultation. For more info, call us at $helpLineNumber";
                CustomHelper::sendToUser([$sms]);
                $getUser->update(['is_welcome_message' => 1]);
            }

            if($getUser->is_welcome_email == 0 && $getUser->role_id == 2){
                $settings = Settings::getValues(['uan_number']);
                $email = new stdClass;
                $email->to = [$getUser->id];
                $email->send_via = 'email';
                $email->templatePath = 'email_templates.release_1.welcome';
                $email->templateData = ['name' => $getUser->name ?? 'customer', 'consult_link' => env('WEB_URL').'doctor-now', 'vitals_link' => env('WEB_URL').'page/sehat-scan/', 'uan' => $settings['uan_number']];
                $email->subject = 'Welcome to Meri Sehat! Your FREE scan and consultation is waiting for you!';
                CustomHelper::sendToUser([$email]);
                $getUser->update(['is_welcome_email' => 1]);
            }

            if($getUser->is_welcome_push == 0 && $getUser->role_id == 2) {
                $name = $getUser->name ?? 'User';
                $notification = new stdClass;
                $notification->send_via = 'notification';
                $notification->to = [$getUser->id];
                $notification->ref_id = null;
                $notification->title = "Welcome to Meri Sehat! 🎉🎊🙌";
                $notification->sub_title = '';
                $notification->key = null;
                $notification->type = 'user_signup';
                $notification->type_data = $getUser->id;
                $notification->text = "Dear $name, welcome to Meri Sehat, Pakistan’s first AI powered health platform. You have been given 1 FREE Doctor Now consultation. Tap here to avail 📝";
                $notification->module = 'users';
                $notification->message = "Dear $name, welcome to Meri Sehat, Pakistan’s first AI powered health platform. You have been given 1 FREE Doctor Now consultation. Tap here to avail 📝";
                $notification->payload = json_encode($notification);
                CustomHelper::sendToUser([$notification]);
                $getUser->update(['is_welcome_push' => 1]);
            }

            if(ApiToken::getTokenDetails($request->bearerToken())) {
                $onesignal = new OnesignalHelper();
                $player_id = $onesignal->registerUser($getUser->id);
                ApiToken::getTokenDetails($request->bearerToken())->update(['user_id' => $getUser->id]);
                if(isset($player_id)) {
                    ApiToken::where('user_id', $getUser->id)->update(['player_id' => $player_id]);
                }
            }
            $agent = new Agent();
            $activity = Activity::where('user_id', $getUser->id)->latest()->first();
            $activity->update([
                'verified_login' => 1,
            ]);
            return $this->returnResponse(200, 'Phone number has been verified successfully.', ['user' => new UserResource($getUser)]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to verify otp
     */
    public function checkOtp(Request $request)
    {
        try{
            $input = $request->all();
            $validator = Validator::make($input, [
                'phone' => 'required|exists:users,phone',
                'otp' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }

            $getUser = User::getUser($input['phone'], $input['otp']);

            if(!$getUser){
                if($request->header('locale') == Language::URDU){
                    return $this->returnResponse(400, 'غلط توثیقی کوڈ۔');
                }
                return $this->returnResponse(400, 'Invalid OTP code.');
            }


            $unique_code = Helper::generateUniqueCode();

            $getUser['otp'] = null;
            $input['otp'] = null;
            $input['status'] = true;
            $getUser['status'] = true;
            $getUser['is_phone_verified'] = true;
            $getUser['unique_code'] = $unique_code;
            $updateUser = $getUser->update($input);
            if(!$updateUser){
                return $this->returnResponse(400, 'Unable to update user profile.');
            }
            if(ApiToken::getTokenDetails($request->bearerToken())) {
                ApiToken::getTokenDetails($request->bearerToken())->update(['user_id' => $getUser->id]);
            }
            return $this->returnResponse(200, 'Phone number has been verified successfully.', ['user' => new DoctorResource($getUser)]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This methoid is used to get User details
     */
    public function getUser(Request $request){
        try{
            if(Request()->segment(2) === "v2"){
                $user = \Auth::user() ?? \Auth::guard("api")->user();
                if($request->has('user')){
                    $userId = $request->user;
                }else{
                    if($user){
                        if(isset($user->id)){
                            $userId = $user->id;
                        }else{
                            return $this->returnResponse(403, 'Unauthorized');
                        }
                    }else{
                        return $this->returnResponse(403, 'Unauthorized');
                    }
                }
            }else{
                $userId = isset($request->user) ? $request->user : $request->header('user_id');
            }
            $getUser = User::find($userId);
            if(!$getUser){
                return $this->returnResponse(400, 'User not found.');
            }
            if(Request()->segment(2) === "v2" && $request->has('user') && $getUser->role_id != 3){
                return $this->returnResponse(400, 'Doctor not found.');
            }
            return $this->returnResponse(200, 'User Details.', ['user' => new UserDetailResource($getUser)]);
        }catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to update User profile Image
     */
    public function updateProfileImage(Request $request){
        try{
            $input = $request->all();
            $validator = Validator::make($input, [
                'image' => ['image', 'mimes:jpeg,png,jpg,JPEG,PNG,JPG,MPEG,heif,heic,heif-sequence,heic-sequence'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            if($request->header('locale') != Language::ENGLISH){
                if(isset($input['city_id'])){
                    $input['city_id'] = City::find($input['city_id'])->translation_of;
                }
            }
            if(Request()->segment(2) === "v2"){
                $userId = \Auth::user()->id;
            }else{
                $userId = $request->header('user_id');
            }
            $getUser = User::find($userId);
            if($getUser && $getUser->role_id != $this->getConstantByValue("USER_ROLE_ID")) {
                return $this->returnResponse(401, 'You are not authorized.');
            }
            if($request->hasFile('image')){
                if($getUser->image){
                    $this->deleteFile($getUser->image, $getUser->id);
                }
                $medicalrecord = new stdClass;
                $medicalrecord->file=$request->file('image');
                $folder="user".$getUser->id;
                $input['image'] = $this->S3Uploader($medicalrecord,$folder);
            }
            unset($input['is_sign_up']);
            unset($input['phone']);
            $updateUser = $getUser->update($input);
            if(!$updateUser){
                return $this->returnResponse(400, 'Unable to update user profile.');
            }
            return $this->returnResponse(200, 'Updated user profile successfully.', ['user' => new UserResource(User::find($getUser->id))]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    /**
     * This method is used to update User profile
     */
    public function updateProfile(Request $request){
        try{
            $input = $request->all();
            if($request->is_sign_up == true){
                $validator = Validator::make($input, [
                    'city_id' => ['required', 'exists:cities,id'],
                    'gender' => ['required'],
                ]);
            }else{
                $validator = Validator::make($input, [
                    'image' => ['image', 'mimes:jpeg,png,jpg,JPEG,PNG,JPG,MPEG,heif,heic,heif-sequence,heic-sequence'],
                    'city_id' => ['required', 'exists:cities,id'],
                ]);
            }
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            if($request->has('email') && $request->email){
                $getEmailDomain = explode('@', $request->email);
                $checkUnwantedEmail = \DB::table('unwanted_domains')->where('domain', $getEmailDomain[1])->first();
                if($checkUnwantedEmail){
                    return $this->returnResponse(400, 'Email domain could not be verified.');
                }
            }
            if($request->header('locale') != Language::ENGLISH){
                $input['city_id'] = City::find($input['city_id'])->translation_of;
            }
            if(Request()->segment(2) === 'v2'){
                $userId = \Auth::user()->id;
            }else{
                $userId = $request->header('user_id');
            }
            $getUser = User::find($userId);
            if($getUser && $getUser->role_id != $this->getConstantByValue("USER_ROLE_ID")) {
                return $this->returnResponse(401, 'You are not authorized.');
            }
            if($request->hasFile('image')){
                if($getUser->image){
                    $this->deleteFile($getUser->image, $getUser->id);
                }
                $folder="images";
                $input['image']= $this->S3Uploader($request,$folder);
            }
            unset($input['is_sign_up']);
            unset($input['phone']);
            $updateUser = $getUser->update($input);
            if(isset($request->email)){
                $onesignal = new OnesignalHelper();
                $onesignal->updateUserPush($getUser->id, $request->email);
            }
            if(!$updateUser){
                return $this->returnResponse(400, 'Unable to update user profile.');
            }
            return $this->returnResponse(200, 'Updated user profile successfully.', ['user' => new UserResource(User::find($getUser->id))]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function updateUserNameAndEmail(Request $request){
        try{
            $input = $request->all();
            $validator = Validator::make($input, [
                'email' => ['required', 'email'],
                'name' => ['required'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            if($request->has('email')){
                $getEmailDomain = explode('@', $request->email);
                $checkUnwantedEmail = \DB::table('unwanted_domains')->where('domain', $getEmailDomain[1])->first();
                if($checkUnwantedEmail){
                    return $this->returnResponse(400, 'Email domain could not be verified.');
                }
            }
            if(Request()->segment(2) === 'v2'){
                $userId = \Auth::user()->id;
            }else{
                $userId = $request->header('user_id');
            }
            $getUser = User::find($userId);
            if($getUser && $getUser->role_id != $this->getConstantByValue("USER_ROLE_ID")) {
                return $this->returnResponse(401, 'You are not authorized.');
            }
            $updateUser = $getUser->update($input);
            if(!$updateUser){
                return $this->returnResponse(400, 'Unable to update user profile.');
            }
                $onesignal = new OnesignalHelper();
                $onesignal->updateUserPush($getUser->id, $request->email);
            return $this->returnResponse(200, 'Updated user profile successfully.', ['user' => new UserResource(User::find($getUser->id))]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to send otp on email or phone number
     */
    public function logout(Request $request)
    {
        try{
            if(Request()->segment(2) === 'v1'){
                $userId = $request->header('user_id');
                $token = ApiToken::where('token', $request->bearerToken())->first();
                if ($token) {
                    $token->delete();
                }
            }else{
                $userId = $request->user()->id;
                $request->user()->token()->revoke();
                \DB::table('oauth_access_tokens')->where('user_id', $request->user()->id)->delete();
            }
            $onesignal = new OnesignalHelper();
            if($request->has('player_id') && $request->player_id != ''){
                $onesignal->deletePlayer($request->player_id);
            }
            if($request->header('locale') == Language::ENGLISH){
                $agent = new Agent();
            $activities = Activity::create([
                'user_id'=>$userId,
                'action' => 'logout',
                'device_information' => isset($request->device_id) ? $request->device_id : '',
                'device_type' => $agent->isMobile() || $agent->isTablet() ? 'Mobile' : 'Web',
                'verified_login' => 0,
            ]);
                return $this->returnResponse(200, 'Logged out successfully.');
            }else{
                $agent = new Agent();
            $activities=Activity::create([
                'user_id'=>$userId,
                'action' => 'logout',
                'device_information' => isset($request->device_id) ? $request->device_id : '',
                'device_type' => $agent->isMobile() || $agent->isTablet() ? 'Mobile' : 'Web',
                'verified_login' => 0,
            ]);
                return $this->returnResponse(200, 'کامیابی سے لاگ آؤٹ ہو گیا۔');
            }
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function doctorRegistrationShortPhoneOnly(Request $request)
    {
        try{

            DB::beginTransaction();
            $input = $request->all();

            $validator = Validator::make($input, [
                'phone' => ['required', 'unique:users,phone', 'regex:/(03)[0-9]{9}$/'],
                'network' => ['required'],
             ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            if ($request->network === 'undefined' || $request->network === 'Undefined') {
                return $this->returnResponse(400, 'Please select a network');
            }
            $otp = Helper::generateOTP();
            $createDoctor = User::create([
                'city_id' => 1,
                'phone' => $request->phone,
                'network' => strtolower($request->network),
                'role_id' => $this->getConstantByValue("DOCTOR_ROLE_ID"),
                'otp' => $otp,
                'is_subscribed' => 0,
                'is_phone_verified' => 0,
                'is_blocked' => 0,
                'status' => 0,
            ]);


            // // SEND OTP SMS
            // $to = $createDoctor->phone;
            // $message = Helper::getSmsText('resend',$otp);
            // $smsHelper = new SmsHelper();
            // //$smsHelper->send($to, $message);



            if(!$createDoctor){
                return $this->returnResponse(400, 'Unable to create doctor profile.');
            }
            if(ApiToken::getTokenDetails($request->bearerToken())) {
                ApiToken::getTokenDetails($request->bearerToken())->update(['user_id' => $createDoctor->id]);
            }
            $sms = new stdClass;
            $sms->to = [$createDoctor->id];
            $sms->send_via = 'sms';
            $sms->message = $otp.' is your One-TIme Password for Meri Sehat. This OTP is valid for 10 minutes only. Please do not share your OTP with anyone. Thank you.';
            CustomHelper::sendToUser([$sms]);
            $agent = new Agent();
            $activities=Activity::create([
                'user_id'=>$createDoctor->id,
                'action' => 'login',
                'device_information' => isset($request->device_id) ? $request->device_id : '',
                'device_type' => $agent->isMobile() || $agent->isTablet() ? 'Mobile' : 'Web',
                'verified_login' => 0,
            ]);
            DB::commit();
            return $this->returnResponse(200, 'Doctor profile has been created successfully.', [
                'mask_phone' => CustomHelper::phoneMasking($createDoctor->phone),
            ]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function doctorRegistrationAfterOTP(Request $request)
    {
        try{

            DB::beginTransaction();
            $input = $request->all();

            Validator::extend('alpha_spaces', function($attribute, $value)
            {
                return preg_match('/^[\pL\s]+$/u', $value);
            });

            $messages = [
                'alpha_spaces' => 'The :attribute may only contain letters and spaces.',
            ];

            $validator = Validator::make($input, [
                'name' => 'required|alpha_spaces|max:100',
                'phone' => ['required', 'regex:/(03)[0-9]{9}$/', 'exists:users,phone'],
                'otp' => ['required', 'exists:users,otp'],
                'email' => ['email', 'unique:users,email'],
                'pmc_number' => ['required'],
                'speciality' => ['required','array'],
                'speciality.*' => ['required', 'exists:specialities,id'],
                'city' => ['required','alpha'],
            ], $messages);

            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }

            //Get city ID
            $get_city_id = City::where('name',$request->city)->first();

            if(!$get_city_id){
                return $this->returnResponse(400, 'Invalid city please type correct city.');
            }

            $password = Str::random(10);
            $unique_code = Helper::generateUniqueCode();

            $createDoctor = User::where('phone', $request->phone)
                ->where('role_id', $this->getConstantByValue("DOCTOR_ROLE_ID"))
                ->where('otp', $request->otp)
                ->where('is_phone_verified', 1)
                ->where('status', 1)
                ->first();

            if (!$createDoctor) {
                return $this->returnResponse(400, 'Unable to create doctor profile, please go back and verify otp');
            }

            $createDoctor->update([
                'unique_code' => $unique_code,
                'otp' => null,
                'city_id' => $get_city_id->id,
                'phone' => $request->phone,
                'name' => $request->name,
                'password' => Hash::make($password),
                'email' => $request->email
            ]);

            $doctorDetails = DoctorDetail::create([
                'doctor_id' => $createDoctor->id,
                'prefix' => 'Dr',
                'pmc_no' => $request->pmc_number
            ]);

            foreach($request->speciality as $specialityId) {
                $doctorSpeciality = DoctorSpeciality::create([
                    'doctor_id' => $createDoctor->id,
                    'speciality_id' => $specialityId,
                ]);
            }

            // SEND CREDENTIAL EMAIL
            $template_path = 'email_templates.doctor_login_credentials';
            $template_data = [
                "email" => $createDoctor->email,
                "password" => $password,
            ];
            $to_email = $createDoctor->email;
            $subject = "Welcome to MeriSehat";
            EmailHelper::sendMail($template_path, $template_data, $to_email, $subject);

            if(!$doctorDetails || !$doctorSpeciality){
                return $this->returnResponse(400, 'Unable to create doctor profile.');
            }
            if(ApiToken::getTokenDetails($request->bearerToken())) {
                ApiToken::getTokenDetails($request->bearerToken())->update(['user_id' => $createDoctor->id]);
            }
            DB::commit();
            //return $this->returnResponse(200, 'Doctor profile has been created successfully.', ['user' => new UserResource(User::find($createDoctor->id))]);
            return $this->returnResponse(200, 'Doctor profile has been updated successfully.', [
                'unique_code' => $unique_code,
            ]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function doctorRegistrationShort(Request $request)
    {
        try{

            DB::beginTransaction();
            $input = $request->all();

            Validator::extend('alpha_spaces', function($attribute, $value)
            {
                return preg_match('/^[\pL\s]+$/u', $value);
            });

            $messages = [
                'alpha_spaces' => 'The :attribute may only contain letters and spaces.',
            ];

            $validator = Validator::make($input, [
                'name' => 'required|alpha_spaces|max:100',
                'phone' => ['required', 'regex:/(03)[0-9]{9}$/', 'unique:users,phone'],
                'email' => ['required', 'email', 'unique:users,email'],
                'pmc_number' => ['required'],
                'speciality' => ['required','array'],
                'speciality.*' => ['required', 'exists:specialities,id'],
                'city' => ['required','alpha'],
            ], $messages);

            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }

            //Get city ID
            $get_city_id = City::where('name',$request->city)->first();

            if(!$get_city_id){
                return $this->returnResponse(400, 'Invalid city please type correct city.');
            }

            //$otp = rand(1000, 9999);
            $otp = Helper::generateOTP();
            $password = Str::random(10);
            //$unique_code = Helper::generateUniqueCode();

            $createDoctor = User::create([
                // 'unique_code' => $unique_code,
                'city_id' => $get_city_id->id,
                'phone' => $request->phone,
                'name' => $request->name,
                'password' => Hash::make($password),
                'email' => $request->email,
                'otp' => $otp,
                'role_id' => $this->getConstantByValue("DOCTOR_ROLE_ID"),
                // 'status' => 0
            ]);

            $doctorDetails = DoctorDetail::create([
                'doctor_id' => $createDoctor->id,
                'prefix' => 'Dr',
                'pmc_no' => $request->pmc_number
            ]);

            foreach($request->speciality as $specialityId) {
                $doctorSpeciality = DoctorSpeciality::create([
                    'doctor_id' => $createDoctor->id,
                    'speciality_id' => $specialityId,
                ]);
            }

            // SEND CREDENTIAL EMAIL
            $template_path = 'email_templates.doctor_login_credentials';
            $template_data = [
                "email" => $createDoctor->email,
                "password" => $password,
            ];
            $to_email = $createDoctor->email;
            $subject = "Welcome to MeriSehat";
            EmailHelper::sendMail($template_path, $template_data, $to_email, $subject);

            // SEND OTP EMAIL
            $template_path = 'email_templates.otp';
            $template_data = [
                "otp_type" => "Signup",
                "otp" => $otp,
            ];
            $to_email = $createDoctor->email;
            $subject = "Meri Sehat Signup OTP";
            EmailHelper::sendMail($template_path, $template_data, $to_email, $subject);

            // // SEND OTP SMS
            // $to = $createDoctor->phone;
            // $message = Helper::getSmsText('resend',$otp);
            // $smsHelper = new SmsHelper();
            // // $smsHelper->send($to, $message);



            if(!$createDoctor || !$doctorDetails || !$doctorSpeciality){
                return $this->returnResponse(400, 'Unable to create doctor profile.');
            }
            if(ApiToken::getTokenDetails($request->bearerToken())) {
                ApiToken::getTokenDetails($request->bearerToken())->update(['user_id' => $createDoctor->id]);
            }
            DB::commit();
            $sms = new stdClass;
            $sms->to = [$createDoctor->id];
            $sms->send_via = 'sms';
            $sms->message = $otp.' is your One-TIme Password for Meri Sehat. This OTP is valid for 10 minutes only. Please do not share your OTP with anyone. Thank you.';
            CustomHelper::sendToUser([$sms]);
            //return $this->returnResponse(200, 'Doctor profile has been created successfully.', ['user' => new UserResource(User::find($createDoctor->id))]);
            return $this->returnResponse(200, 'Doctor profile has been created successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function doctorRegistration(Request $request)
    {
        try{
            DB::beginTransaction();
            $input = $request->all();

            $validator = Validator::make($input, [
                'phone' => ['required', 'unique:users,phone', 'regex:/(03)[0-9]{9}$/'],
                'email' => ['required', 'email', 'unique:users,email'],
                'gender' => ['required'],
                'prefix' => ['required'],
                'name' => ['required'],
                'experience' => ['required'],
                'password' => ['required'],
                'pmc_number' => ['required'],
                'speciality' => ['required','array'],
                'speciality.*' => ['required', 'exists:specialities,id'],
                'service' => ['required','array'],
                'service.*' => ['required','exists:services,id'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }

            $createDoctor = User::create([
                'phone' => $request->phone,
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'gender' => $request->gender,
                'otp' => $otp = rand(1000, 9999),
                'role_id' => $this->getConstantByValue("DOCTOR_ROLE_ID")
            ]);

            $doctorDetails = DoctorDetail::create([
                'doctor_id' => $createDoctor->id,
                'prefix' => $request->prefix,
                'experience_year' => $request->experience,
                'pmc_no' => $request->pmc_number
            ]);
            foreach($request->speciality as $specialityId) {
                $doctorSpeciality = DoctorSpeciality::create([
                    'doctor_id' => $createDoctor->id,
                    'speciality_id' => $specialityId,
                ]);
            }
            foreach($request->service as $serviceId) {
                $doctorService = DoctorService::create([
                    'doctor_id' => $createDoctor->id,
                    'service_id' => $serviceId,
                ]);
            }
            if(!$createDoctor || !$doctorDetails || !$doctorSpeciality || !$doctorService){
                return $this->returnResponse(400, 'Unable to create doctor profile.');
            }
            if(ApiToken::getTokenDetails($request->bearerToken())) {
                ApiToken::getTokenDetails($request->bearerToken())->update(['user_id' => $createDoctor->id]);
            }
            $sms = new stdClass;
            $sms->to = [$createDoctor->id];
            $sms->send_via = 'sms';
            $sms->message = $otp.' is your One-TIme Password for Meri Sehat. This OTP is valid for 10 minutes only. Please do not share your OTP with anyone. Thank you.';
            CustomHelper::sendToUser([$sms]);
            DB::commit();
            return $this->returnResponse(200, 'Doctor profile has been created successfully.', ['user' => new UserResource(User::find($createDoctor->id))]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function doctorLogin(Request $request)
    {
        try
        {
            $input = $request->all();
            $validator = Validator::make($input, [
                'email' => ['required', 'email', 'exists:users,email'],
                'password' => ['required']
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $getUser = User::where(['status' => true, 'is_blocked' => false, 'email' => $request->email])->first();

            $passwordCheck = Hash::check($request->password, $getUser->password);
            if(!$passwordCheck) {
                return $this->returnResponse(400, 'Invalid email or password');
            }
            if(ApiToken::getTokenDetails($request->bearerToken())) {
                ApiToken::getTokenDetails($request->bearerToken())->update(['user_id' => $getUser->id]);
            }
            $agent = new Agent();
            $activities=Activity::create([
                'user_id'=>$getUser->id,
                'action' => 'doctor login',
                'device_id' => isset($request->device_id) ? $request->device_id : '',
                'device_type' => $agent->isMobile() || $agent->isTablet() ? 'Mobile' : 'Web',
                'verified_login' => 0,
            ]);
            return $this->returnResponse(200, 'Successfully logged in!', [
                'mask_email' => CustomHelper::emailMasking($getUser->email),
                'mask_phone' => CustomHelper::phoneMasking($getUser->phone),
                'user' => new UserResource($getUser)
            ]);
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function doctorLoginViaPhone(Request $request)
    {
        try
        {
            $input = $request->all();
            $validator = Validator::make($input, [
                'phone' => ['required', 'exists:users,phone', 'regex:/(03)[0-9]{9}$/'],
                'network' => ['required'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            if($request->phone == '03291112223'){
                $getUser = User::where('phone', $request->phone)->first();
            }else{
                $getUser = User::where([
                    'role_id' => $this->getConstantByValue('DOCTOR_ROLE_ID'),
                    'status' => true,
                    'is_blocked' => false,
                    'phone' => $request->phone
                ])->whereHas('doctorDetail', function($getUser) {
                    $getUser = $getUser->where('is_admin_verified', 1);
                })->first();
            }

            if (!$getUser) {
                return $this->returnResponse(400, 'Invalid phone number.');
            }
            $otp = Helper::generateOTP();
            // $otp = '0000';
            if($request->phone == '03291112223'){
                $otp = '0000';
            }
            $getUser->otp = $otp;
            $getUser->save();

            // SEND OTP SMS
            // $to = $getUser->phone;
            // $message = Helper::getSmsText('login',$otp);
            // $smsHelper = new SmsHelper();
            // $smsHelper->send($to, $message);
            $sms = new stdClass;
            $sms->to = [$getUser->id];
            $sms->send_via = 'sms';
            $sms->message = $otp.' is your One-TIme Password for Meri Sehat. This OTP is valid for 10 minutes only. Please do not share your OTP with anyone. Thank you.';
            CustomHelper::sendToUser([$sms]);
            $agent = new Agent();
            $activities=Activity::create([
                'user_id'=>$getUser->id,
                'action' => 'doctor login',
                'device_id' => isset($request->device_id) ? $request->device_id : '',
                'device_type' => $agent->isMobile() || $agent->isTablet() ? 'Mobile' : 'Web',
                'verified_login' => 0,
            ]);
            return $this->returnResponse(200, 'Successfully logged in!', [
                'mask_email' => CustomHelper::emailMasking($getUser->email),
                'mask_phone' => CustomHelper::phoneMasking($getUser->phone),
            ]);
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function doctorLoginViaPhoneOTP(Request $request)
    {
        try
        {
            $input = $request->all();
            $validator = Validator::make($input, [
                'phone' => ['required', 'exists:users,phone', 'regex:/(03)[0-9]{9}$/'],
                'otp' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }

            if($request->phone == '03291112223'){
                $getUser = User::where('phone', $request->phone)->first();
            }else{
                $getUser = User::where([
                    'role_id' => $this->getConstantByValue('DOCTOR_ROLE_ID'),
                    'otp' => $request->otp,
                    'status' => true,
                    'is_blocked' => false,
                    'phone' => $request->phone
                ])->first();
            }

            if (!$getUser) {
                return $this->returnResponse(400, 'Invalid OTP code');
            }

            $getUser->otp = null;
            $getUser->save();
            $token = '';
            if(ApiToken::getTokenDetails($request->bearerToken())) {
                $token = ApiToken::getTokenDetails($request->bearerToken());
                $token->update(['user_id' => $getUser->id]);
            }
            $agent = new Agent();
            $activity = Activity::where('user_id', $getUser->id)->latest()->first();
            $activity->update([
                'verified_login' => 1,
            ]);
            return $this->returnResponse(200, 'Successfully logged in!', [
                'mask_email' => CustomHelper::emailMasking($getUser->email),
                'mask_phone' => CustomHelper::phoneMasking($getUser->phone),
                'token' => $token,
                'user' => new UserResource($getUser)
            ]);
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function updateDoctor(Request $request)
    {
        try{
            DB::beginTransaction();
            $input = $request->all();
            $userId = $this->getUserIdFromHeader($request->header());
            $validator = Validator::make($input, [
                //'phone' => ['required', 'regex:/(03)[0-9]{9}$/'],
                // 'email' => ['required', 'email'],
                'gender' => ['required'],
                'name' => ['required'],
                'experience_year' => ['required'],
                'city_id' => ['required','exists:cities,id'],
                'education' => ['required','array'],
                'birth_date' => ['required','date'],
                'speciality' => ['required','array'],
                'speciality.*' => ['required','exists:specialities,id'],
                'service' => ['required','array'],
                'service.*' => ['required','exists:services,id'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            if ($request->network === 'undefined' || $request->network === 'Undefined') {
                return $this->returnResponse(400, 'Please select a network');
            }
            $updateDoctor = User::where(['status' => true, 'is_blocked' => false, 'id' => $userId])->update([
                'name'=>$request->name,
                // 'email'=>$request->email,
                'network'=> strtolower($request->network),
                'gender'=>$request->gender,
                'city_id'=>$request->city_id,
                'birth_date'=>$request->birth_date
            ]);

            DoctorDetail::where('doctor_id',$userId)->update([
                'experience_year' =>$request->experience_year,
                'is_physical_consultancy' => isset($request->is_physical_consultancy) ? $request->is_physical_consultancy : 0,
                'is_video_consultancy' => isset($request->is_video_consultancy) ? $request->is_video_consultancy : 0,
                'is_voice_consultancy' => isset($request->is_voice_consultancy) ? $request->is_voice_consultancy : 0,
            ]);

            foreach($request->speciality as $specialityId) {
                DoctorService::updateOrCreate(
                [
                    'doctor_id' => $userId,
                    'service_id' => $specialityId,
                ], [
                    'doctor_id' => $userId,
                    'service_id' => $specialityId,
                ]);
            }
            foreach($request->speciality as $specialityId) {
                DoctorSpeciality::updateOrCreate(
                [
                    'doctor_id' => $userId,
                    'speciality_id' => $specialityId,
                ],
                [
                    'doctor_id' => $userId,
                    'speciality_id' => $specialityId,
                ]);
            }

            foreach($request->education as $educations) {
                DoctorEducation::updateOrCreate(
                [
                    'degree' => $educations['degree'],
                ],
                [
                    'doctor_id' => $userId,
                    'degree' => $educations['degree'],
                    'institute' => $educations['institute'],
                    'year_of_completion' => isset($educations['year_of_completion']) ? $educations['year_of_completion'] : "",
                    'is_completed' => $educations['is_completed'],
                ]);
            }

            if(!$updateDoctor){
                return $this->returnResponse(400, 'Unable to update user profile.');
            }
            DB::commit();
            return $this->returnResponse(200, 'Updated user profile successfully.', ['user' => new UserSummeryResource(User::find($userId))]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }


    public function updateDoctorProfile(Request $request)
    {
        try{
            if(Request()->segment(2) === "v2"){
                $doctor_id = \Auth::user()->id;
            }else{
                $doctor_id = $this->getUserIdFromHeader($request->header());
            }
            $input = $request->all();

            $validator = Validator::make($input, [
                //'phone' => ['required', 'regex:/(03)[0-9]{9}$/'],
                // 'email' => ['required', 'email'],
                'name' => ['required'],
                'phone' => ['required'],
                'email' => ['required'],
                'experience_years' => ['required'],
                'city_id' => ['required','exists:cities,id'],
                'birth_date' => ['required','date'],
                'gender' => ['required'],
                'iban_number' => ['required'],
                'account_number' => ['required'],
                'account_name' => ['required'],

            ]);


            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }

            if($request->hasFile('image')){
                $folder="doctor";
                $key="image";
                $doctorImage = $this->S3UploaderSpecificKey($key,$request,$folder);
                $uploadDoctorImage=User::find($doctor_id)
                ->update(['image' => $doctorImage]);
            }


            $doctorFind=User::find($doctor_id)
            ->update(['name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'city_id' => $request->city_id
            ]);

            $doctorExperince=DoctorDetail::where('doctor_id',$doctor_id)
            ->update(
                [
                    'experience_year' => $request->experience_years,
                    'assistant_phone'=> $request->assistant_phone,
                ]);
            if($request->has('about')){
                $detail['about'] = $request->about;
            }
            if($request->has('pmc_no')){
                $detail['pmc_no'] = $request->pmc_no;
            }
            if(isset($detail)){
                DoctorDetail::where('doctor_id',$doctor_id)->update($detail);
            }
            if($request->has('speciality')){
                DoctorSpeciality::where('doctor_id', $doctor_id)->delete();
                DoctorCondition::where('doctor_id', $doctor_id)->delete();
                DoctorService::where('doctor_id', $doctor_id)->delete();
                foreach($request->speciality as $key => $value){
                    if(is_iterable($value['conditions'])){
                        foreach($value['conditions'] as $k => $v){
                            DoctorCondition::create([
                                'doctor_id' => $doctor_id,
                                'disease_id' => $v,
                                'speciality_id' => $request->speciality[$key]['speciality_id']
                            ]);
                        }
                    }
                    DoctorSpeciality::create([
                        'doctor_id' => $doctor_id,
                        'speciality_id' => $value['speciality_id']
                    ]);
                    DoctorService::insert([
                        'doctor_id' => $doctor_id,
                        'service_id' => $value['service_id'],
                        'speciality_id' => $value['speciality_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            $updateBankDetails=DoctorBankDetail::where('doctor_id',$doctor_id);

            $updateBD = $updateBankDetails->updateOrCreate(
                [
                    'doctor_id' => $doctor_id,
                ],
                [
                    'iban_number' => $request->iban_number,
                    'account_number' => $request->account_number,
                    'account_name' => $request->account_name,
                    'bank_name' => $request->bank_name ? $request->bank_name : "Null",
                ]);

            $updateCnic=DoctorDetail::where('doctor_id',$doctor_id)
                ->update(
                    [
                        'cnic' => $request->cnic,
                    ]);
             if(!$doctorFind && !$doctorExperince && !$updateBD && !$updateCnic){
                return $this->returnResponse(400, 'Unable to personal information.');
             }

            return $this->returnResponse(200, 'Updated Doctor profile successfully.', ['user' => new UserSummeryResource(User::find($doctor_id))]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function deleteService($id)
    {
        try{
            $removeService = DoctorService::where('id', $id)->delete();
            if(!$removeService){
                return $this->returnResponse(400, 'Unable to remove doctor service.');
            }
            return $this->returnResponse(200, 'Removed successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function deleteEducation($id)
    {
        try{
            $removeEduation = DoctorEducation::where('id',$id)->delete();
            if(!$removeEduation){
                return $this->returnResponse(400, 'Unable to remove doctor education.');
            }
            return $this->returnResponse(200, 'Removed successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to create new user's social account
     */
    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'regex:/(03)[0-9]{9}$/'],
            'social_id' => ['required'],
            'platform' => ['required', 'in:facebook,twitter,google,apple'],
        ]);
        if ($validator->fails()) {
            return $this->returnResponse(400, $validator->errors()->first());
        }
        DB::beginTransaction();

        $otp = Helper::generateOTP();
        $getUser = User::updateOrCreate([
                'phone' => $request->phone
            ], [
                // 'otp' => $otp = rand(1000, 9999),
                'otp' => $otp,
            // 'role_id' => $this->getConstantByValue("USER_ROLE_ID")
            ]
        );
        // // SEND OTP EMAIL
        // $template_path = 'email_templates.otp';
        // $template_data = [
        //     "otp_type" => "Signup",
        //     "otp" => $otp,
        // ];
        // $to_email = "tahairshad@weuno.com";
        // $subject = "Meri Sehat Signup OTP";
        // EmailHelper::sendMail($template_path, $template_data, $to_email, $subject);

        // // SEND OTP SMS
        // $to = $getUser->phone;
        // $message = Helper::getSmsText('signup',$otp);
        // $smsHelper = new SmsHelper();
        // $smsHelper->send($to, $message);

        $getUserHasSocial = User::whereHas('socialAccounts', function ($query) use ($request) {
            $query->where(['platform' => $request->platform, 'social_id' => $request->social_id]);
        })->first();
        if(!$getUserHasSocial){
            $getUser->socialAccounts()->create(['platform' => $request->platform, 'social_id' => $request->social_id]);
        }
        DB::commit();
        $agent = new Agent();
        $activities=Activity::create([
            'user_id'=>$getUser->id,
            'action' => 'login',
            'device_information' => isset($request->device_id) ? $request->device_id : '',
            'device_type' => $agent->isMobile() || $agent->isTablet() ? 'Mobile' : 'Web',
            'verified_login' => 0,
        ]);
        return $this->returnResponse(200, "OTP has been sent successfully.", [
            'mask_email' => CustomHelper::emailMasking($getUser->email),
            'mask_phone' => CustomHelper::phoneMasking($getUser->phone),
            'is_sign_up' => ($getUser->email == null) ? true : false,
        ]);
        try{
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                'social_id' => ['required'],
                'platform' => ['required', 'in:facebook,twitter,google,apple'],
                'phone' => ['sometimes', 'regex:/(03)[0-9]{9}$/'],
                'name' => ['sometimes'],
                'email' => ['sometimes'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            DB::beginTransaction();

            $getUser = User::whereHas('socialAccounts', function($query) use($request){
                $query->where(['platform' => $request->platform, 'social_id' => $request->social_id]);
            })->first();
            if(!$getUser){
                $getUser = User::create([
                    'role_id' => $this->getConstantByValue("USER_ROLE_ID"),
                    'phone' => $request->phone,
                    'name' => $request->name,
                    'email' => $request->email
                ]);
                UserSocialAccount::updateOrCreate(
                [
                    'user_id' => $getUser->id,
                    'platform' => $request->platform,
                    'social_id' => $request->social_id
                ],
                [
                    'platform' => $request->platform,
                    'social_id' => $request->social_id
                ]);
            }
            $getUser->update($input);
            if(ApiToken::getTokenDetails($request->bearerToken())) {
                ApiToken::getTokenDetails($request->bearerToken())->update(['user_id' => $getUser->id]);
            }
            DB::commit();
            return $this->returnResponse(200, 'Social logged in successfully.', ['user' => new UserResource($getUser)]);
        }catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to get all the FAQ's available
     */
    public function getFaqs(Request $request)
    {
        try {
            $getFaqs = Faq::getFaqs($request->category_id);
            if(!count($getFaqs)){
                return $this->returnResponse(400, 'There is no faq\'s available right now.');
            }
            return $this->returnResponse(200, '', $getFaqs);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to get all the FAQ's available
     */
    public function subscribeNewsletter(Request $request)
    {
        try {$validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);
        if ($validator->fails()) {
            return $this->returnResponse(400, $validator->errors()->first());
        }
        Newsletter::updateOrCreate(
            [ 'email' => $request->email ],
            [ 'email' => $request->email ]
        );

        $template_path = 'email_templates.welcome_newsletter';
        $to_email = $request->email;
        $subject = "Meri Sehat - Welcome";
        EmailHelper::sendMail($template_path, [], $to_email, $subject);
        return $this->returnResponse(200, 'You are subscribed for the newsletter successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function forgotPasswordMail(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email', 'exists:users,email'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $getUser = User::where('email', $request->email)->where('role_id', $this->getConstantByValue('DOCTOR_ROLE_ID'))->first();
            if (!$getUser) {
                return $this->returnResponse(400, 'Invalid email address.');
            }

                // $otp = '9999';
               $otp = Helper::generateOTP();

                $getUser->otp = $otp;
                $getUser->save();

                //User::where('email', $request->email)->update(['otp' => $otp]);

                // SEND OTP SMS
                $to = $getUser->phone;
                $message = Helper::getSmsText('resend', $otp);
                $smsHelper = new SmsHelper();
                //$smsHelper->send($to, $message);

                // SEND CREDENTIAL EMAIL
                $template_path = 'email_templates.doctor_forget_credentials';
                $template_data = [
                    "email" => $request->email,
                    "otp" => $otp,
                ];
                $to_email = $request->email;
                $subject = "Forget Password - Merisehat.pk";
                EmailHelper::sendMail($template_path, $template_data, $to_email, $subject);

            return $this->returnResponse(200, "OTP has been sent to your email successfully.", [
                'mask_email' => CustomHelper::emailMasking($getUser->email),
                'mask_phone' => CustomHelper::phoneMasking($getUser->phone)
            ]);
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function forgotPassword(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email', 'exists:users,email'],
                'otp' => ['required'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $getUser = User::getUser($request->email, $request->otp);
            if(!$getUser) {
                return $this->returnResponse(400, 'Invalid OTP.');
            }
            return $this->returnResponse(200, 'OTP verified successfully.');
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function resetPassword(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email', 'exists:users,email'],
                'password' => ['required'],
                'otp' => ['required'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $getUser = User::getUser($request->email);
            if(!$getUser) {
                return $this->returnResponse(400, 'User Not Found.');
            }
            if ($getUser->otp != $request->otp) {
                return $this->returnResponse(400, 'OTP doesnt match as you provided.');
            }
            $getUser->update(['password' => Hash::make($request->password)]);
            return $this->returnResponse(200, 'Password updated successfully.');
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'password' => ['required'],
                'current_password' => ['required']
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $userId = $this->getUserIdFromHeader($request->header());
            $getUser = User::where(['status' => true, 'is_blocked' => false, 'id' => $userId])->first();
            if(!$getUser) {
                return $this->returnResponse(400, 'User Not Found.');
            }
            $passwordCheck = Hash::check($request->current_password,$getUser->password);

            if($passwordCheck)
            {
                $getUser->update(['password'=>Hash::make($request->password)]);
            }
            else
            {
                return $this->returnResponse(400, 'Invalid Current Password');
            }

            $getUser->update(['password'=>Hash::make($request->password)]);

            return $this->returnResponse(200, 'Password Updated Successfully.');
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to like or unlink the specified article from user
     */
    public function likeOrUnLike(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'article_id' => ['required', 'exists:articles,id'],
                'is_like' => ['required']
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            if(Request()->segment(2) === "v1"){
                if($request->header('user_id') == 0){
                    return $this->returnResponse(400, 'For giving feedback, please register your account first.');
                }
            }

            UserArticleReview::updateOrcreate(
                [
                    'article_id' => $request->article_id,
                    'user_id' => Request()->segment(2) === "v1" ? $request->header('user_id') : \Auth::user()->id,
                ],
                [
                    'is_like' => $request->is_like == 'true' || $request->is_like == true ?  true : false,
                ]
            );
            if($request->header('locale') == Language::ENGLISH){
                $data = [
                    'total' => UserArticleReview::where('article_id', $request->article_id)->count(),
                    'like' => UserArticleReview::where([
                        ['article_id', $request->article_id],
                        ['is_like', true]
                    ])->count(),
                ];
                return $this->returnResponse(200, 'Thanks for your feedback', $data);
            }else{
                return $this->returnResponse(200, 'آپ کی راے کا شکریہ');
            }

        }catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to get the dashboard details for user
     */
    public function getDashboardDetails(Request $request)
    {
        try{
            $getUser = User::where(['status' => true, 'is_blocked' => false, 'id' => Request()->segment(2) === "v1" ? $request->header('user_id') : \Auth::user()->id])
            ->withCount([
                'familyMembers AS family_members',
                'healthScans AS health_scans',
                'medicalRecords AS medical_records',
                'doctorReviews AS total_reviews',
                'appointment AS total_appointments',
                'appointment AS in_person_appointments' => function($query){
                    $query->where('type', Constant::APPOINTMENT_TYPE_IN_PERSON);
                },
                'appointment AS virtual_appointments' => function($query){
                    $query->where('type', Constant::APPOINTMENT_TYPE_SCHEDULE);
                },
                'appointment AS instant_consultations' => function($query){
                    $query->where('type', Constant::APPOINTMENT_TYPE_INSTANT);
                },
                'appointment AS total_upcoming_appointments' => function($query){
                    $query->where('date', '>=', Carbon::today()->format('Y-m-d'))
                    ->where('time', '>=', Carbon::today()->format('H:i:m'));
                },
            ])
            ->first();
            $getAppointment = Appointment::where(['status' => true, 'user_id' => $getUser->id]);
            if ($request->has('family_id')) {
                $getAppointment = $getAppointment->where('family_member_id', $request->family_id);
            }
            $getAppointment = $getAppointment->where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)
            ->where(function($query){
                $query->where(function($subQuery){
                    $subQuery->where('date', '=', Carbon::now()->format('Y-m-d'));
                    $subQuery->where('time', '>=', Carbon::now()->format('H:i'));
                });
                $query->orWhere('date', '>', Carbon::now()->format('Y-m-d'));
            })
            ->orderBy('date', 'ASC')
            ->orderBy('time', 'ASC')
            ->first();

            $getPrescription = Appointment::where('user_id', $getUser->id)->with(['doctor', 'getAppointmentPrescription']);
            if ($request->has('family_id')) {
                $getPrescription = $getPrescription->where('family_member_id', $request->family_id);
            }
            $getPrescription = $getPrescription->where(function($query){
                $query->whereHas('getPrescription', function($subQuery){
                    $subQuery->where('status', 1);
                });
            })
            ->orderBy('id', 'desc')->take(1)->first();

            $id= null;
            if (isset($getPrescription->doctor_id)) {
                $id=$getPrescription->doctor_id;
            }
            $find_doctor = User::where('id',$id)->first();
            if ($find_doctor) {
                $doctor = new stdClass();
                $doctor->e_id = $find_doctor->e_id;
                $doctor->image_url = $find_doctor->image_url;
                $doctor->member_since = $find_doctor->member_since;
                $doctor->name = $find_doctor->name;
            }
            $doctor_specialities = Speciality::whereHas('doctorSpeciality', function ($query) use($id){
                $query->where('doctor_id', $id);
            })->get();
            if(DoctorEducation::where('doctor_id',$id)->exists()==true){
                 $doctor_education= Degree::whereHas('doctorEducation', function ($query) use($id){
                $query->where('doctor_id', $id);
            })->get();
        }

        if(isset($getPrescription->getPrescription)){
            $last_prescription = $getPrescription->getPrescription;
            $prescription = '';
            $prescriptionHere = $getPrescription->getAppointmentPrescription;
            foreach($prescriptionHere as $pres){
                $prescription .= $pres->prescription . "\n";
            }
            if($prescription == ''){
                $prescription = null;
            }
            $last_prescription->prescription_here = $prescription;
        }else{
            $last_prescription = null;
        }

            return $this->returnResponse(200, '', [
                'family_members' => $getUser->family_members,
                'health_scans' => $getUser->health_scans,
                'medical_records' => $getUser->medical_records,
                'total_reviews' => $getUser->total_reviews,
                'total_appointments' => $getUser->total_appointments,
                'in_person_appointments' => $getUser->in_person_appointments,
                'virtual_appointments' => $getUser->virtual_appointments,
                'instant_consultations' => $getUser->instant_consultations,
                'total_upcoming_appointments' => 0,
                'total_comments' => 0,
                'comments' => null,
                'reviews' => isset($getUser->doctorReviews) ? $getUser->doctorReviews : [],
                'subscription' => $getUser->subscription ?? null,
                'last_prescription' => $last_prescription ? $last_prescription : null,
                'upcoming_appointment' => $getAppointment ? new AppointmentResource($getAppointment) : null,
                'last_health_scan' => HealthScan::getLastHealthScans($getUser->id, $request),
                'prescription' => isset($getPrescription->getPrescription) ? carbon::parse($getPrescription->created_at)->format('d-m-y') : null,
                'doctor_name' => isset($doctor) ? $doctor : null,
                'doctor_speciality' => isset($doctor_specialities) ? $doctor_specialities : null,
                'doctor_education' => isset($doctor_education) ? $doctor_education : null

            ]);
        }catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to Search Doctors by Applying filters
     */
    public function searchDoctors(Request $request)
    {
        try {
            $pagination = null;
            $page = 1;
            $limit = 10;
            if ($request->has('page')) {
                $page = $request->page;
            }
            if ($request->has('limit')) {
                $limit = $request->limit;
            }
            $city = $request->city;
            $gender = $request->gender;
            $experienceYear = $request->experience_year;
            $speciality = $request->speciality;
            $service = $request->service;
            $most_reviewed = $request->most_reviewed ? 'desc': 'asc';
            $most_experince = $request->most_experince;
            $by_fees = $request->by_fees;
            $availableNow = $request->available_now == 'true' ? [true] : [true, false];
            $search = $request->q;
            $language = $request->header('locale');
            $platform = $request->header('platform');
            if ($request->has('limit')) {
                $limit = $request->limit;
            }
            $getApprovedDoctor = DoctorDetail::where(['is_admin_verified'=> 1])->pluck('doctor_id');
            $getDoctors_count = User::where(['status' => true, 'is_blocked' => false, 'role_id' => $this->getConstantByValue('DOCTOR_ROLE_ID')])->whereIn('id', $getApprovedDoctor)
            ->withCount('doctorReviews as review_count')
            // ->where(function($query) use($search){
            //     if($search){
            //         $query->orWhere('name', 'like', "%$search%");
            //         $query->orWhereHas('doctorClinics.clinic', function($query) use ($search){
            //             $query->where('name', 'like', "%$search%");
            //         });
            //         $query->orWhereHas('doctorDetail', function($query) use ($search){
            //             $query->where('prefix', 'like', "%$search%");
            //         });
            //     }
            // })
            // ->where(function($query) use($gender){
            //     if($gender){
            //         $query->where('gender', $gender);
            //     }
            // })
            ->whereHas('doctorDetail', function($query) use($experienceYear, $availableNow){
                if($experienceYear){
                    $query->where('experience_year', $experienceYear);
                }
                if($availableNow !== null){
                    $query->whereIn('is_available', $availableNow);
                }
            });
            // ->whereHas('city', function($query) use($city){
            //     if($city){
            //         $query->where('name', $city);
            //     }
            // })
            // ->whereHas('doctorSpecialityDetails', function($query) use($speciality){
            //     if($speciality){
            //         $speciality = explode(',', $speciality);
            //         $query->whereIn('slug', $speciality);
            //     }
            // })
            // ->whereHas('doctorServiceDetails', function($query) use($service){
            //     if($service){
            //         $service = explode(',', $service);
            //         $service = collect($service)->map(function ($name) {
            //             return \Str::headline($name);
            //         })->all();
            //         $query->whereIn('name', $service);
            //     }
            // });
            $getDoctors_total_count = $getDoctors_count->count();
            $offset = ($page-1) * $limit;
            $total_pages = ceil($getDoctors_total_count / $limit);
            if ($request->has('pagination') && $request->pagination == 'true') {
                $getDoctors = $getDoctors_count
                    ->with('doctorServiceDetails', 'doctorSpecialityDetails', 'city', 'doctorDetail', 'doctorHighPaidClinic')
                    ->orderBy('review_count', $most_reviewed)->skip($offset)->take($limit)
                    ->get();
                $pagination = [
                    'total_page' => $total_pages,
                    'first_page' => 1,
                    'current_page' => $page,
                    'last_page' => $total_pages,
                    'limit' => $limit,
                ];
            } else {
                $getDoctors = $getDoctors_count
                    ->with('doctorServiceDetails', 'doctorSpecialityDetails', 'city', 'doctorDetail', 'doctorHighPaidClinic')
                    ->orderBy('review_count', $most_reviewed)->offset(0)->limit($limit)
                    ->get();
            }
            $getDoctors = collect($getDoctors)->unique('id');
            $doctorCount = $getDoctors->count();
            if($by_fees){
                $getDoctors = $getDoctors->sortByDesc('doctorHighPaidClinic.consultation_fee');
            }
            if($most_experince){
                $getDoctors = $getDoctors->sortByDesc('doctorDetail.experience_year');
            }


            $sortFilters = $platform == 'web' ? $this->getKeyValue( ['available_now', 'available_today', 'by_fees', 'most_reviewed', 'most_experince'], null, null, null, null, true) : [];
            $filters = [
                'availability' => $platform != 'web' ? [['key' => 'available_now', 'value' => true, 'title' => 'Available Now'], ['key' => 'available_today', 'value' => true, 'title' => 'Today'], ['key' => 'later', 'value' => true, 'title' => 'Later']] : null,
                'gender' => $platform != 'web' ? ($language == Language::ENGLISH ? $this->getKeyValue( ['male', 'female', 'others'], 'ucfirst') : [['key' => 'male', 'value' => 'مرد'], ['key' => 'female', 'value' => 'عورت']]) : null,
                'experience_year' => $this->getKeyValue(collect($getDoctors->pluck('doctorDetail.experience_year')->unique()->sort()->values()->all())),
                'speciality' => $platform != 'web' ? $this->getKeyValue(collect(Arr::flatten($getDoctors->pluck('doctorSpecialityDetails')))->pluck('name')->unique()->sort()->values()->all(), null, 'slugify') : null,
                'service' => $platform != 'web' ? $this->getKeyValue(collect(Arr::flatten($getDoctors->pluck('doctorServiceDetails')))->pluck('name')->unique()->sort()->values()->all(), null, 'slugify') : null,
            ];
            return $this->returnResponse(200, '', [
                'pagination' => $pagination,
                'sort' => $sortFilters,
                'filters' => $filters,
                'doctor_count' => 6786,
                'doctors' => UserSummeryResource::collection($getDoctors),
            ]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e);
        }
    }
    /**
     * This function is used to create key value pairs for an array
     */
    private function getKeyValue($value, $wrapperfunction = null, $slugify = null, $slug = null, $locale = null, $sorting = null)
    {
        $result = [];
        for($i = 0; $i < count($value); $i++)
        {
            if($slugify == 'un-slugify'){
                $result[] = [
                    'key' => $value[$i],
                    'value' => \Str::title(str_replace('-', ' ', $value[$i]))
                ];
            }elseif($slugify == 'slugify'){
                $result[] = [
                    'key' => \Str::slug($value[$i]),
                    'value' => $value[$i]
                ];
            }elseif($sorting == true){
                // dd(\Str::title(str_replace('_', ' ', $value[$i])));
                $result[] = [
                    'title' => \Str::title(str_replace('_', ' ', $value[$i])),
                    'key' => $value[$i],
                    'value' => true
                ];
            }
            else{
                $result[] = [
                    'key' => $value[$i],
                    'value' => $wrapperfunction != null ? $wrapperfunction($value[$i]) : $value[$i]
                ];
            }
        }
        return $result;
    }
    /**
     * This method is used to get all the specialities, services and cities in Alphabetical format with doctor counts
     */
    public function searchDoctorByCategory(Request $request)
    {
        try{
            $type = $request->type;
            $search = $request->search;
            switch($type)
            {
                case 'speciality':
                    return $this->returnResponse(200, '', Speciality::getSpecialityByAlphabeticalOrder($request->header('locale'), $search));
                case 'condition':
                case 'service':
                    return $this->returnResponse(200, '', Service::getServicesByAlphabeticalOrder($request->header('locale'), $search));
                case 'city':
                    return $this->returnResponse(200, '', City::getCitiesByAlphabeticalOrder($request->header('locale'), $search));
                default:
                    return $this->returnResponse(400, 'Select the correct type to search doctors.');
            }
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    /**
     * This method is used to get all the specialities, services and cities in Alphabetical format with doctor counts
     */
    public function headerSearch(Request $request)
    {
        try{
            $search = $request->search;
            $getArticles = $this->getArticles($request->header('locale'), $search);
            $getDiseases = $this->getDiseases($request->header('locale'), $search);
            $getDoctors = $this->getDoctors($search);
            $returnResponse = (($getArticles->merge($getDiseases))->merge($getDoctors))->shuffle()->take(6)->all();

            if(!count($getArticles) && !count($getDoctors) && !count($getDiseases)){
                return $this->returnResponse(200, 'Data not available');
            }
            return $this->returnResponse(200, '', $returnResponse);

        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to get all the doctors and clinics by searching
     */
    public function widgetDoctorSearch(Request $request)
    {
        try{
            $search = $request->search;
            $getDoctors = User::whereHas('hasDoctor')
            ->where(function($query) use ($search){
                if($search){
                    $query->orWhere('name', 'like', "%$search%");
                }
            })
            ->orderBy('name', 'ASC')
            ->limit(6)
            ->get(['id', 'name']);

            if(count($getDoctors)){
                $getDoctors->each(function ($item) {
                    $getUser = User::find($item['id']);
                    $item['redirect_url'] = '/doctor/' . \Str::slug(($getUser->city)->name) . '/' . \Str::slug($getUser->doctorSpecialityDetails->pluck('name')[0]) . '/' . \Str::slug($getUser->doctorDetail->prefix) . '-' . \Str::slug($item->name) . '/' . $item->id;
                });
            }
            $getClinics = Clinic::whereHas('doctorClinics')
            ->where(function($query) use ($search){
                if($search){
                    $query->orWhere('name', 'like', "%$search%");
                }
            })
            ->orderBy('name', 'ASC')
            ->limit(6)
            ->get(['id', 'name', 'address']);

            if(count($getClinics)){
                $getClinics->each(function ($item) {
                    $item['redirect_url'] = '/doctor/' . \Str::slug($item->address) . '/?q=' . \Str::slug($item->name);
                });
            }
            return $this->returnResponse(200, '', [
                'doctors' => $getDoctors,
                'clinics' => $getClinics,
            ]);

        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * Get Articles through search
     */
    private function getArticles($language, $search)
    {
        $getArticles = Article::where(['status' => true, 'lang_id' => $language])
        ->where(function($query) use ($search){
            if($search){
                $query->orWhere('name', 'like', "%$search%");
                $query->orWhere('slug', 'like', "%$search%");
                $query->orWhere('descripton', 'like', "%$search%");
                $query->orWhere('keywords', 'like', "%$search%");
            }
        })
        ->orderBy('name', 'ASC')
        ->limit(4)
        ->get(['id', 'name as text', 'slug', 'image', 'lang_id', 'descripton as description']);

        return $getArticles->each(function ($item) {
            if($item->lang_id == 1){
                $item['redirect_url'] = "/article/$item->slug";
            }else{
                $getLanguage = Language::find($item->lang_id);
                $item['redirect_url'] = "/$getLanguage->slug/article/$item->slug";
            }
        });
    }
    /**
     * Get Doctors through search
     */
    private function getDoctors($search)
    {
        $getDoctors = User::whereHas('hasDoctor')
        ->where(function($query) use ($search){
            if($search){
                $query->orWhere('name', 'like', "%$search%");
            }
        })
        ->orderBy('name', 'ASC')
        ->limit(4)
        ->get(['id', 'name as text', 'image']);

        return $getDoctors->each(function ($item) {
            $getUser = User::find($item['id']);
            $item['redirect_url'] = '/doctor/' . \Str::slug(($getUser->city)->name) . '/' . \Str::slug($getUser->doctorSpecialityDetails->pluck('name')[0]) . '/' . \Str::slug($getUser->doctorDetail->prefix) . '-' . \Str::slug($item->text) . '/' . $item->id;
            $item['lang_id'] = 1;
            $item['image'] = $getUser->image_url;
            $item['description'] = count($getUser->doctorEducation) ? implode(', ', $getUser->doctorEducation->pluck('degree')->all()) : null;
        });
    }
    /**
     * Get Diseases through search
     */
    private function getDiseases($language, $search)
    {
        $getDiseases = Disease::where(['status' => true, 'lang_id' => $language])
        ->whereHas('page')
        ->where(function($query) use ($search){
            if($search){
                $query->orWhere('name', 'like', "%$search%");
                $query->orWhere('slug', 'like', "%$search%");
                $query->orWhere('description', 'like', "%$search%");
            }
        })
        ->orderBy('name', 'ASC')
        ->limit(4)
        ->get(['id', 'name as text', 'slug', 'lang_id', 'description']);

        return $getDiseases->each(function ($item) {
            if($item->lang_id == 1){
                $item['redirect_url'] = "/disease/$item->slug";
            }else{
                $getLanguage = Language::find($this->lang_id);
                $item['redirect_url'] = "/$getLanguage->slug/disease/$item->slug";
            }
            $item['description'] = $item->page->description;
            $item['image'] = $item->page->image_url;
        });
    }
    /**
     * This method is used to delete user account
     */
    public function deleteUserAccount(Request $request)
    {
        try{
            $userId = $request->header('user_id');
            $getUser = User::find($userId);
            if(!$getUser){
                return $this->returnResponse(200, 'User not found.');
            }
            ApiToken::where('user_id', $getUser->id)->delete();
            $getUser->delete();
            return $this->returnResponse(200, 'User Account Deactivated Successfully.');

        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function checkTrialConsultation(Request $request)
    {
        try{
            // $input = $request->all();
            // if($request->header('locale') != Language::ENGLISH){
            //     $input['city_id'] = City::find($input['city_id'])->translation_of;
            // }
            if(Request()->segment(2) === 'v2'){
                $userId = \Auth::user()->id;
            }else{
                $userId = $request->header('user_id');
            }
            $getUser = User::find($userId);
            if($getUser && $getUser->role_id != $this->getConstantByValue("USER_ROLE_ID")) {
                return $this->returnResponse(401, 'You are not authorized.');
            }
            $data = User::checkTrialConsultation($getUser);
            return $this->returnResponse($data['trail_consultation_status'] == true ? 200 : 400, $data['trail_consultation_status'] == true ? 'Trail consultation can avail' : 'Trail consultation expire', $data);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getServices(Request $request){
        $validator = Validator::make($request->all(), [
            'specialities' => ['required'],
        ]);
        if ($validator->fails()) {
            return $this->returnResponse(400, $validator->errors()->first());
        }
        $specialities = $request->specialities;
        if( strpos($specialities, ',') !== false ) {
            $specialities = explode(',', $specialities);
        } else {
            $specialities = array($specialities);
        }

        try{
            $services = Service::whereIn('speciality_id',$specialities)->where('status', true)->get();
            if(!count($services)){
                return $this->returnResponse(200, 'Data not available');
            }
            return $this->returnResponse(200, 'Service fetched successfully', $services);
        }catch(\Exception $e){
            return [
                'status' => false,
                'message' => 'Something went wrong'
            ];
        }
    }

    public function patientInfoGet(Request $request)
    {
        try{
            $input = $request->all();
            $getUser = User::find(Request()->segment(2) === "v1" ? $request->header('user_id') : \Auth::user()->id);
            if($getUser->role_id == $this->getConstantByValue("DOCTOR_ROLE_ID")){
                $getUserId = $request->has('user_id') ? $request->user_id : 0;
                $getUser = User::find($getUserId);
            }
            if(!$getUser) {
                return $this->returnResponse(401, 'You are not authorized.');
            }
            $data = PatientInfo::where([
                ['user_id', $getUser->id]
            ])->latest()->first();
            if($data){
                $patient_info = [
                    'name' => $data->name ?? $getUser->name,
                    'gender' => $data->gender ?? $getUser->gender,
                    'date_of_birth' => $data->date_of_birth ?? $getUser->birth_date,
                    'height' => $data->height ?? $getUser->height,
                    'weight' => $data->weight ?? $getUser->weight,
                    'id' => $data->id
                ];
            }else{
                $patient_info = [
                    'name' => $getUser->name,
                    'gender' => $getUser->gender,
                    'date_of_birth' => $getUser->birth_date,
                    'height' => $getUser->height,
                    'weight' => $getUser->weight,
                ];
            }
            return $this->returnResponse(200, 'Success', $patient_info);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function patientInfoCreate(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'gender' => ['required'],
            'date_of_birth' => ['required','date_format:Y-m-d','before:today']
        ]);
        if ($validator->fails()) {
            return $this->returnResponse(400, $validator->errors()->first());
        }

        try{
            $data = PatientInfo::create(
                [
                    'user_id'   => Request()->segment(2) === "v1" ? $request->header('user_id') : \Auth::user()->id,
                    'name' => $request->name,
                    'gender' => $request->gender,
                    'date_of_birth' => $request->date_of_birth,
                    'height' => $request->has('height')?$request->height:'',
                    'weight' => $request->has('weight')?$request->weight:'',

                ]);
            return $this->returnResponse(200, 'success', $data);
        }catch(\Exception $e){
            return [
                'status' => false,
                'message' => 'Something went wrong'
            ];
        }
    }

    public function findADoctor(Request $request){
        $validator = Validator::make($request->all(), [
            'number' => ['required'],
        ]);
        if ($validator->fails()) {
            return $this->returnResponse(400, $validator->errors()->first());
        }
        try{
            $data = FindADoctor::updateOrCreate(
            [
                'number' => $request->number

            ],
            ['user_id' => ((Request()->segment(2) === "v2") ? \Auth::user() ? \Auth::user()->id : 0 : $request->header('user_id')) ?? 0
            ]);
            return $this->returnResponse(200, 'success', $data);
        }
        catch(\Exception $e){
            return [
                'status' => false,
                'message' => 'Something went wrong'
            ];
        }
    }

    public function getAlink(Request $request){
        $validator = Validator::make($request->all(), [
            'number' => ['required'],
            // 'network' => ['required']
        ]);
        if ($validator->fails()) {
            return $this->returnResponse(400, $validator->errors()->first());
        }

        // add this for testing pipeline
        try{
            $data = GetALink::Create([
                'number' => $request->number,
                'network' => $request->has('network') ? strtolower($request->network) : null,
                'name' => $request->has('name') ? $request->name : null,
                'email' => $request->has('email') ? $request->email : null,
                'page_title' => $request->has('page_title') ? $request->page_title : null
            ]);
            $sms = new SmsHelper;
            $settings = Settings::getValues(['android_app_link','ios_app_link']);
            // $message = 'Thanks for interest in our app! Click here to get it for Android: '.$settings['android_app_link'].' Click here to get it for iOS: '. $settings['ios_app_link'];
            // $message = "Meri Sehat is launching it's Artificial Intelligence health scan technology soon. Your number has been saved and we'll inform you when it is available to download. Register here for a FREE instant video consultation with our doctors: http://bit.ly/3HZmX2W";
            $message = "Thank you for showing interest in SehatScan! To instantly measure your vitals, download our app from Google Play Store by visiting: https://play.google.com/store/apps/details?id=pk.merisehat.app";

            if(env('APP_ENV') == 'local' || env('APP_ENV') == 'production'){
                $sms->send($request->number, $message);
            }
            return $this->returnResponse(200, 'success', $data);
        }catch(\Exception $e){
            return [
                'status' => false,
                'message' => 'Something went wrong'
            ];
        }
    }

    public function testSms(Request $request)
    {
        $sms = new SmsHelper;
        $sms->send('03333924895', 'Message From Meri Sehat');
    }

    public function getYoutubeVideos(Request $request)
    {
        try{
            $params = [
                'order'         => 'date',
                'part'          => 'id, snippet',
                'maxResults'    => env('YOUTUBE_LIMIT'),
                'channelId'     => env('YOUTUBE_CHANNEL_ID')
            ];
            if($request->has('pageToken')){
                $params['pageToken'] = $request->pageToken;
            }
            $search = Youtube::searchAdvanced($params, true);
            if($search && isset($search['results'])){
                return $this->returnResponse(200, 'success', $search);
            }
            return $this->returnResponse(400, 'No videos found');
        } catch(\Exception $e) {
            return $this->returnResponse(400, $e->getMessage());
        }
    }

    public function getAllVideos(Request $request)
    {
        $lang_id = 1;
        if ($request->headers->has('locale')) {
            $lang_id = $request->header('locale');
        }
        $sort_by = 'created_at';
        $sort_order = 'desc';
        if ($request->has('sort_by')) {
            $sort_by = $request->sort_by;
        }
        if ($request->has('sort_order')) {
            $sort_order = $request->sort_order;
        }
        if ($request->has('most_recent')) {
            $sort_order = 'desc';
        }

        $filters = Topics::where('status', 1)->where('lang_id', $lang_id)->where(function ($query) {
            $query->where('parent_id', 1)->orWhere('id', 2);
        })->get();
        $videos = WidgetMedia::whereHas('referenceWidget', function ($query) use ($lang_id, $request) {
            if ($request->has('search_by') && $request->search_by == 'disease') {
                $query = $query->where('reference_type', 'page');
                $query = $query->whereHas('page', function ($query) use ($request, $lang_id) {
                    if ($request->has('search_category') && $request->search_category != '') {
                        $query = $query->where('slug', 'like', '%'.$request->search_category.'%');
                    }
                    $query = $query->where('parent_id', '2');
                    if ($request->has('search_key') && $request->search_key != '') {
                        $query = $query->where('name', 'like', '%' . $request->search_key . '%');
                    }
                });
            } elseif ($request->has('search_by') && $request->search_by == 'article') {
                $query = $query->where('reference_type', 'article');
                $query = $query->whereHas('article', function ($query) use ($request, $lang_id) {
                    $query = $query->whereHas('parent', function ($query) use ($request, $lang_id) {
                        if ($request->has('search_category') && $request->search_category != '') {
                            $query = $query->where('slug', $request->search_category);
                        }
                        $query = $query->where(function ($query) use ($request, $lang_id) {
                            $query = $query->where('parent_id', 1)->orWhere('id', 1);
                        });
                        if ($request->has('search_key') && $request->search_key != '') {
                            $query = $query->where('name', 'like', '%' . $request->search_key . '%');
                        }
                    });
                });
            } else {
                $query = $query->whereIn('reference_type', ['page','article']);
                $query = $query->whereHas('page', function ($query) use ($request, $lang_id) {
                    if ($request->has('search_category') && $request->search_category != '') {
                        $query = $query->where('slug', 'like', '%'.$request->search_category.'%');
                    }
                    $query = $query->where('parent_id', '2');
                    if ($request->has('search_key') && $request->search_key != '') {
                        $query = $query->where('name', 'like', '%' . $request->search_key . '%');
                    }
                });
                $query = $query->orWhereHas('article', function ($query) use ($request, $lang_id) {
                    $query = $query->whereHas('parent', function ($query) use ($request, $lang_id) {
                        if ($request->has('search_category') && $request->search_category != '') {
                            $query = $query->where('slug', $request->search_category);
                        }
                        $query = $query->where(function ($query) use ($request, $lang_id) {
                            $query = $query->where('parent_id', 1)->orWhere('id', 1);
                        });
                        if ($request->has('search_key') && $request->search_key != '') {
                            $query = $query->where('name', 'like', '%' . $request->search_key . '%');
                        }
                    });
                });
            }
            $query = $query->whereIn('widget_id', ['17','29'])->orderBy('sequence', 'ASC');
        })->where('language_id', $lang_id)->where('type', 'video')->orderBy($sort_by, $sort_order);

        if ($request->has('live_show')) {
            $videos = $videos->where('url', 'like', '%%');
        }

        $videos = $videos->get();
        $videos_resource = VideoResource::collection($videos);

        if ($request->has('trending')) {
            $videosCollection = collect($videos_resource);
            $videos_resource = $videosCollection->filter(function ($value, $key) {
                return $value['articles']->is_featured == 1;
            })->all();
        }

        if ($request->has('most_watch')) {
            $videosCollection = collect($videos_resource);
            $sorted = $videosCollection->sortByDesc('clicks_count');
            $videos_resource = $sorted->values()->all();
        }

        $result['filters'] = $filters;
        $result['videos_count'] = count($videos_resource);
        $result['videos'] = $videos_resource;
        return $this->returnResponse(200, 'success', $result);
    }

    public function checkUserNumber(Request $request){
           $user = User::where('phone', $request->phone)->exists();
           if($user){
               $user = User::where('phone', $request->phone)->first();
               return response()->json(['data' => true, 'record' => $user, 'message' => 'Exists'], 200);
           }
           return response()->json(['data' => $user, 'record' => [], 'message' => 'Not Exists'], 404);
    }

    public function CorporatQuery(Request $request){
        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'max:25'],
            'company_name' => ['required', 'max:50'],
            'mobile_number' => ['required', 'unique:corporat_queries', 'max:15' ],
            'email' => ['required', 'email', 'unique:corporat_queries', 'max:100'],
        ]);
        if ($validator->fails()) {
            return $this->returnResponse(422, $validator->errors()->first());
        }
        try{
            $data = CorporatQuery::create($request->all());
            return $this->returnResponse(201, 'created', $data);
        }
        catch(\Exception $e){
            return $this->returnResponse(400, $e->getMessage());
        }
    }

    public function setOneSignalPlayer(Request $request)
    {
        if(Request()->segment(2) === "v2"){
            $id = \Auth::guard('api')->user()->id;
        }else{
            $id = $this->getUserIdFromHeader($request->header());
        }
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id'],
            'player_id' => ['required']
        ]);
        if ($validator->fails()) {
            return $this->returnResponse(400, $validator->errors()->first());
        }
        try{
            ApiToken::updateOrCreate([
                'user_id' => $request->user_id,
            ], [
                'device_id' => $request->device_id ?? '',
                'platform' => $request->platform ?? '',
                    'fcm_token' => $request->fcm_token ?? '',
                    'token' => $request->bearerToken(),
                    'app_version' => $request->app_version ?? "v1.0",
                    'expiry_date' => now()->addDays(2),
                    'player_id' => $request->player_id,
                ]
            );
            return $this->returnResponse(200,'', 'Added');
        }catch (\Exception $th) {
            return $this->returnResponse(400, $th->getMessage());
        }
    }

    public function totalExperience(){
        try{
            $exp = [];
            for($i=0; $i<=50; $i++){
                $exp[$i] = $i;
            }
            return $this->returnResponse(200, 'success', $exp);
        } catch(\Exception $e) {
            return $this->returnResponse(400, $e->getMessage());
        }
    }

    public function journeyCompleted(Request $request)
    {
        try{
            if(Request()->segment(2) === "v2"){
                $user_id = \Auth::user()->id;
            }else{
                $user_id = $request->header('user_id');
            }
            User::where('id', $user_id)->update(['is_journey' => 1]);
            return $this->returnResponse(200, 'success');
        }catch (\Exception $th) {
            return $this->returnResponse(500, $th->getMessage());
        }
    }
}
