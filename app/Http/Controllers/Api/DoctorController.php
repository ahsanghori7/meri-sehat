<?php

namespace App\Http\Controllers\Api;

use App\Http\Common\{ Constant, Helper, SmsHelper};
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserSummeryResource;
use Illuminate\Http\Request;
use App\Models\{City, DoctorClinic, DoctorSpeciality, Role, User, DoctorDetail, Appointment, Settings,DoctorPayable, DoctorEarning, ClinicTiming,Clinic};
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use DateInterval;
use DateTime;
use Exception;
use DB;


class DoctorController extends Controller
{
    public function getDoctors(Request $request)
    {
        try {
            if(Request()->segment(2) === "v2"){
                $user = \Auth::user() ?? \Auth::guard("api")->user();
                $userId = ($user == null) ? $request->user_id : $user->id;
            }else{
                $userId = $request->user_id ? $request->user_id : $request->header('user-id');
            }
            $getDoctors = User::getAllDoctors($userId, $this->getConstantByValue('DOCTOR_ROLE_ID'), $request->doctor, $request->city, $request->speciality, $request->service, $request->q, $request->is_appointment, $request->is_featured);
            if(!count($getDoctors)){
                return $this->returnResponse(400, 'There is no doctor\'s available right now.');
            }
            return $this->returnResponse(200, '', UserSummeryResource::collection($getDoctors));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function currentAppointments(Request $request)
    {
        try {
            if(Request()->segment(2) === "v2"){
                $user = \Auth::user() ?? \Auth::guard("api")->user();
                $userId = ($user == null) ? $request->user_id : $user->id;
            }else{
                $userId = $request->user_id ? $request->user_id : $request->header('user-id');
            }
            $getCount = Appointment::where([
                'doctor_id' => $userId,
                'type' => Constant::APPOINTMENT_TYPE_INSTANT,
                'progress' => (new Constant)->APPOINTMENT_STATUS_PENDING,
            ])->count();
            return $this->returnResponse(200, 'doctor current appointments', ['count' => $getCount ? true : false]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getEarning(Request $request)
    {
        try {
            $user_id = $request->header('user_id');
            $user = User::where('id', $user_id)->with('hasDoctor')->first();
            $bank_info = $user->doctorBankDetails ?? null;

            $today_date = Carbon::now();
            $strt_date_this_month = $today_date->firstOfMonth()->format('Y-m-d');
            $end_date_this_month = $today_date->lastOfMonth()->format('Y-m-d');
            $today_date = Carbon::now()->format('Y-m-d');

            $earnings = DoctorPayable::whereHas('doctorEarning', function($q) use($user_id){
                $q->where('doctor_id', $user_id);
            })
            ->where('status', 'paid')
            ->get();
            $earnings = count($earnings) > 0 ? $earnings : null;

            $constant = new Constant();
            $this_month = $this->getEarningBaseQuery($user_id)->whereBetween('created_at', [Carbon::parse($strt_date_this_month), Carbon::parse($end_date_this_month)])->sum('amount_paid');
            $all_time = $this->getEarningBaseQuery($user_id)->sum('amount_paid');
            $today = $this->getEarningBaseQuery($user_id)->whereDate('created_at', $today_date)->sum('amount_paid');
            $online_consultation = $this->getEarningBaseQuery($user_id)
            ->whereHas('appointmentPayables', function($q) use($constant){
                $q->whereHas('appointment', function($query) use($constant){
                    $query->where('type', $constant::APPOINTMENT_TYPE_SCHEDULE);
                });
            })
            ->sum('amount_paid');
            $in_person_appointment = $this->getEarningBaseQuery($user_id)
            ->whereHas('appointmentPayables', function($q) use($constant){
                $q->whereHas('appointment', function($query) use($constant){
                    $query->where('type', $constant::APPOINTMENT_TYPE_IN_PERSON);
                });
            })
            ->sum('amount_paid');


            $earning_totals = [
                'this_month' => $this_month,
                'all_time' => $all_time,
                'today' => $today,
                'in_person_appointment' => $in_person_appointment,
                'online_consultation' => $online_consultation,
            ];
            $bank_details = [
                'account_name' => $bank_info != null ? $bank_info->account_name: '-',
                'iban_number' => $bank_info != null ? $bank_info->iban_number: '-',
                'bank_name' => $bank_info != null ? $bank_info->bank_name: '-',
                'account_number' => $bank_info != null ? $bank_info->account_number: '-',
            ];

            if (!$bank_info) {
                $bank_details = '';
            }

            $data = [
                'bank_details' => $bank_details,
                'earning_totals' => $earning_totals,
                'earnings' => $earnings,
            ];
            // $data = $this->paginate($data);
            // $data->withPath($request->url());
            return $this->returnResponse(200, '', $data);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to update the available status of the doctor
     */
    public function updateAvailablity(Request $request)
    {
        try {
            $getDoctorDetails = User::find($request->header('user_id'));
            if(!$getDoctorDetails){
                return $this->returnResponse(400, 'There is no doctor\'s available right now.');
            }
            $getDoctorDetails->doctorDetail()->update(['is_available' => !$getDoctorDetails->doctorDetail->is_available]);
            return $this->returnResponse(200, '', new UserSummeryResource(User::find($request->header('user_id'))));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to Get lisiting of the clinics with their time slots
     */
    public function clinicListing(Request $request)
    {
        try {
            $doctorId = $request->doctor_id ?? $request->header('user_id');
            $getDoctorDetails = User::where(['id' => $doctorId, 'role_id' => (new Constant)->DOCTOR_ROLE_ID])->first();
            if(!$getDoctorDetails){
                return $this->returnResponse(400, 'You are not authorized to get the details.');
            }
            $getDoctorClinic = DoctorClinic::getClinicWithTimeSlots($doctorId, $request->is_physical);
            if(!count($getDoctorClinic)){
                return $this->returnResponse(400, 'You are create any clinics yet.');
            }
            return $this->returnResponse(200, '', $getDoctorClinic);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    function getEarningBaseQuery($user_id){
        return $base_query = DoctorPayable::whereHas('doctorEarning', function($q) use($user_id){
            $q->where('doctor_id', $user_id);
        })
        ->where('status', 'paid');
    }

    function getEarningTableBaseQuery($user_id){
        return DoctorEarning::where('doctor_id', $user_id);
    }

    function getAppointmentBaseQuery($user_id, $type){
        return $base_query = Appointment::where('doctor_id', $user_id)->where('progress', (new Constant)->APPOINTMENT_STATUS_COMPLETED)
        ->where('type', $type);
    }

    public function checkOnline(Request $request)
    {
        try {
            $doctors_count = 0;
            $doctors_images = array();
            $getDoctors = DoctorDetail::where('is_instant_consultation', 1)->where('is_admin_verified', 1)->whereHas('user', function($getDoctors){
                $getDoctors = $getDoctors->where('status', 1)->where('role_id', (new Constant)->DOCTOR_ROLE_ID);
            })->get();
            $doctors_count = $getDoctors->count();
            if ($getDoctors) {
                for ($i=0; $i < 3; $i++) {
                    if (isset($getDoctors[$i])) {
                        array_push($doctors_images, array(
                            'name' => $getDoctors[$i]->user->name,
                            'email' => Helper::emailMasking($getDoctors[$i]->user->email),
                            'phone' => Helper::phoneMasking($getDoctors[$i]->user->phone),
                            'city' => $getDoctors[$i]->user->city->name,
                            'image' => isset($getDoctors[$i]->user->image) ? $getDoctors[$i]->user->image : asset('public/images') . '/default-pic.png',
                            'image_url' => isset($getDoctors[$i]->user->image) ? env('ASSETS_STORAGE').$getDoctors[$i]->user->image : asset('public/images') . '/default-pic.png',
                        ));
                    }
                }
            }
            $instant_consultation_discount_percent = Settings::where('key', 'instant_consultation_discount_percent')->get()->pluck('value');
            $instant_consultation_fees = Settings::where('key', 'instant_consultation_fees')->get()->pluck('value');
            $instant_consultation_discounted_fees = Settings::where('key', 'instant_consultation_discounted_fees')->pluck('value');

            $data['doctor_images'] = $doctors_images;
            $data['online_doctors'] = $doctors_count;
            $data['is_popup'] = 0;
            $data['instant_consultation_fees'] = (isset($instant_consultation_fees[0]))?$instant_consultation_fees[0]:env('INSTANT_CONSULTATION_FEES');
            $data['instant_consultation_discounted_fees'] = (isset($instant_consultation_discounted_fees[0]))?$instant_consultation_discounted_fees[0]:env('INSTANT_CONSULTATION_FEES');
            $data['instant_consultation_discount_percent']= (isset($instant_consultation_discount_percent[0]))?$instant_consultation_discount_percent[0]:env('instant_consultation_discount_percent');
            $data['time_availability'] = 'Available 24/7';
            if(!count($getDoctors)){
                return $this->returnResponse(400, 'There is no doctor\'s available right now.', $data);
            }
            return $this->returnResponse(200, 'Doctors fetch successfully', $data);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function onlineOffline(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'is_instant_consultation' => ['required','boolean'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            if(Request()->segment(2) === "v2"){
                $user_id = \Auth::user()->id;
            }else{
                $user_id = $request->header('user_id');
            }

            if($user_id == 1337){
                return $this->returnResponse(200, 'You are offline', new UserSummeryResource(User::find($user_id)));
            }
            $getDoctors = DoctorDetail::where('doctor_id', $user_id)->whereHas('user', function($getDoctors){
                $getDoctors = $getDoctors->where('status', 1);
            })->first();
            if(!$getDoctors){
                return $this->returnResponse(400, 'There is no doctor\'s available right now.');
            }
            if ($request->is_instant_consultation == 0) {
                $message = 'You are offline';
            } else {
                $message = 'You are online';
            }
            $getDoctors->is_instant_consultation = $request->is_instant_consultation;
            $getDoctors->save();
            return $this->returnResponse(200, $message, new UserSummeryResource(User::find($user_id)));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getEarningDetails(Request $request){
        try{
            if(Request()->segment(2) === "v2"){
                $user_id = \Auth::user()->id;
            }else{
                $user_id = $request->header('user_id');
            }
            $constant = new Constant();
            $data = [];
            $user = User::where('id', $user_id)->with('hasDoctor')->first();
            $bank_info = $user->doctorBankDetails ?? null;

            $today_date = Carbon::today();
            $start_date_this_week = $today_date->startOfWeek()->format('Y-m-d H:i:s');
            $end_date_this_week = $today_date->endOfWeek()->format('Y-m-d H:i:s');
            $start_date_this_month = $today_date->startOfMonth()->format('Y-m-d H:i:s');
            $end_date_this_month = $today_date->endOfMonth()->format('Y-m-d H:i:s');
            $start_date_this_year = $today_date->startOfYear()->format('Y-m-d H:i:s');
            $end_date_this_year = $today_date->endOfYear()->format('Y-m-d H:i:s');
            // dd(Carbon::today()->format('Y-m-d H:i:s'));

            $data = [
                'earnings' => [
                    'earning_today' => $this->getEarningTableBaseQuery($user_id)->whereDate('created_at', Carbon::today()->format('Y-m-d'))->sum('income'),
                    'earning_week' => $this->getEarningTableBaseQuery($user_id)->whereBetween('created_at', [$start_date_this_week, $end_date_this_week])->sum('income'),
                    'earning_month' => $this->getEarningTableBaseQuery($user_id)->whereBetween('created_at', [$start_date_this_month, $end_date_this_month])->sum('income'),
                    'earning_year' => $this->getEarningTableBaseQuery($user_id)->whereBetween('created_at', [$start_date_this_year, $end_date_this_year])->sum('income'),
                    'earning_all' => $this->getEarningTableBaseQuery($user_id)->sum('income'),
                    'receivables' => [
                        'amount' => $this->getEarningBaseQuery($user_id)->sum('total_receivable'),
                        'last_payout' => ($this->getEarningBaseQuery($user_id)->count()) ? $this->getEarningBaseQuery($user_id)->latest('transaction_date')->first()->transaction_date : 0,
                    ],
                ],
                'appointments' => [
                    'scheduled' => [
                        'today' => $this->getAppointmentBaseQuery($user_id, $constant::APPOINTMENT_TYPE_SCHEDULE)->whereDate('date', Carbon::today()->format('Y-m-d'))->count(),
                        'week' => $this->getAppointmentBaseQuery($user_id, $constant::APPOINTMENT_TYPE_SCHEDULE)->whereBetween('date', [$start_date_this_week, $end_date_this_week])->count(),
                        'month' => $this->getAppointmentBaseQuery($user_id, $constant::APPOINTMENT_TYPE_SCHEDULE)->whereBetween('date', [$start_date_this_month, $end_date_this_month])->count(),
                        'year' => $this->getAppointmentBaseQuery($user_id, $constant::APPOINTMENT_TYPE_SCHEDULE)->whereBetween('date', [$start_date_this_year, $end_date_this_year])->count(),
                        'all' => $this->getAppointmentBaseQuery($user_id, $constant::APPOINTMENT_TYPE_SCHEDULE)->count(),
                    ],
                    'doctornow' => [
                        'today' => $this->getAppointmentBaseQuery($user_id, $constant::APPOINTMENT_TYPE_INSTANT)->whereDate('date', Carbon::today()->format('Y-m-d'))->count(),
                        'week' => $this->getAppointmentBaseQuery($user_id, $constant::APPOINTMENT_TYPE_INSTANT)->whereBetween('date', [$start_date_this_week, $end_date_this_week])->count(),
                        'month' => $this->getAppointmentBaseQuery($user_id, $constant::APPOINTMENT_TYPE_INSTANT)->whereBetween('date', [$start_date_this_month, $end_date_this_month])->count(),
                        'year' => $this->getAppointmentBaseQuery($user_id, $constant::APPOINTMENT_TYPE_INSTANT)->whereBetween('date', [$start_date_this_year, $end_date_this_year])->count(),
                        'all' => $this->getAppointmentBaseQuery($user_id, $constant::APPOINTMENT_TYPE_INSTANT)->count(),
                    ],
                    'clinic_visits' => [
                        'today' => $this->getAppointmentBaseQuery($user_id, $constant::APPOINTMENT_TYPE_IN_PERSON)->whereDate('date', Carbon::today()->format('Y-m-d'))->count(),
                        'week' => $this->getAppointmentBaseQuery($user_id, $constant::APPOINTMENT_TYPE_IN_PERSON)->whereBetween('date', [$start_date_this_week, $end_date_this_week])->count(),
                        'month' => $this->getAppointmentBaseQuery($user_id, $constant::APPOINTMENT_TYPE_IN_PERSON)->whereBetween('date', [$start_date_this_month, $end_date_this_month])->count(),
                        'year' => $this->getAppointmentBaseQuery($user_id, $constant::APPOINTMENT_TYPE_IN_PERSON)->whereBetween('date', [$start_date_this_year, $end_date_this_year])->count(),
                        'all' => $this->getAppointmentBaseQuery($user_id, $constant::APPOINTMENT_TYPE_IN_PERSON)->count(),
                    ],
                ],
                'bank_details' => $bank_info
            ];
            // $data = $this->paginate($data);
            // $data->withPath($request->url());
            return $this->returnResponse(200, '', $data);

        }catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function earningBreakDown(Request $request){
        try{
            if(Request()->segment(2) === "v2"){
                $user = \Auth::user() ?? \Auth::guard("api")->user();
                $user_id = ($user == null) ? $request->user_id : $user->id;
            }else{
                $user_id = $request->user_id ? $request->user_id : $request->header('user-id');
            }
            $consultation_fees = Settings::where('key','instant_consultation_fee')->first();
            $merisehat_commission_value = Settings::where('key','instant_consultation_ms_commission')->first();
            if($consultation_fees && $merisehat_commission_value){
                $consult_fees = $consultation_fees->value;
                $ms_com_value = $merisehat_commission_value->value;
                $your_earning_value = $consult_fees - $ms_com_value;
                $merisehat_commission_inpercent = ($ms_com_value / $consult_fees ) * 100;
                $your_earning_inpercent = ($your_earning_value / $consult_fees) * 100;
            }
            else{
                $consult_fees = 0;
                $ms_com_value = 0;
                $your_earning_value = 0;
                $merisehat_commission_inpercent = 0;
                $your_earning_inpercent = 0;
            }
                $data = [
                'consultation_fees' => $consult_fees,
                'merisehat_commission_inpercent' => $merisehat_commission_inpercent,
                'merisehat_commission_value' => $ms_com_value,
                'your_earning_inpercent' => $your_earning_inpercent,
                'your_earning_value' => $your_earning_value,
            ];
            return $this->returnResponse(200, '', $data);
        }catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function createTimeSlots(Request $request){
        if(Request()->segment(2) === "v2"){
            $doctor_id = \Auth::user()->id;
        }else{
            $doctor_id = $this->getUserIdFromHeader($request->header());
        }
        if($request->isMethod('post')){
            if($request->has('is_video_consultation')){
                $validator= Validator::make($request->all(), [
                    'is_video_consultation.consultation_fee' => 'required',
                    'is_video_consultation.consultation_duration' => 'required',
                    'is_video_consultation.*.schedule.*.days' => 'required',
                    'is_video_consultation.*.schedule.*.start_time' => 'required',
                    'is_video_consultation.*.schedule.*.end_time' => 'required',
                ]);
                if ($validator->fails()) {
                    return $this->returnResponse(400, $validator->errors()->first());
                }
                DB::beginTransaction();
                try{
                    $doctor_clinic=DoctorClinic::create([
                        'doctor_id' => $doctor_id,
                        'clinic_id' => 0,
                        'consultation_fee' => $request->is_video_consultation['consultation_fee'],
                        'consultation_duration' => $request->is_video_consultation['consultation_duration'],
                        'status' => 1
                    ]);
                    foreach($request->is_video_consultation['schedule'] as $key =>  $value){

                            ClinicTiming::create(['doctor_clinic_id'=>$doctor_clinic->id,'day'=>$value['days'] ,'start_time'=> $value['start_time'], 'end_time' => $value['end_time'],'is_physical' =>0 ,'status' => 1]);

                    }
                    DB::commit();
                }catch (\Exception $e) {
                    // Handle any errors that occur during the transaction
                    DB::rollback();
                    throw $e;
                }

            }
            if($request->has('is_clinic_visit')){
                $validator = Validator::make($request->all(), [
                    'is_clinic_visit.*.consultation_fee' => 'required',
                    'is_clinic_visit.*.consultation_duration' => 'required',
                    'is_clinic_visit.*.clinic_id' => 'required',
                    'is_clinic_visit.*.schedule.*.days' => 'required',
                    'is_clinic_visit.*.schedule.*.start_time' => 'required',
                    'is_clinic_visit.*.schedule.*.end_time' => 'required',
                ]);

                if ($validator->fails()) {
                    return $this->returnResponse(400, $validator->errors()->first());
                }
                DB::beginTransaction();
                try{
                    foreach($request->is_clinic_visit as $isClinic){
                        $doctor_clinic=DoctorClinic::create([
                            'doctor_id' => $doctor_id,
                            'clinic_id' => $isClinic['clinic_id'],
                            'consultation_fee' => $isClinic['consultation_fee'],
                            'consultation_duration' => $isClinic['consultation_duration'],
                            'status' => 1
                        ]);
                        foreach($isClinic['schedule'] as $key =>  $value){

                            ClinicTiming::create(['doctor_clinic_id'=>$doctor_clinic->id,'day'=>$value['days']  ,'start_time'=> $value['start_time'] , 'end_time' => $value['end_time'],'is_physical' =>1 ,'status' => 1]);

                        }
                    }
                    DB::commit();
                }catch (\Exception $e) {
                    // Handle any errors that occur during the transaction
                    DB::rollback();
                    throw $e;
                }
            }
            return $this->returnResponse(200,'Details added successfully.','');
        }else{
            $data=DoctorClinic::where('doctor_id',$doctor_id)->with('clinicTimings')->get();
            if(!$data){
                return $this->returnResponse(400, 'Unable to personal information.');
            }else{
                return $this->returnResponse(200,'Get Doctor clinic details successfully.',$data);

            }
        }

    }

    public function createPersonalInfo(Request $request){

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
            'city_id' => ['required','exists:cities,id'],
            'birth_date' => ['required','date'],
            'gender' => ['required'],
            'assistant_phone' => ['required'],
            'experience_year' => ['required']

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
        $doctorExperince=DoctorDetail::updateOrCreate(
            [
                'doctor_id' => $doctor_id
            ],
            [
                'experience_year' => $request->experience_years,
                'assistant_phone'=> $request->assistant_phone,
            ]);
            if(!$doctorFind && !$doctorExperince){
                return $this->returnResponse(400, 'Unable to personal information.');
            }
           return $this->returnResponse(200,'Personal information updated successfully.','');
    }

    public function allClinicListing(Request $request){
        try{
            $clinicListing=Clinic::get();
            return $this->returnResponse(200,'',$clinicListing);
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getUpdateDoctorDetails(Request $request)
    {
        try {
            if(Request()->segment(2) === "v2"){
                $doctor_id = \Auth::user()->id;
            }else{
                $doctor_id = $this->getUserIdFromHeader($request->header());
            }
            $doctor = User::with('doctorDetail',
            'doctorSpecialities',
            'doctorBankDetails',
            'doctorExperiences',
            'doctorServices',
            'doctorConditions',
            'doctorEducation',
            'doctorCertification',
            'doctorClinics',
            'doctorClinics.clinicTimings',
            )
            ->where('id',$doctor_id)
            ->where('role_id', User::DOCTOR_ROLE)
            ->first();
            if($doctor){
                return $this->returnResponse(200, 'Doctor Details Fetched Successfully', $doctor);
            }
            return $this->returnResponse(404, 'No Doctor Found Against This ID');
        } catch (Exception $e) {
            return $this->returnResponse(400, 'Something went wrong', $e->getMessage());
        }
    }
}
