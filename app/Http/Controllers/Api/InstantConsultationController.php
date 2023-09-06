<?php   namespace App\Http\Controllers\Api;

use App\Http\{Common\Queue, Common\agora\RtcTokenBuilder, Common\Constant, Controllers\Controller,  Common\agora\RtmTokenBuilder};
use App\Models\{Appointment, DoctorDetail, User, PreAppointment, PromoCode, Transaction, UserPromoCode,
    UserSubscription, CancelledAppointment, PatientInfo, ReasonForVisit, Settings
};
use Carbon\Carbon;
use Illuminate\{Http\Request, Support\Facades\Auth};
use Jenssegers\Agent\Agent;
use App\Http\Resources\User\AppointmentResource;
use Illuminate\Support\Facades\File;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as mPDF;

class InstantConsultationController extends Controller
{
    /**
     * TThis method is used to create queue management for Instant Consultation
     */
    public function startConsultaion(Request $request){

        /////////////////////// Check Appoint Type if Schedule then Return null ///////////////////////
        if($request->headers->has('app-type') && $request->headers->get('app-type') == 'schedule'){
            $getAppointment = Appointment::where(['id' => $request->headers->get('appointment-id')])->first();
            if($getAppointment){
                $data = [
                    'redirect' => true,
                    //'appointment_id' => $request->header('app-id'),
                    'is_doctor_connected' => $getAppointment->is_doctor_connected,
                    'is_waiting' => false,
                    'waiting_time' => 0,
                ];
                return $this->returnResponse(200, 'Call started.',$data);
            }
            return $this->returnResponse(400, "Invalid appointment id provided");
        }
        /////////////////////// END Check Appoint Type if Schedule then Return null ///////////////////////
        // TODO::Check if user has membership
        if(Request()->segment(2) === "v2"){
            $user_id = \Auth::user()->id;
        }else{
            $user_id = $request->header('user_id');
        }
        $start_type = $request->start_type;
        $promo_code = $request->promo_code;
        $doctors = [];
        $appointments = [];
        $is_subscription = null;
        $subscription_id = null;
        $getUserDetail = User::with('subscription')->find($user_id);
        if($start_type === 'subscription'){
            if($getUserDetail->subscription && $getUserDetail->subscription->receipt_data){
                $checkAvailableConsultation = json_decode($getUserDetail->subscription->receipt_data);
                if(property_exists($checkAvailableConsultation, 'free_video_consults') && property_exists($checkAvailableConsultation, 'consume_free_video_consults')){
                    if($checkAvailableConsultation->free_video_consults != 'unlimited'){
                        if($checkAvailableConsultation->free_video_consults <= $checkAvailableConsultation->consume_free_video_consults){
                            return $this->returnResponse(400, 'You have avail all your DoctorNow consultation available in package.');
                        }
                    }
                    $is_subscription = 'subscription';
                    $subscription_id = $getUserDetail->subscription->subscription_id;
                }else{
                    return $this->returnResponse(400, 'Please pay DoctorNow consultation fee or buy a subscription.');
                }
            }else{
                return $this->returnResponse(400, 'Please pay DoctorNow consultation fee or buy a subscription.');
            }
        }elseif($start_type === 'transfer'){
            $getUserTransaction = Transaction::where([
                ['reference_type', 'one_time'],
                ['user_id', $user_id],
                ['status', 1],
                ['is_avail', 0],
            ])->orderBy('created_at', 'desc')->first();

            if(!$getUserTransaction){
                return $this->returnResponse(400, 'Please pay Doctor Now consultation fee or buy a subscription.');
            }
            $is_subscription = 'one_time_payment';
        }elseif($start_type === 'free_trail'){
            if($getUserDetail){
                if($getUserDetail->trial_consultation <= 0){
                    return $this->returnResponse(400, 'Your free trial has expired.');
                }else{
                    $difference = Carbon::now()->diffInDays(Carbon::parse($getUserDetail->created_at));
                    if($difference > 30){
                        return $this->returnResponse(400, 'Your free trial has expired.');
                    }
                    $is_subscription = 'free_trail';
                }
            }else{
                return $this->returnResponse(400, 'Something went wrong.');
            }
        }elseif($start_type === 'promo_code'){
            $checkUserPromoCode = UserPromoCode::where([
                ['user_id', $user_id],
                ['status', 1]
            ])->first();
            if($checkUserPromoCode){
                $getPromoCode = PromoCode::where([
                    ['status', 1],
                    ['expire_date', '>=', Carbon::now()]
                ])->find($checkUserPromoCode->id);
                if($getPromoCode){
                    if($getPromoCode->code == $promo_code){
                        if($checkUserPromoCode->no_of_consultation_avail < $getPromoCode->no_of_consultation){
                            $is_subscription = $promo_code;
                        }else{
                            return $this->returnResponse(400, 'You have availed all your consultations against this promo code.');
                        }
                    }else{
                        return $this->returnResponse(400, 'Invalid promo code');
                    }
                }else{
                    return $this->returnResponse(400, 'No Promo Code found.');
                }
            }else{
                return $this->returnResponse(400, 'No Promo Code found.');
            }
        }

        $check_appointment = Appointment::where('user_id', $user_id)->where('action_by', $user_id)->where('type', Constant::APPOINTMENT_TYPE_INSTANT)->where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->first();
        if($check_appointment){
            $getDoctors = DoctorDetail::where(['doctor_id' => $check_appointment->doctor_id])
            ->with(['user' => function ($query) {
                $query->where('role_id', 3)->where('phone', '!=', '03291112223')->select('id', 'name');
            }])
            ->select(['id','doctor_id','current_appointment','last_call_at'])->orderBy('assigned_calls', 'asc')->get();
            $appointment = $this->create_appointment($getDoctors, $user_id, $is_subscription, $subscription_id);
        }else{
            if($user_id == 678){
                $agent = new Agent();
                $platform = "Platform:" . $agent->platform() . "ServerUserAgent:" . $_SERVER['HTTP_USER_AGENT'];
                $getDoctorId = User::where('phone', '03291112223')->first();
                $created_appointment = Appointment::create([
                    'user_id' => $user_id,
                    'action_by' => $user_id,
                    'type' => Constant::APPOINTMENT_TYPE_INSTANT,
                    'booked_via_subscription' => 'free_trail',
                    'progress' => (new Constant)->APPOINTMENT_STATUS_PENDING,
                    'date' => Carbon::now()->format('Y-m-d'),
                    'time' => Carbon::now()->format('H:i'),
                    'user_agent' => $platform,
                    'doctor_id' => $getDoctorId->id
                ]);
            }
            $getDoctors = DoctorDetail::where(['is_instant_consultation' => 1])
            ->with(['user' => function ($query) {
                $query->where('role_id', 3)->where('phone', '!=', '03291112223')->select('id', 'name');
            }])
            ->select(['id','doctor_id','current_appointment','last_call_at'])->orderBy('assigned_calls', 'asc')->get();
            if(!count($getDoctors) && $user_id != 678){
                return $this->returnResponse(400, 'Sorry, at this time there is no doctor available for Doctor Now consultation.');
            }
            if(count($getDoctors) != 0 && isset($getDoctors) && isset($getDoctors[0]) && isset($getDoctors[0]['doctor_id'])){
                $check = Appointment::where('doctor_id', $getDoctors[0]['doctor_id'])->where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->count();
                $getTotalAppointment = Appointment::where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->count();
                if($check > 0 && $getTotalAppointment < count($getDoctors)){
                    $getDoctors = DoctorDetail::where(['is_instant_consultation' => 1])
                    ->with(['user' => function ($query) {
                        $query->where('role_id', 3)->where('phone', '!=', '03291112223')->select('id', 'name');
                    }])
                    ->select(['id','doctor_id','current_appointment','last_call_at'])->get();
                }
            }
            $checkDoctor = $this->checkDoctor($getDoctors, $user_id, $is_subscription, $subscription_id);
            if(!is_int($checkDoctor)){
                return $checkDoctor;
            }
            if($checkDoctor > 0){
                $appointment = $this->create_appointment($getDoctors, $user_id, $is_subscription, $subscription_id);
            }else{
                $appointment = $this->pre_appointment($getDoctors, $user_id, $is_subscription, $subscription_id);
            }
        }


        return $appointment;
    }
    /**
     * This method is used to pre appointment
    */

    private function pre_appointment($getDoctors, $user_id, $is_subscription, $subscription_id){
        $check = Appointment::where('user_id', $user_id)->where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->where('type', Constant::APPOINTMENT_TYPE_INSTANT)->count();
        if($check){
            return $this->create_appointment($getDoctors, $user_id, $is_subscription, $subscription_id);
        }
        $created_appointment = PreAppointment::where('user_id', $user_id)->first();
        if(!$created_appointment){
            $created_appointment = PreAppointment::create([
                'user_id' => $user_id,
                'progress' => (new Constant)->APPOINTMENT_STATUS_PENDING,
                'action_by' => $user_id,
                'type' => Constant::APPOINTMENT_TYPE_INSTANT,
                'booked_via_subscription' => $is_subscription,
                'subscription_id' => $subscription_id,
                'consultation_fee' => 0,
                'date' => Carbon::now()->format('Y-m-d'),
                'time' => Carbon::now()->format('H:i'),
            ]);
        }
        $getAppointments = PreAppointment::where(['type' => 'instant-consultation', 'progress' => (new Constant)->APPOINTMENT_STATUS_PENDING])
        ->with(['user' => function ($query) {
            $query->select('id', 'name');
        }])
        ->select(['id','user_id','created_at'])->get();
        $existingAppointment = [];

        foreach ($getDoctors as $key => $doctor){
            if(!isset($doctor->user->name)){
                continue;
            }
            $doctors[] = [
                'id' => $doctor->doctor_id,
                'title' => $doctor->user->name,
                'lastCall' => $doctor->last_call_at,
                'appointmentId' => $doctor->current_appointment,
            ];
            $existingAppointment[] = $doctor->current_appointment;
        }

        foreach ($getAppointments as $key => $appointment){
            $appointments[] = [
                'id' => $appointment->id,
                'name' => $appointment->user ? $appointment->user->name : null,
                'joinCallAt' => $appointment->created_at,
            ];
        }
        if($this->calculateWaitingTime(0, $user_id) == null){
            return $this->returnResponse(400, 'Searching for the best available doctors online......');
        }

        return $this->returnResponse(200, 'Searching for the best available doctors online......', [
            'redirect' => in_array($created_appointment->id, $existingAppointment) ? true : false,
            'appointment_id' => 0,
            'is_waiting' => in_array($created_appointment->id, $existingAppointment) ? false : true,
            'waiting_time' => $this->calculateWaitingTime(0, $user_id),
            'existing_appointments' => $existingAppointment,
            'is_doctor_connected' => $created_appointment['is_doctor_connected'],
            'remaining_time' => $this->calculateRemainingTime(0, Constant::APPOINTMENT_INSTANT_TIME, $user_id),
            'is_canceled' => true
        ]);
    }

    private function create_appointment($getDoctors, $user_id, $is_subscription, $subscription_id, $pre_appointment_id = 0){
        $doctors = [];
        $appointments = [];

        $check_appointment = Appointment::where('user_id', $user_id)->where('action_by', $user_id)->where('type', Constant::APPOINTMENT_TYPE_INSTANT)->where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->first();

        if($check_appointment){
            $created_appointment = $check_appointment;
        }else{
            $checkPreAppointment = PreAppointment::where([
                ['progress', (new Constant)->APPOINTMENT_STATUS_PENDING],
                ['user_id', $user_id],
                ['booked_via_subscription', '!=', NULL]
            ])->first();
            $agent = new Agent();
            $platform = "Platform:" . $agent->platform() . "ServerUserAgent:" . $_SERVER['HTTP_USER_AGENT'];
            if($checkPreAppointment){
                $created_appointment = Appointment::create([
                    'user_id' => $checkPreAppointment->user_id,
                    'action_by' => $checkPreAppointment->user_id,
                    'type' => Constant::APPOINTMENT_TYPE_INSTANT,
                    'booked_via_subscription' => $checkPreAppointment->is_subscription,
                    'subscription_id' => $checkPreAppointment->subscription_id,
                    'progress' => (new Constant)->APPOINTMENT_STATUS_PENDING,
                    'date' => Carbon::now()->format('Y-m-d'),
                    'time' => Carbon::now()->format('H:i'),
                    'user_agent' => $platform
                ]);
                $patient_info = PatientInfo::where([
                    ['user_id',$checkPreAppointment->user_id]
                ])->latest()->first();
                if($patient_info){
                    $patient_info->update(['appointment_id' => $created_appointment->id]);
                }

            }else if($is_subscription){
                $created_appointment = Appointment::create([
                    'user_id' => $user_id,
                    'action_by' => $user_id,
                    'type' => Constant::APPOINTMENT_TYPE_INSTANT,
                    'booked_via_subscription' => $is_subscription,
                    'subscription_id' => $subscription_id,
                    'progress' => (new Constant)->APPOINTMENT_STATUS_PENDING,
                    'date' => Carbon::now()->format('Y-m-d'),
                    'time' => Carbon::now()->format('H:i'),
                    'user_agent' => $platform
                ]);
                $patient_info = PatientInfo::where([
                    ['user_id',$user_id]
                ])->latest()->first();
                if($patient_info){
                    $patient_info->update(['appointment_id' => $created_appointment->id]);
                }
            }else{
                $check_appointment = Appointment::where([
                    ['user_id', $user_id],
                    ['type', Constant::APPOINTMENT_TYPE_INSTANT],
                    ['updated_at', '>', Carbon::now()->subMinutes(5)->toDateTimeString()]
                ])->latest()->first();
                if($check_appointment){
                    if($check_appointment->progress === 'cancel'){
                        return $this->returnResponse(400, 'System cancelled an appointment because doctor did not start the call.', ['progress' => (new Constant)->APPOINTMENT_STATUS_CANCELLED]);
                    }
                }
                return $this->returnResponse(400, 'Something went wrong. Sorry for inconvenience.');
            }
        }
        if($pre_appointment_id != 0){
            PreAppointment::find($pre_appointment_id)->delete();
        }
        PreAppointment::where('user_id', $user_id)->delete();

        $getAppointments = Appointment::where(['type' => 'instant-consultation', 'progress' => (new Constant)->APPOINTMENT_STATUS_PENDING])
        ->with(['user' => function ($query) {
            $query->select('id', 'name');
        }])
        ->select(['id','user_id','created_at'])->get();
        $existingAppointment = [];
        foreach ($getDoctors as $key => $doctor){
            if(!isset($doctor->user->name)){
                continue;
            }
            $getDoctorAppointment = Appointment::where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->where('type', Constant::APPOINTMENT_TYPE_INSTANT)->where('doctor_id', $doctor->doctor_id)->count();
            if($getDoctorAppointment >= 2){
                continue;
            }
            $doctors[] = [
                'id' => $doctor->doctor_id,
                'title' => $doctor->user->name,
                'lastCall' => $doctor->last_call_at,
                'appointmentId' => $doctor->current_appointment,
            ];
            $existingAppointment[] = $doctor->current_appointment;
        }

        foreach ($getAppointments as $key => $appointment){
            $appointments[] = [
                'id' => $appointment->id,
                'name' => $appointment->user ? $appointment->user->name : null,
                'joinCallAt' => $appointment->created_at,
            ];
        }

        $doctors = json_encode($doctors,true);
        $appointments = json_encode($appointments,true);

        $queue = new Queue($doctors, $appointments);
        $queue = $queue->distribute();
        if(isset($queue) && $queue['assigned_doctors'] != false && $queue['assigned_doctors'][0]){
            $assigned_doctor = $queue['assigned_doctors'][0];
            DoctorDetail::where('id', $assigned_doctor['id'])->update([
                'last_call_at' => Carbon::parse($assigned_doctor['lastCall'])->toDateTimeString(),
                'current_appointment' => $assigned_doctor['appointmentId'],
            ]);
            if($created_appointment->doctor_id == null){
                $createdAppointmentUser = User::find($created_appointment->user_id);
                if(isset($createdAppointmentUser) && in_array($createdAppointmentUser->phone, ["033339248951", "03313468086"])){
                    $getDoctorId = User::where('phone', '03291112223')->first();
                    Appointment::where('id', $created_appointment->id)->update(['doctor_id' => $getDoctorId->id]);

                }else{
                    if($created_appointment->doctor_id == null ){
                        Appointment::where('id', $created_appointment->id)->update(['doctor_id' => $assigned_doctor['id']]);
                        DoctorDetail::where('doctor_id', $assigned_doctor['id'])->increment('assigned_calls');
                    }
                }
            }
            if($assigned_doctor['appointmentId'] == $created_appointment->id && $created_appointment->user_id == $user_id){
                $data = [
                    'redirect' => true,
                    'appointment_id' => $created_appointment->id,
                    'is_waiting' => false,
                    'waiting_time' => 0,
                    'remaining_time' => $this->calculateRemainingTime($created_appointment->id ,Constant::APPOINTMENT_INSTANT_TIME, $user_id),
                    'is_doctor_connected' => $created_appointment->is_doctor_connected,
                    'is_canceled' => false
                ];
                return $this->returnResponse(200, 'Your doctor will be connecting shortly',$data);
            }
        }
        if(isset($queue) && $queue['appointments'] && count($queue['appointments']) > 0){
            foreach ($queue['appointments'] as $appointment){
                if($appointment['id'] == $created_appointment->id  && $created_appointment->user_id == $user_id){
                    $waiting_time = $this->calculateWaitingTime($created_appointment->id, $user_id);
                    $message = $waiting_time == 0 ? 'Your doctor will be connecting shortly' : "<b>Success - Your appointment is confirmed!</b><br/>Our doctor shall be with you shortly";
                    return $this->returnResponse(200, $message, [
                        'redirect' => in_array($created_appointment->id, $existingAppointment) ? true : false,
                        'appointment_id' => $created_appointment->id,
                        'is_waiting' => in_array($created_appointment->id, $existingAppointment) ? false : true,
                        'waiting_time' => $waiting_time,
                        'existing_appointments' => $existingAppointment,
                        'is_doctor_connected' => $created_appointment['is_doctor_connected'],
                        'remaining_time' => $this->calculateRemainingTime($created_appointment->id, Constant::APPOINTMENT_INSTANT_TIME, $user_id),
                        'is_canceled' => false
                    ]);
                }
            }
        }

        return $queue;
    }


    private function checkDoctor($getDoctors, $user_id, $is_subscription, $subscription_id){
        $check = Appointment::where('user_id', $user_id)->where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->where('type', Constant::APPOINTMENT_TYPE_INSTANT)->count();
        if($check){
            return $this->create_appointment($getDoctors, $user_id, $is_subscription, $subscription_id);
        }

        $doctorCount = 0;
        $getDoctors = DoctorDetail::where(['is_instant_consultation' => 1])
        ->with(['user' => function ($query) {
            $query->where('role_id', 3)->select('id', 'name');
        }])
        ->select(['id','doctor_id','current_appointment','last_call_at'])->orderBy('assigned_calls', 'asc')->get();
        if($getDoctors){
            foreach ($getDoctors as $key => $doctor){
                if(!isset($doctor->user->name)){
                    continue;
                }
                $getDoctorAppointment = Appointment::where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->where('type', Constant::APPOINTMENT_TYPE_INSTANT)->where('doctor_id', $doctor->doctor_id)->count();
                if($getDoctorAppointment >= 2){
                    continue;
                }
                $doctors[] = [
                    'id' => $doctor->doctor_id,
                    'title' => $doctor->user->name,
                    'lastCall' => $doctor->last_call_at,
                    'appointmentId' => $doctor->current_appointment,
                ];

                if(!isset($doctor->current_appointment) or empty($doctor->current_appointment) )
                {
                    $doctorCount = $doctorCount + 1;
                }
            }
        }
        return $doctorCount;
    }
    /**
     * This method is used to send Agora link and channel
     */
    public function generateAgoraLink(Request $request)
    {
        try{
            if(!$request->appointment_id){
                return $this->returnResponse(400, "Invalid appointment id provided");
            }
            $getAppointment = Appointment::find($request->appointment_id);
            if(Request()->segment(2) === "v2"){
                $user_id = \Auth::user()->id;
            }else{
                $user_id = $request->header('user-id');
            }
            if($getAppointment->user_id != $user_id && $getAppointment->doctor_id != $user_id){
                return $this->returnResponse(400, "You are not authorized.");
            }

            if($getAppointment->doctor_id == $user_id){
                if($getAppointment->call_started){
                    $getAppointment->update(['is_doctor_connected' => 1]);
                }else{
                    $getAppointment->update(['is_doctor_connected' => 1, 'call_started' => Carbon::now()]);
                }
            }
            if($getAppointment->agora_link){
                return $this->returnResponse(200, "", [
                    'agora_token' => $getAppointment->agora_link,
                    'channel_name' => "Appointment-" . $getAppointment->id,
                    'remaining_time' => $this->calculateRemainingTime($getAppointment->id, Constant::APPOINTMENT_INSTANT_TIME, $getAppointment->user_id)
                ]);
            }
            $agoraDetails = $this->token($request->appointment_id);
            $getAppointment->update(['agora_link' => $agoraDetails['agora_token']]);

            return $this->returnResponse(200, "", [
                'agora_token' => $agoraDetails['agora_token'],
                'channel_name' => $agoraDetails['channnel_name'],
                'remaining_time' => $this->calculateRemainingTime($getAppointment->id, Constant::APPOINTMENT_INSTANT_TIME, $getAppointment->user_id)
            ]);
        }catch(\Exception $e){
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    // --------------------------- Agora Genrate Link Start ------------------------
    private function token($getRoom){
        $channelName = "Appointment-" . $getRoom;
        $channelToken = $this->processToken($channelName);
        return [
            'agora_token' => $channelToken,
            'channnel_name' => $channelName
        ];
    }

    private function processToken($channelName){
        $rtcBuilder = new RtcTokenBuilder();
        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $uidStr = "0";
        $role = $rtcBuilder::RolePublisher;
        // $expireTimeInSeconds = 2592000; // expires after a month
        $expireTimeInSeconds = 86400; // expires after a day

        $currentTimestamp = (new \DateTime("now", new \DateTimeZone('UTC')))->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = $rtcBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $uidStr, $role, $privilegeExpiredTs);
        return $token;
    }
    // --------------------------- Agora Genrate Link End ------------------------

    private function calculateWaitingTime($appointment_id, $user_id){
        $call_started_diff = 0;
        if($appointment_id === 0){
            $numberOfAvailableDoctor = $this->availableDoctor();
            if($numberOfAvailableDoctor == 0){
                return null;
            }

            $getAllPreAppointments = PreAppointment::orderBy('id', 'ASC')->get()->pluck('user_id');
            $getUserAppointment = PreAppointment::where('user_id', $user_id)->first();
            if($getUserAppointment){
                if($getUserAppointment->created_at){
                    $start  = new Carbon($getUserAppointment->created_at);
                    $end    = Carbon::now();
                    $call_started_diff = $start->diffInSeconds($end);
                }
            }
            if($getAllPreAppointments){
                $appointmentNumber = array_search($user_id, $getAllPreAppointments->toArray());
                if(is_int($appointmentNumber)){
                    if($appointmentNumber == 0){
                        $return = (2 * 600) - $call_started_diff;
                        return $return > 0 ? $return : 0;
                    }
                    $appointmentNumber = $appointmentNumber + 1;
                }
                $getPriority = ceil($appointmentNumber / $numberOfAvailableDoctor);
                $return = (($getPriority * 600) + 600) - $call_started_diff;
                return $return > 0 ? $return : 0;
            }
            $return = (2 * 600) - $call_started_diff;
            return $return > 0 ? $return : 0;
        }else{
            $getAppointment = Appointment::find($appointment_id);
            $getAllAppointmentDoctor = Appointment::where('doctor_id', $getAppointment->doctor_id)->where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->where('type', Constant::APPOINTMENT_TYPE_INSTANT)->orderBy('id', 'ASC')->get()->pluck('user_id');
            if($getAllAppointmentDoctor){
                $appointmentNumber = array_search($user_id, $getAllAppointmentDoctor->toArray());
                if(is_int($appointmentNumber)){
                    $doctorFirstAppointment = Appointment::where('doctor_id', $getAppointment->doctor_id)->where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->orderBy('id', 'ASC')->first();
                    if($doctorFirstAppointment->call_started){
                        $start  = new Carbon($doctorFirstAppointment->call_started);
                        $end    = Carbon::now();
                        $call_started_diff = $start->diffInSeconds($end);
                    }
                    return ($appointmentNumber * 600) - $call_started_diff > 0 ? ($appointmentNumber * 600) - $call_started_diff : 0;
                }

                return 600;
            }
            return 600;
        }
    }

    private function availableDoctor(){

        $doctorCount = 0;
        $getDoctors = DoctorDetail::where(['is_instant_consultation' => 1])
        ->with(['user' => function ($query) {
            $query->where('role_id', 3)->select('id', 'name');
        }])
        ->select(['id','doctor_id','current_appointment','last_call_at'])->orderBy('assigned_calls', 'asc')->get();

        if($getDoctors){
            foreach ($getDoctors as $key => $doctor){
                if(!isset($doctor->user->name)){
                    continue;
                }
                $doctorCount = $doctorCount + 1;
            }
        }

        return $doctorCount;

    }

    public function cancelInstantConsultaion(Request $request){
        if(Request()->segment(2) === "v2"){
            $user_id = \Auth::user()->id;
        }else{
            $user_id = $request->headers->get('user_id');
        }
        $checkAppointment = Appointment::where([
            ['user_id', $user_id],
            ['doctor_id', '!=', null],
            ['type', 'instant-consultation'],
            ['progress', (new Constant)->APPOINTMENT_STATUS_PENDING]
        ])->first();
        if($checkAppointment){
            return $this->returnResponse(400, 'Sorry, you can not cancel your appointment once the doctor is assigned.', $checkAppointment);
        }
        $checkPreAppointment = PreAppointment::where([
            ['user_id', $user_id],
            ['doctor_id', null],
            ['type', 'instant-consultation'],
            ['progress', (new Constant)->APPOINTMENT_STATUS_PENDING]
        ])->first();
        if($checkPreAppointment){
            $reasons = $request->has('reasons') ? $request->reasons : null;
            CancelledAppointment::create(['appointment_id' => $checkPreAppointment->id, 'user_id' => $user_id, 'reasons' => $reasons]);
            $checkPreAppointment->delete();
            return $this->returnResponse(200, 'Your appointment has been cancelled successfully.');
        }
        return $this->returnResponse(404, 'No current appointment found against your profile.');
    }

    public function getWaitingTime(Request $request){
        if(Request()->segment(2) === "v2"){
            $user_id = \Auth::user()->id;
        }else{
            $user_id = $request->headers->get('user_id');
        }
        $checkPreAppointment = PreAppointment::where([
            ['user_id', $user_id],
            ['doctor_id', null],
            ['type', 'instant-consultation'],
            ['progress', (new Constant)->APPOINTMENT_STATUS_PENDING]
        ])->first();
        if($checkPreAppointment){
            if($this->calculateWaitingTime(0, $user_id) == null){
                return $this->returnResponse(400, 'Searching for the best available doctors online......');
            }
            return $this->returnResponse(200,'success', ['waiting_time' => $this->calculateWaitingTime(0, $user_id)]);
        }
        $checkAppointment = Appointment::where([
            ['user_id', $user_id],
            ['doctor_id', '!=', null],
            ['type', 'instant-consultation'],
            ['progress', (new Constant)->APPOINTMENT_STATUS_PENDING]
        ])->first();
        if($checkAppointment){
            return $this->returnResponse(200,'success', ['waiting_time' => $this->calculateWaitingTime($checkAppointment->id, $user_id)]);
        }
        return $this->returnResponse(404, 'No current appointment found against your profile.');
    }

    public function verifyPayment(Request $request){
        if(Request()->segment(2) === "v2"){
            $user_id = \Auth::user()->id;
        }else{
            $user_id = $request->headers->get('user_id');
        }
        $getUserTransaction = Transaction::where([
            ['reference_type', 'one_time'],
            ['user_id', $user_id],
            ['is_avail', 0]
        ])->orderBy('created_at', 'desc')->with('user')->first();
        if($getUserTransaction->status){
            return $this->returnResponse(200, 'Paid', $getUserTransaction);
        }
        return $this->returnResponse(404, 'No payment found against your profile.');
    }

    private function calculateRemainingTime($appointment_id, $time, $user_id){
        $call_started_diff = 0;
        if($appointment_id == 0){
            return $time;
        }
        $getAppointment = Appointment::find($appointment_id);
        $getAllAppointmentDoctor = Appointment::where('doctor_id', $getAppointment->doctor_id)->where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->where('type', Constant::APPOINTMENT_TYPE_INSTANT)->orderBy('id', 'ASC')->get()->pluck('user_id');
        if($getAllAppointmentDoctor){
            $appointmentNumber = array_search($user_id, $getAllAppointmentDoctor->toArray());
            if(is_int($appointmentNumber)){
                $doctorFirstAppointment = Appointment::where('doctor_id', $getAppointment->doctor_id)->where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->orderBy('id', 'ASC')->first();
                if($doctorFirstAppointment->call_started){
                    $start  = new Carbon($doctorFirstAppointment->call_started);
                    $end    = Carbon::now();
                    $call_started_diff = $start->diffInSeconds($end);
                }else{
                    $start  = new Carbon($doctorFirstAppointment->created_at);
                    $end    = Carbon::now();
                    $call_started_diff = $start->diffInSeconds($end);
                }
                $remaining_time = $time - $call_started_diff;
                return $remaining_time >= 0 ? $remaining_time : 0;
            }
        }

        return $time;
    }

    public function getWaitingTimeRes($user_id){
        $checkPreAppointment = PreAppointment::where([
            ['user_id', $user_id],
            ['doctor_id', null],
            ['type', 'instant-consultation'],
            ['progress', (new Constant)->APPOINTMENT_STATUS_PENDING]
        ])->first();
        if($checkPreAppointment){
            if($this->calculateWaitingTime(0, $user_id) == null){
                return null;
            }
            return $this->calculateWaitingTime(0, $user_id);
        }
        $checkAppointment = Appointment::where([
            ['user_id', $user_id],
            ['doctor_id', '!=', null],
            ['type', 'instant-consultation'],
            ['progress', (new Constant)->APPOINTMENT_STATUS_PENDING]
        ])->first();
        if($checkAppointment){
            return $this->calculateWaitingTime($checkAppointment->id, $user_id);
        }
        return null;
    }

    public function patient_connected(Request $request){

        $getAppointment = Appointment::find($request->id);
        if(!$getAppointment){
            return $this->returnResponse(400, "Invalid appointment id provided");
        }
        $getAppointment->is_patient_connected = 1;
        $getAppointment->save();
        return $this->returnResponse(200, 'Patient connected.');
    }

    public function rtmTokenGenrator(Request $request){
        try{
            if(Request()->segment(2) === "v2"){
                $user_id = (string) \Auth::user()->id;
            }else{
                $user_id = $request->headers->get('user_id');
            }
            $rtmBuilder = new RtmTokenBuilder();
            $appID = env('AGORA_APP_ID');
            $appCertificate = env('AGORA_APP_CERTIFICATE');
            $uidStr = $user_id;
            $expireTimeInSeconds = 86400;

            $currentTimestamp = (new \DateTime("now", new \DateTimeZone('UTC')))->getTimestamp();
            $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

            $token = $rtmBuilder::buildToken($appID, $appCertificate, $uidStr, $privilegeExpiredTs);
            return $this->returnResponse(200, "token generated successfully.", ['token' => $token]);
        }catch(\Exception $e){
            return $this->returnResponse(400, $e->getMessage());
        }
    }

    public function reasonForVisit(Request $request)
    {
        try{
            return $this->returnResponse(200, 'Success', ReasonForVisit::all());
        }catch(\Exception $e){
            return $this->returnResponse(400, $e->getMessage());
        }
    }

    public function updateReasonForVisit(Request $request, Appointment $appointment){
        try{
            $appointment->reason_for_visit = $request->has('reason_for_visit') ? $request->reason_for_visit : null;
            $appointment->additional_detail = $request->has('additional_detail') ? $request->additional_detail : null;
            $appointment->save();
            return $this->returnResponse(200, '', new AppointmentResource($appointment));
        }catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function switchDoctor(Request $request){
        try{
            if(Request()->segment(2) === "v2"){
                $user_id = \Auth::user()->id;
            }else{
                $user_id = $request->header('user_id');
            }
            $check_appointment = Appointment::where([
                ['user_id', $user_id],
                ['progress', (new Constant)->APPOINTMENT_STATUS_PENDING]
            ])->first();
            if(!$check_appointment)
                return $this->returnResponse(400, 'Sorry, no DoctorNow consultation found.');

            $getOnlineDoctorsCount = DoctorDetail::where('is_instant_consultation', 1)->where('doctor_id', '!=', $check_appointment->doctor_id)->count();
            if($getOnlineDoctorsCount == 0)
                return $this->returnResponse(400, 'No doctors are available at this time.');

            if($check_appointment->switch_doctor_count >= 3)
                return $this->returnResponse(400, 'Limit reached for changing doctors.');

            $getOnlineDoctors = DoctorDetail::where('is_instant_consultation', 1)->where('doctor_id', '!=', $check_appointment->doctor_id)->get();

            foreach($getOnlineDoctors as $key => $doc){
                if(Appointment::where('doctor_id', $doc->doctor_id)->where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->count() < 2){
                    $check_appointment->switch_doctor_count = $check_appointment->switch_doctor_count + 1;
                    $check_appointment->doctor_id = $doc->doctor_id;
                    $check_appointment->save();
                    return $this->returnResponse(200, 'Doctor switch successfully.', new AppointmentResource($check_appointment));
                }
            }

            return $this->returnResponse(400, 'Sorry, no Doctor available currently please try again later.', []);
        }catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function switchDoctorReason(Request $request){
        try{
            if(Request()->segment(2) === "v2"){
                $user_id = \Auth::user()->id;
            }else{
                $user_id = $request->header('user_id');
            }
            $check_appointment = Appointment::where([
                ['user_id', $user_id],
                ['progress', (new Constant)->APPOINTMENT_STATUS_PENDING]
            ])->first();
            if(!$check_appointment)
                return $this->returnResponse(400, 'Sorry, no DoctorNow consultation found.');

            $getOnlineDoctorsCount = DoctorDetail::where('is_instant_consultation', 1)->where('doctor_id', '!=', $check_appointment->doctor_id)->count();
            if($getOnlineDoctorsCount == 0)
                return $this->returnResponse(400, 'No doctors are available at this time.');

            if($check_appointment->switch_doctor_count >= 3)
                return $this->returnResponse(400, 'Limit reached for changing doctors.');

            $testDoctorId = 1337;
            if(env('APP_ENV') == 'staging'){
                $testDoctorId = 1920;
            }
            $getOnlineDoctors = DoctorDetail::where('is_instant_consultation', 1)->where('doctor_id', '!=', $testDoctorId)->where('doctor_id', '!=', $check_appointment->doctor_id)->get();

            foreach($getOnlineDoctors as $key => $doc){
                if(Appointment::where('doctor_id', $doc->doctor_id)->where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->count() < 2){
                    $check_appointment->switch_doctor_count = $check_appointment->switch_doctor_count + 1;
                    $check_appointment->doctor_id = $doc->doctor_id;
                    $check_appointment->switch_reason =  $request->has('switch_reason') ? $request->switch_reason : null;
                    $check_appointment->save();
                    return $this->returnResponse(200, 'Doctor switch successfully.', new AppointmentResource($check_appointment));
                }
            }

            return $this->returnResponse(400, 'Sorry, no Doctor available currently please try again later', []);
        }catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    
    public function verifyPaymentDownload(Request $request)
    {
        try{
            if(Request()->segment(2) === "v2"){
                $user_id = \Auth::user()->id;
            }else{
                $user_id = $request->headers->get('user_id');
            }
            $getUserTransaction = Transaction::where([
                ['reference_type', 'one_time'],
                ['user_id', $user_id],
                ['is_avail', 0]
            ])->with('user')->orderBy('created_at', 'desc')->first();

            $getSettings['html'] = 1;
            $getSettings['facebook'] = $this->returnSettingByKey('facebook_link');
            $getSettings['instagram'] = $this->returnSettingByKey('instagram_link');
            $getSettings['youtube'] = $this->returnSettingByKey('youtube_link');
            $getSettings['twitter'] = $this->returnSettingByKey('twitter_link');
            $getSettings['linkedin'] = $this->returnSettingByKey('lindedin_link');
            $getSettings['uan_number'] = $this->returnSettingByKey('uan_number');
            $getSettings['email'] = $this->returnSettingByKey('email');

            $folder = storage_path().'/app/public/transaction-downloads/';
            $public_folder = 'storage/transaction-downloads/';
            if(!File::isDirectory($folder)) {
                File::makeDirectory($folder, 0777, true, true);
            }
            $now = Carbon::now()->format('d-m-Y_h.ia');
            $filename = 'transaction_'.$now.'.pdf';
            $getSettings['html'] = 0;
            $title = 'transaction_'.$now;
            $filePath=public_path($public_folder.$filename);
            $pdf = mPDF::loadView('download.transaction', ['transaction' => $getUserTransaction, 'settings' => $getSettings],[], [
                'title' => $title,
                'author' => 'MeriSehat (Pvt) Ltd.',
                'margin_top' => 70,
                'margin_bottom' => 70,
                'margin_footer' => 15,
                'margin_header' => 15,
            ])->save($folder.$filename);
            if($request->header('platform')=='app')
            {
                return $this->returnResponse(200, 'Download Successfully', [
                    'pdf_file_name' => $filename,
                    'pdf_download_link' => url($public_folder.$filename)
                ]);
            }
            else{
                return response()->download($filePath, $filename, [
                    "Pragma" =>" public",
                    "Expires" => "0",
                    "Cache-Control" => " must-revalidate, post-check=0, pre-check=0",
                    "Content-Type" => "application/force-download",
                    "Content-Type" => "application/octet-stream",
                    "Content-Type" => "application/download",
                    "Content-Disposition" => 'attachment; filename="'.$filename.'"',
                    "Content-Transfer-Encoding" => "binary ",
                    // 'Content-Type' => 'application/pdf',
                    // 'Content-Disposition' => 'attachment; filename="'.$filename.'"',
                ]);
            }
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function paymentReceiptDownload(Request $request){
        try{
            if(Request()->segment(2) === "v2"){
                $user_id = \Auth::user()->id;
            }else{
                $user_id = $request->headers->get('user_id');
            }
            $getUserTransaction = Transaction::where([
                ['user_id', $user_id],
                ['status', 1],
                ['type','debit']
            ])->with('user')->orderBy('created_at', 'desc')->first();
            $getSettings['html'] = 1;
            $getSettings['facebook'] = $this->returnSettingByKey('facebook_link');
            $getSettings['instagram'] = $this->returnSettingByKey('instagram_link');
            $getSettings['youtube'] = $this->returnSettingByKey('youtube_link');
            $getSettings['twitter'] = $this->returnSettingByKey('twitter_link');
            $getSettings['linkedin'] = $this->returnSettingByKey('lindedin_link');
            $getSettings['uan_number'] = $this->returnSettingByKey('uan_number');
            $getSettings['email'] = $this->returnSettingByKey('email');

            $folder = storage_path().'/app/public/transaction-downloads/';
            $public_folder = 'storage/transaction-downloads/';
            if(!File::isDirectory($folder)) {
                File::makeDirectory($folder, 0777, true, true);
            }
            $now = Carbon::now()->format('d-m-Y_h.ia');
            $filename = 'Payment_Receipt_'.$now.'.pdf';
            $getSettings['html'] = 0;
            $title = 'Payment Receipt'.$now;
            $pdf = mPDF::loadView('download.transaction', ['transaction' => $getUserTransaction, 'settings' => $getSettings],[], [
                'title' => $title,
                'author' => 'MeriSehat (Pvt) Ltd.',
                'margin_top' => 70,
                'margin_bottom' => 70,
                'margin_footer' => 15,
                'margin_header' => 15,
            ])->save($folder.$filename);
            return $this->returnResponse(200, 'Download Successfully', [
                'pdf_file_name' => $filename,
                'pdf_download_link' => url($public_folder.$filename)
            ]);
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function returnSettingByKey($key)
    {
        return Settings::where('key', $key)->first()->value ?? null;
    }
}
