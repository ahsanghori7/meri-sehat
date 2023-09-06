<?php

namespace App\Http\Controllers\Api;

//use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Common\{Constant, Helper, NotificationHelper};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;


use App\Models\{Appointment, User, Review, PrescribedElement, AppointmentCondition, DoctorClinic, DoctorDetail, Prescription,
    Settings, PromoCode, Transaction, UserPromoCode, UserSubscription, AppointmentPrescription, DoctorEarning, DoctorEarningDetail,
    DoctorPayable, AppointmentPayable, Deduction, AppointmentDeduction
};
use App\Http\Resources\User\AppointmentResource;
use Illuminate\Support\Facades\{DB, File, Storage, Validator};
use stdClass;
//use PDF;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as mPDF;

class AppointmentController extends Controller
{
    public function dashboard(Request $request)
    {
        try{
            $appointment = new Appointment();
            if(Request()->segment(2) === "v2"){
                $userId = \Auth::user()->id;
            }else{
                $userId = $request->doctor_id ?? $this->getUserIdFromHeader($request->header());
            }

            $getAppointmentCount = $appointment->getAppointmentCount($userId);
            if(!$getAppointmentCount){
                return $this->returnResponse(400, 'No appointments available.');
            }
            $review = Review::getReviews($userId);

            return $this->returnResponse(200, '', [
                'appointments_count' => $getAppointmentCount,
                'review_count' => $review ? count($review['reviews']) : 0,
                'review_avg' => $review ? $review['total_rating'] : 0,
                'reviews' => $review ? $review : []
            ]);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function addAppointmentDetails(Request $request, Appointment $appointment)
    {
        try
        {
            $instant_consultation_fees = Settings::where('key', 'instant_consultation_fees')->get()->pluck('value');
            if(Request()->segment(2) === "v2"){
                $userId = \Auth::user()->id;
            }else{
                $userId = $request->header('user_id');
            }
            if($appointment->doctor_id != $userId){
                return $this->returnResponse(401, 'You are un-authenticated');
            }
            DB::beginTransaction();
            $createPrescription = Prescription::updateOrCreate(
                [
                    'appointment_id' => $appointment->id,
                ],
                [
                    'blood_group' => $request->blood_group,
                    'patient_consultation_note' => $request->patient_consultation_note,
                    'cosultation_note' => $request->cosultation_note,
                    'status' => true
                ]
            );
            DoctorDetail::updateOrCreate([
                'doctor_id' => $userId
            ], [
                'current_appointment' => null,
                'last_call_at' => null,
            ]);
            $appointment->update([
                'progress' => (new Constant)->APPOINTMENT_STATUS_COMPLETED,
                'action_by' => $userId,
                'agora_link' => null,
                'prescription_here' => $request->has('prescription_here') ? $request->prescription_here : null,
                'call_ended' => Carbon::now(),
                'call_notes' => $request->has('call_notes') ? $request->call_notes : null
            ]);
            if($request->has('prescription_here')){
                $prescription_here = json_decode($request->prescription_here);
                if(is_array($prescription_here)){
                    foreach($prescription_here as $prescription){
                        AppointmentPrescription::create(['appointment_id' => $appointment->id, 'prescription' => $prescription]);
                    }
                }
            }
            if(isset($request->condition))
            {
                $conditions = $request->condition;
                $conditionsToInsert = [];
                foreach($conditions as $condition){
                    $conditionsToInsert[] = [
                        'appointment_id' => $appointment->id,
                        'condition' => $condition,
                    ];
                }
                $appointment->getCondition()->delete();
                AppointmentCondition::insert($conditionsToInsert);
            }
            if(isset($request->medicine))
            {
                foreach($request->medicine as $medicine)
                {
                    PrescribedElement::updateOrCreate([
                        'prescription_id'=> $createPrescription->id,
                        'prescription_element_id' => $medicine['prescription_element_id'],
                    ],
                    [
                        'number_of_days' => $medicine['number_of_days'] ?? null,
                        'unit' => $medicine['unit'] ?? null,
                        'morning' => $medicine['morning'] ?? null,
                        'afternoon' => $medicine['afternoon'] ?? null,
                        'evening' => $medicine['evening'] ?? null,
                        'night' => $medicine['night'] ?? null,
                        'is_after_meal' => $medicine['is_after_meal'] ?? 0,
                    ]);
                }

                $name = $appointment->user->name ?? 'User';
                $notification = new stdClass;
                $notification->send_via = 'notification';
                $notification->to = [$appointment->id];
                $notification->ref_id = $appointment->doctor_id;
                $notification->title = "Your prescription is ready to view! 📝";
                $notification->sub_title = '';
                $notification->key = null;
                $notification->type = 'prescription_created';
                $notification->type_data = $createPrescription->id;
                $notification->text = "Dear $name, tap here to view digital prescription 📱";
                $notification->module = 'prescribed_elements';
                $notification->message = "Dear $name, tap here to view digital prescription 📱";
                $notification->payload = json_encode($notification);
                Helper::sendToUser([$notification]);
            }
            if(isset($request->lab))
            {
                foreach($request->lab as $lab)
                {
                    PrescribedElement::updateOrCreate([
                        'prescription_id'=> $createPrescription->id,
                        'prescription_element_id' => $lab['prescription_element_id'],
                    ],
                    [
                        'prescription_element_id' => $lab['prescription_element_id'],
                        'description' => isset($lab['description']) ? $lab['description'] : null
                    ]);


                }
            }
            $getUserDetail = User::find($appointment->user_id);
            if($appointment->booked_via_subscription === 'subscription'){
                if($getUserDetail->subscription){
                    $userSubscription = UserSubscription::find($getUserDetail->subscription->id);
                    $receipt_data = json_decode($userSubscription->receipt_data);
                    $receipt_data->consume_free_video_consults = $receipt_data->consume_free_video_consults + 1;
                    $update_receipt_data = json_encode($receipt_data);
                    $userSubscription->update(['receipt_data' => $update_receipt_data]);
                }
            }else if($appointment->booked_via_subscription === 'free_trail'){
                $getUserDetail->update([ 'trial_consultation' => $getUserDetail->trial_consultation - 1]);
            }else{
                if($appointment->booked_via_subscription == 'one_time_payment' ){
                    Transaction::where([
                        ['reference_type', 'one_time'],
                        ['user_id', $appointment->user_id],
                        ['status', 1],
                        ['is_avail', 0],
                    ])->first()->update(['is_avail' => 1]);
                }else{
                    if($appointment->booked_via_subscription){
                        $getPromoCodeDetail = PromoCode::where('code', $appointment->booked_via_subscription)->first();
                        if($getPromoCodeDetail){
                            $getUserPromoCode = UserPromoCode::where([
                                ['user_id', $appointment->user_id],
                                ['promo_code_id', $getPromoCodeDetail->id]
                            ])->first();
                            if($getUserPromoCode){
                                $getUserPromoCode->update(['no_of_consultation_avail' => $getUserPromoCode->no_of_consultation_avail + 1]);
                            }
                        }

                    }
                }
            }
            $getDoctorEarning = DoctorEarning::where('doctor_id', $appointment->doctor_id)->where('status', (new Constant)->EARNING_STATUS_UNPAID)->first();
            $percentage = 80;
            $value = ($percentage / 100) * $instant_consultation_fees[0];
            if($getDoctorEarning){
                $getDoctorEarning->total_payable = $getDoctorEarning->total_payable + $value;
                $getDoctorEarning->remaining_payable = $getDoctorEarning->remaining_payable + $value;
                $getDoctorEarning->income = $getDoctorEarning->income + $value;
                $getDoctorEarning->save();
            }else{
                $getDoctorEarning = DoctorEarning::create([
                    'parent_id' => 0,
                    'doctor_id' => $userId,
                    'total_payable' => $value,
                    'remaining_payable' => $value,
                    'income' => $value,
                    'deduction' => 0,
                    'status' => (new Constant)->EARNING_STATUS_UNPAID
                ]);
            }
            DoctorEarningDetail::create([
                'doctor_earning_id' => $getDoctorEarning->id,
                'appointment_id' => $appointment->id,
                'status' => (new Constant)->EARNING_STATUS_UNPAID
            ]);
            DB::commit();

            $name = $appointment->user->name ?? 'User';
            $notification = new stdClass;
            $notification->send_via = 'notification';
            $notification->to = [$appointment->id];
            $notification->ref_id = $appointment->doctor_id;
            $notification->title = "Followup / Feedback";
            $notification->sub_title = '';
            $notification->key = 'appointment';
            $notification->type = 'appointment_feedback';
            $notification->type_data = $appointment->id;
            $notification->text = "Dear $name, how was your instant consultation experience? 🌟🌟🌟. Tap here to let us know.";
            $notification->module = 'appointments';
            $notification->message = "Dear $name, how was your instant consultation experience? 🌟🌟🌟. Tap here to let us know.";
            $notification->payload = json_encode($notification);
            Helper::sendToUser([$notification]);
            return $this->returnResponse(200, 'Appointment completed successfully.');
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    /**
     * This method is used to create new Appointment
     */
    public function createAppointment(Request $request){
        try{
            DB::beginTransaction();
            $input = $request->all();
            $userId = $this->getUserIdFromHeader($request->header());
            $validator = Validator::make($input, [
                'doctor_id' => ['required', 'exists:users,id'],
                'clinic_id' => ['required','exists:doctor_clinics,id'],
                'type' => ['required'],
                'date' => ['required'],
                'time' => ['required'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            $getDoctorClinic = DoctorClinic::find($request->clinic_id);
            $previousAppointment = Appointment::where([
                'date' => Carbon::parse($request->date)->format('Y-m-d'),
                'time' => Carbon::parse($request->time)->format('H:i'),
                'doctor_clinic_id' => $request->clinic_id,
                'type' => $request->type,
                'doctor_id' => $request->doctor_id,
                'progress' => $this->getConstantByValue('APPOINTMENT_STATUS_PENDING'),
            ])->first();
            if($previousAppointment){
                return $this->returnResponse(400, 'Appointment has already been created at that date or time, please select another timee slot.');
            }
            $settings = Settings::getValues(['meri_sehat_commission','meri_sehat_commission_type']);
            $doctor_total = $getDoctorClinic->consultation_fee;
            $ms_total = 0;
            if($settings['meri_sehat_commission_type'] == '1'){
                $ms_total = ($getDoctorClinic->consultation_fee / 100) * $settings['meri_sehat_commission'];
            }else{
                $ms_total =  $settings['meri_sehat_commission'];
            }
            $doctor_total = $doctor_total - $ms_total;
            $createAppointmentData = [
                'user_id' => $userId,
                'doctor_id' => $request->doctor_id,
                'doctor_clinic_id' => $request->clinic_id,
                'family_member_id' => isset($request->family_member_id) ? $request->family_member_id : null,
                'reason' => $request->reason,
                'type' => $request->type,
                'date' => Carbon::parse($request->date)->format('Y-m-d'),
                'time' => Carbon::parse($request->time)->format('H:i'),
                'progress' => $this->getConstantByValue('APPOINTMENT_STATUS_PENDING'),
                'action_by' => $userId,
                'consultation_fee' => $getDoctorClinic->consultation_fee,
                'ms_total' => $ms_total,
                'doctor_total' => $doctor_total,
                'grand_total' => $getDoctorClinic->consultation_fee,
                'ms_commission' => $settings['meri_sehat_commission'],
                'ms_commission_is_percentage' => $settings['meri_sehat_commission_type'],

            ];
            $createAppointment = Appointment::create($createAppointmentData);

            if(!$createAppointment){
                return $this->returnResponse(400, 'Unable to create appointment.');
            }
            // Send an notification to admin
            $getUserDetails = User::find($userId);
            $getDoctorDetails = User::find($request->doctor_id);
            $payload = [
                'module' => 'Appointment',
                'id' => $createAppointment->id,
            ];
            $body = $getUserDetails->name . " booked an " . \Str::title($request->type)." appointment with " . $getDoctorDetails->doctorDetail->prefix . '. '. $getDoctorDetails->name;
            NotificationHelper::createAdminNotification("Booking an Appointment", $body, $payload);

            DB::commit();
            return $this->returnResponse(200, 'Appointment has been created successfully.', new AppointmentResource($createAppointment));
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to update an Appointment whose appointment date is greater than 24 hours from now.
     */
    public function updateAppointment(Request $request, Appointment $appointment){
        try{
            DB::beginTransaction();
            $input = $request->all();
            $userId = $this->getUserIdFromHeader($request->header());
            $validator = Validator::make($input, [
                'date' => ['required'],
                'time' => ['required'],
            ]);
            if ($validator->fails()) {
                return $this->returnResponse(400, $validator->errors()->first());
            }
            if($userId != $appointment->user_id && $userId != $appointment->doctor_id){
                return $this->returnResponse(400, 'You are unauthorized to perform this action.');
            }
            if($appointment->progress != (new Constant)->APPOINTMENT_STATUS_PENDING){
                return $this->returnResponse(400, 'You are unable to edit this appointment.');
            }
            // $getSettings = Settings::where('key', Constant::RESTRICT_APPOINTMENT)->first();
            // $value = $getSettings ? $getSettings->value : 12;
            // if(Carbon::now()->addHours($value)->greaterThanOrEqualTo(Carbon::parse("$appointment->date $appointment->time"))){
            //     return $this->returnResponse(400, "You are unable to update the appointment within $value hours.");
            // }
            $updateAppointment = $appointment->update([
                'date' => $request->date,
                'time' => Carbon::parse($request->time)->format('H:i'),
            ]);

            if(!$updateAppointment){
                return $this->returnResponse(400, 'Unable to create appointment.');
            }
            DB::commit();
            return $this->returnResponse(200, 'Appointment has been updated successfully.');
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to cancel an Appointment whose appointment date is greater than 24 hours from now.
     */
    public function cancelAppointment(Request $request, Appointment $appointment){
        try{

            if(Request()->segment(2) === "v2"){
                $userId = \Auth::user()->id;
            }else{
                $userId = $this->getUserIdFromHeader($request->header());
            }

            if($userId != $appointment->user_id && $userId != $appointment->doctor_id){
                return $this->returnResponse(400, 'You are unauthorized to perform this action.');
            }
            if($appointment->progress != (new Constant)->APPOINTMENT_STATUS_PENDING){
                return $this->returnResponse(400, 'You are unable to edit this appointment.');
            }
            if($appointment->progress == $this->getConstantByValue('APPOINTMENT_STATUS_CANCELLED_BY_USER')
            && $appointment->progress == $this->getConstantByValue('APPOINTMENT_STATUS_CANCELLED_BY_DOCTOR'))
            {
                return $this->returnResponse(200, 'Appointment has been cancelled already.');
            }
            $getUser = User::find($appointment->user_id);
            $appointment->update([
                'progress'=> ($userId == $appointment->user_id) ? $this->getConstantByValue('APPOINTMENT_STATUS_CANCELLED_BY_USER') : $this->getConstantByValue('APPOINTMENT_STATUS_CANCELLED_BY_DOCTOR'),
                'action_by' => ($userId == $appointment->user_id) ? $appointment->user_id : $appointment->doctor_id,
            ]);
            Helper::cancelAppointment($appointment->id);
            $getUser = User::find($appointment->user_id);

            if ($userId == $appointment->user_id) {
                $name = $getUser->name ?? 'User';
                $notification = new stdClass;
                $notification->send_via = 'notification';
                $notification->to = [$getUser->id];
                $notification->ref_id = $appointment->doctor_id;
                $notification->title = "Oh No! Your Consultation is canceled 😓😓";
                $notification->sub_title = '';
                $notification->key = 'appointment';
                $notification->type = 'appointment_user_cancelled';
                $notification->type_data = $appointment->id;
                $notification->text = "Dear $name, your Doctor Now consultation is cancelled as per your request❌.";
                $notification->module = 'appointments';
                $notification->message = "Dear $name, your Doctor Now consultation is cancelled as per your request❌.";
                $notification->payload = json_encode($notification);
                Helper::sendToUser([$notification]);
            } else {
                $name = $getUser->name ?? 'User';
                $notification = new stdClass;
                $notification->send_via = 'notification';
                $notification->to = [$getUser->id];
                $notification->ref_id = $appointment->doctor_id;
                $notification->title = "Oh No! Your Consultation is canceled 😓😓";
                $notification->sub_title = '';
                $notification->key = 'appointment';
                $notification->type = 'appointment_doctor_cancelled';
                $notification->type_data = $appointment->id;
                $notification->text = "Dear $name, unfortunately, your  appointment with ".$appointment->doctor->name." on ".date('d-m-Y')." and ".date('H:i:s a')." is cancelled due to unavailability of the doctor ❌.Tap to reconnect with another doctor.";
                $notification->module = 'appointments';
                $notification->message = "Dear $name, unfortunately, your  appointment with ".$appointment->doctor->name." on ".date('d-m-Y')." and ".date('H:i:s a')." is cancelled due to unavailability of the doctor ❌.Tap to reconnect with another doctor.";
                $notification->payload = json_encode($notification);
                Helper::sendToUser([$notification]);
            }

            return $this->returnResponse(200, 'Cancelled Successfully');
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function getDoctorAppointments(Request $request)
    {
        try{
            $userId = $request->user_id ? $request->user_id : $request->header('user_id');
            $constant = new Constant();

            if($request->status == "past"){
                $progress = [
                    $constant->APPOINTMENT_STATUS_COMPLETED,
                    $constant->APPOINTMENT_STATUS_CANCELLED,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_USER,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_DOCTOR
                ];
            }else if($request->status == "upcoming"){
                $progress = [
                    $constant->APPOINTMENT_STATUS_PENDING
                ];
            }else if($request->status == "cancelled"){
                $progress = [
                    $constant->APPOINTMENT_STATUS_CANCELLED,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_USER,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_DOCTOR
                ];
            }elseif($request->status == "all"){
                $upcommingProgress = [
                    $constant->APPOINTMENT_STATUS_PENDING
                ];
                $pastAppointmentStatus = [
                    $constant->APPOINTMENT_STATUS_COMPLETED,
                    $constant->APPOINTMENT_STATUS_CANCELLED,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_USER,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_DOCTOR
                ];
                $getUpcommingAppointment = Appointment::where('doctor_id', $userId)
                    ->whereIn('progress', $upcommingProgress)
                    ->where(function($query){
                        $query->whereDate('date','>',Carbon::now()->toDateString());
                        $query->orWhere(function($subQuery){
                            $subQuery->whereDate('date','=',Carbon::now()->toDateString());
                            $subQuery->whereTime('time','>',Carbon::now()->toTimeString());
                        });
                    })
                    ->orderBy('id', 'DESC')->get();

                $getPastAppointment = Appointment::where('doctor_id', $userId)
                    ->where(function($query) use ($pastAppointmentStatus){
                        $query->orWhereIn('progress', $pastAppointmentStatus);
                        $query->orWhere(function($subQuery){
                            $subQuery->whereDate('date','<',Carbon::now()->toDateString());
                            $subQuery->orWhere(function($nestedQuery){
                                $nestedQuery->whereDate('date','=',Carbon::now()->toDateString());
                                $nestedQuery->whereTime('time','<',Carbon::now()->toTimeString());
                            });

                        });
                    })->orderBy('id', 'DESC')->get();
                return $this->returnResponse(200, '', [
                    'upcomming_appointments' => AppointmentResource::collection($getUpcommingAppointment),
                    'past_appointments' => AppointmentResource::collection($getPastAppointment),
                ]);
            }else{
                $progress = [
                    $constant->APPOINTMENT_STATUS_PENDING,
                    $constant->APPOINTMENT_STATUS_PROCESSING,
                    $constant->APPOINTMENT_STATUS_COMPLETED,
                    $constant->APPOINTMENT_STATUS_CANCELLED,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_USER,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_DOCTOR
                ];
            }
            if($request->status == "past")
            {
                $getAppointment = Appointment::where('user_id', $userId)
                    ->where(function($query) use ($progress){
                        $query->orWhereIn('progress', $progress);
                        $query->orWhere(function($subQuery){
                            $subQuery->whereDate('date','<',Carbon::now()->toDateString());
                            $subQuery->orWhere(function($nestedQuery){
                                $nestedQuery->whereDate('date','=',Carbon::now()->toDateString());
                                $nestedQuery->whereTime('time','<',Carbon::now()->toTimeString());
                            });
                        });
                    })->orderBy('id', 'DESC')->get();
            }
            elseif($request->status == "upcoming")
            {
                $getAppointment = Appointment::where('doctor_id', $userId)
                    ->whereIn('progress', $progress)
                    ->where(function($query){
                        $query->whereDate('date','>',Carbon::now()->toDateString());
                        $query->orWhere(function($subQuery){
                            $subQuery->whereDate('date','=',Carbon::now()->toDateString());
                            $subQuery->whereTime('time','>',Carbon::now()->toTimeString());
                        });
                    })->orderBy('id', 'DESC')->get();
            }
            elseif($request->status == "cancelled")
            {
                $getAppointment = Appointment::where('doctor_id', $userId)
                    ->whereIn('progress', $progress)->get();
            } else {
                $getAppointment = Appointment::where('doctor_id', $userId)
                    ->whereIn('progress', $progress)
                    ->orderBy('id', 'DESC')->get();
            }
            return $this->returnResponse(200, '', AppointmentResource::collection($getAppointment));
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to list all the Appointments.
     */
    public function getAppointments(Request $request)
    {
        try{
            if(Request()->segment(2) === "v2"){
                $userId = \Auth::user()->id;
            }else{
                $userId = $request->user_id ? $request->user_id : $request->header('user-id');
            }
            $doctorId = $request->doctor_id;
            $constant = new Constant();

            if($request->status == "past"){
                $progress = [
                    $constant->APPOINTMENT_STATUS_COMPLETED,
                    $constant->APPOINTMENT_STATUS_CANCELLED,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_USER,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_DOCTOR
                ];
            }else if($request->status == "upcoming"){
                $progress = [
                    $constant->APPOINTMENT_STATUS_PENDING
                ];
            }else if($request->status == "cancelled"){
                $progress = [
                    $constant->APPOINTMENT_STATUS_CANCELLED,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_USER,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_DOCTOR
                ];
            }elseif($request->status == "all"){
                $upcommingProgress = [
                    $constant->APPOINTMENT_STATUS_PENDING
                ];
                $pastAppointmentStatus = [
                    $constant->APPOINTMENT_STATUS_COMPLETED,
                    $constant->APPOINTMENT_STATUS_CANCELLED,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_USER,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_DOCTOR
                ];
                $getUpcommingAppointment = Appointment::where('user_id', $userId)
                ->where(function($query) use($doctorId){
                    if($doctorId){
                        $query->where('doctor_id', $doctorId);
                    }
                })
                ->whereIn('progress', $upcommingProgress)
                ->where(function($query){
                    $query->whereDate('date','>',Carbon::now()->toDateString());
                    $query->orWhere(function($subQuery){
                        $subQuery->whereDate('date','=',Carbon::now()->toDateString());
                        $subQuery->whereTime('time','>',Carbon::now()->toTimeString());
                    });
                })
                ->orderBy('id', 'DESC')->get();

                if(Request()->segment(2) === "v2"){
                    $getPastAppointment = Appointment::where('user_id', $userId)->where('progress', '!=', (new Constant)->APPOINTMENT_STATUS_PENDING)
                    ->where(function($query) use($doctorId){
                        if($doctorId){
                            $query->where('doctor_id', $doctorId);
                        }
                    })
                    ->where(function($query) use ($pastAppointmentStatus){
                        $query->orWhereIn('progress', $pastAppointmentStatus);
                        $query->orWhere(function($subQuery){
                            $subQuery->whereDate('date','<',Carbon::now()->toDateString());
                            $subQuery->orWhere(function($nestedQuery){
                                $nestedQuery->whereDate('date','=',Carbon::now()->toDateString());
                                $nestedQuery->whereTime('time','<',Carbon::now()->toTimeString());
                            });

                        });
                    })->orderBy('id', 'DESC')->paginate(5)->withQueryString();
                    return $this->returnResponse(200, '', [
                        'upcomming_appointments' => AppointmentResource::collection($getUpcommingAppointment),
                        'past_appointments' => AppointmentResource::collection($getPastAppointment),
                    ]);
                }else{
                    $getPastAppointment = Appointment::where('user_id', $userId)->where('progress', '!=', (new Constant)->APPOINTMENT_STATUS_PENDING)
                    ->where(function($query) use($doctorId){
                        if($doctorId){
                            $query->where('doctor_id', $doctorId);
                        }
                    })
                    ->where(function($query) use ($pastAppointmentStatus){
                        $query->orWhereIn('progress', $pastAppointmentStatus);
                        $query->orWhere(function($subQuery){
                            $subQuery->whereDate('date','<',Carbon::now()->toDateString());
                            $subQuery->orWhere(function($nestedQuery){
                                $nestedQuery->whereDate('date','=',Carbon::now()->toDateString());
                                $nestedQuery->whereTime('time','<',Carbon::now()->toTimeString());
                            });

                        });
                    })->orderBy('id', 'DESC')->get();
                    return $this->returnResponse(200, '', [
                        'upcomming_appointments' => AppointmentResource::collection($getUpcommingAppointment),
                        'past_appointments' => AppointmentResource::collection($getPastAppointment),
                    ]);
                }
            }else{
                $progress = [
                    $constant->APPOINTMENT_STATUS_PENDING,
                    $constant->APPOINTMENT_STATUS_PROCESSING,
                    $constant->APPOINTMENT_STATUS_COMPLETED,
                    $constant->APPOINTMENT_STATUS_CANCELLED,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_USER,
                    $constant->APPOINTMENT_STATUS_CANCELLED_BY_DOCTOR
                ];
            }
            if($request->status == "past")
            {
                $getAppointment = Appointment::where('user_id', $userId)
                ->where(function($query) use($doctorId){
                    if($doctorId){
                        $query->where('doctor_id', $doctorId);
                    }
                })
                ->where(function($query) use ($progress){
                    $query->orWhereIn('progress', $progress);
                    $query->orWhere(function($subQuery){
                        $subQuery->whereDate('date','<',Carbon::now()->toDateString());
                        $subQuery->orWhere(function($nestedQuery){
                            $nestedQuery->whereDate('date','=',Carbon::now()->toDateString());
                            $nestedQuery->whereTime('time','<',Carbon::now()->toTimeString());
                        });
                    });
                })->get();
            }
            elseif($request->status == "upcoming")
            {
                $getAppointment = Appointment::where('user_id', $userId)
                ->where(function($query) use($doctorId){
                    if($doctorId){
                        $query->where('doctor_id', $doctorId);
                    }
                })
                ->whereIn('progress', $progress)
                ->where(function($query){
                    $query->whereDate('date','>',Carbon::now()->toDateString());
                    $query->orWhere(function($subQuery){
                        $subQuery->whereDate('date','=',Carbon::now()->toDateString());
                        $subQuery->whereTime('time','>',Carbon::now()->toTimeString());
                    });
                })->get();
            }
            elseif($request->status == "cancelled")
            {
                $getAppointment = Appointment::where('user_id', $userId)
                    ->where(function($query) use($doctorId){
                        if($doctorId){
                            $query->where('doctor_id', $doctorId);
                        }
                    })
                    ->whereIn('progress', $progress)->get();
            } else {
                $getAppointment = Appointment::where('user_id', $userId)
                ->where(function($query) use($doctorId){
                    if($doctorId){
                        $query->where('doctor_id', $doctorId);
                    }
                })
                ->whereIn('progress', $progress)
                ->get();
            }
            return $this->returnResponse(200, '', AppointmentResource::collection($getAppointment));
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function returnSettingByKey($key)
    {
        return Settings::where('key', $key)->first()->value ?? null;
    }

    public function downloadPrescription(Request $request, $id)
    {
        try{
            $getAppointment = Appointment::with('getAppointmentPrescription','getPrescription')->find($id);
            if(!$getAppointment){
                return $this->returnResponse(400, 'Invalid appointment ID.');
            }
            if(Request()->segment(2) === 'v2'){
                $getUserDetail = User::find(\Auth::user()->id);
                if($getUserDetail){
                    if($getUserDetail->role_id == (new Constant)->USER_ROLE_ID){
                        if($getUserDetail->id != $getAppointment->user_id){
                            return $this->returnResponse(404, 'Invalid Appointment ID.');
                        }
                    }
                }
            }

            $getSettings['html'] = 1;
            $getSettings['facebook'] = $this->returnSettingByKey('facebook_link');
            $getSettings['instagram'] = $this->returnSettingByKey('instagram_link');
            $getSettings['youtube'] = $this->returnSettingByKey('youtube_link');
            $getSettings['twitter'] = $this->returnSettingByKey('twitter_link');
            $getSettings['linkedin'] = $this->returnSettingByKey('lindedin_link');
            $getSettings['uan_number'] = $this->returnSettingByKey('uan_number');
            $getSettings['email'] = $this->returnSettingByKey('email');

            $is_html = 1;
            $is_download = 0;
            if ($request->has('is_html')) {
                $is_html = $request->is_html;
            }
            if ($request->has('is_download') && $request->header('platform')!='app') {
                $folder = storage_path().'/app/public/prescription-downloads/';
                $public_folder = 'storage/prescription-downloads/';
                if(!File::isDirectory($folder)) {
                    File::makeDirectory($folder, 0777, true, true);
                }
                $filename = str_replace(' ', '', $getAppointment->patientname).'_appointment_'.$getAppointment->id.'_prescription_'.Carbon::parse($getAppointment->created_at)->format('d-m-Y_h.ia').'.pdf';
                $getSettings['html'] = 0;
                $title = str_replace(' ', '', $getAppointment->patientname).'_appointment_'.$getAppointment->id.'_prescription_'.Carbon::parse($getAppointment->created_at)->format('d-m-Y_h.ia').'';

                // $type = File::mimeType(public_path($public_folder.$filename));
    	        // $headers = array( 'Content-Type: ' . $type,);
                // return response()->download(public_path($public_folder.$filename));
                $filePath=public_path($public_folder.$filename);
                $pdf = mPDF::loadView('download.prescription', ['appointment' => $getAppointment, 'settings' => $getSettings],[], [
                    'title' => $title,
                    'author' => 'MeriSehat (Pvt) Ltd.',
                    'margin_top' => 70,
                    'margin_bottom' => 70,
                    'margin_footer' => 15,
                    'margin_header' => 15,
                ])->save($folder.$filename);
                if (file_exists($filePath)) {
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
                } else {
                    return response()->json(['error' => 'File not found.'], 404);
                }

            }elseif($request->has('is_download') && $request->header('platform')=='app'){
                $folder = storage_path().'/app/public/prescription-downloads/';
                $public_folder = 'storage/prescription-downloads/';
                if(!File::isDirectory($folder)) {
                    File::makeDirectory($folder, 0777, true, true);
                }
                $filename = str_replace(' ', '', $getAppointment->patientname).'_appointment_'.$getAppointment->id.'_prescription_'.Carbon::parse($getAppointment->created_at)->format('d-m-Y_h.ia').'.pdf';
                $getSettings['html'] = 0;
                $title = str_replace(' ', '', $getAppointment->patientname).'_appointment_'.$getAppointment->id.'_prescription_'.Carbon::parse($getAppointment->created_at)->format('d-m-Y_h.ia').'';
                $pdf = mPDF::loadView('download.prescription', ['appointment' => $getAppointment, 'settings' => $getSettings],[], [
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
            if ($is_html == 1) {
                return view('download.prescription', ['appointment' => $getAppointment, 'settings' => $getSettings]);
            } else {
                return $this->returnResponse(200, '', new AppointmentResource($getAppointment));
            }
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
    /**
     * This method is used to get single appoint deatil
     */
    public function getAppointmentDetail(Request $request, Appointment $appointment)
    {
        try{
            if(Request()->segment(2) === 'v2'){
                if($appointment->user_id != \Auth::user()->id && $appointment->doctor_id != \Auth::user()->id){
                    return $this->returnResponse(404, 'Invalid Appointment ID.');
                }
            }
            return $this->returnResponse(200, '', new AppointmentResource($appointment));
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function doctorNextAppointment(Request $request)
    {
        try{
            $user_id = $request->header('user_id');
            $doctorDetail = User::where('role_id', 3)->find($user_id);
            if($doctorDetail){
                $next_appointment = Appointment::where([
                    ['doctor_id', $user_id],
                    ['is_doctor_connected', 0],
                    ['call_started', null],
                    ['progress', (new Constant)->APPOINTMENT_STATUS_PENDING]
                ])->first();
                return $this->returnResponse(200, '', $next_appointment);
            }
            return $this->returnResponse(400, 'Unable to find a doctor.');
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }


    public function pendingAppointments(Request $request)
    {
        try{
            $appointment = new Appointment();
            if(Request()->segment(2) === "v2"){
                $userId = \Auth::user()->id;
            }else{
                $userId = $request->doctor_id ?? $this->getUserIdFromHeader($request->header());
            }
            $query = $appointment->select('id', 'user_id', 'progress', 'type', 'created_at')->where('doctor_id', $userId)->where('progress', $this->getConstantByValue('APPOINTMENT_STATUS_PENDING'))
            ->with([
                'user' => function($query) use ($userId){
                    $query->withCount(['appointment' => function($subQuery) use ($userId){
                        $subQuery->where(['doctor_id' => $userId, 'progress' => $this->getConstantByValue('APPOINTMENT_STATUS_COMPLETED')]);
                    }
                ]);
            }]);
            $appointmentListing = $query->orderBy('id', 'DESC')->get();
            return $this->returnResponse(200, '', $appointmentListing);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function allAppointments(Request $request)
    {
        try{
            $appointment = new Appointment();
            if(Request()->segment(2) === "v2"){
                $userId = \Auth::user()->id;
            }else{
                $userId = $request->doctor_id ?? $this->getUserIdFromHeader($request->header());
            }
            $progress = $request->has('progress') ? $request->progress : '';
            $past = $request->has('past') ? 'past' : null;
            $query = $appointment->where('doctor_id', $userId)
            ->with([
                'doctor.doctorDetail',
                'transactionDetails',
                'user' => function($query) use ($userId){
                    $query->withCount(['appointment' => function($subQuery) use ($userId){
                        $subQuery->where(['doctor_id' => $userId, 'progress' => $this->getConstantByValue('APPOINTMENT_STATUS_COMPLETED')]);
                    }
                ]);
            }]);
            if($request->has('search') && $request->search != ''){
                $query = $query->where('id', $request->search)
                        ->orWhere('user_id', $request->search)
                        ->orwhereHas('patientInfo', function ($query) use ($request){
                            $query->where('name',$request->search);
                        })->orWhere('type', $request->search)
                        ->orWhere('booked_via_subscription', $request->search);
            }
            if($request->has('appointment_id')){
                $query = $query->where('id', $request->appointment_id);
            }
            if($request->has('patient_id')){
                $query = $query->where('user_id', $request->patient_id);
            }
            if($request->has('patient_name')){
                $query = $query->whereHas('patientInfo', function ($query) use ($request){
                    $query->where('name',$request->patient_name);
                });
            }
            if($request->has('appointment_type')){
                $query = $query->where('type', $request->appointment_type);
            }
            if($request->has('payment_type')){
                $query = $query->where('booked_via_subscription', $request->payment_type);
            }
            if($request->has('subscription_id')){
                $query = $query->where('subscription_id', $request->subscription_id);
            }
            if($request->has('from')){
                $query = $query->whereDate('created_at', '<=', $request->from);
            }
            if($request->has('to')){
                $query = $query->whereDate('created_at', '<=', $request->to);
            }
            $limit = $request->has('limit') ? $request->limit : 10;
            if($progress){
                $query = $query->where('progress', $progress);
            }
            if($past == 'past')
                $query = $query->where('progress', '!=', $this->getConstantByValue('APPOINTMENT_STATUS_PENDING'));

            $appointmentListing = $query->orderBy('id', 'DESC')->paginate($limit);
            return $this->returnResponse(200, '', $appointmentListing);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }


    public function downloadAllAppointments(Request $request)
    {
        try{
            $appointment = new Appointment();
            // $userId = $request->doctor_id ?? $this->getUserIdFromHeader($request->header());
            if(Request()->segment(2) === "v2"){
                if(\Auth::user()) {
                    $userId = \Auth::user()->id;
                }
            }else{
                if($request->header('user_id')) {
                    $userId = $request->header('user_id');
                }
            }

            $progress = $request->has('progress') ? $request->progress : '';
            $past = $request->has('past') ? 'past' : null;
            $query = $appointment->where('doctor_id', $userId)
            ->with([
                'getAppointmentPrescription',
                'getPrescription',
                'doctor.doctorDetail',
                'transactionDetails',
                'user' => function($query) use ($userId){
                    $query->withCount(['appointment' => function($subQuery) use ($userId){
                        $subQuery->where(['doctor_id' => $userId, 'progress' => $this->getConstantByValue('APPOINTMENT_STATUS_COMPLETED')]);
                    }
                ]);
            }]);
            if($request->has('search') && $request->search != ''){
                $query = $query->where('id', $request->search)
                        ->orWhere('user_id', $request->search)
                        ->orwhereHas('patientInfo', function ($query) use ($request){
                            $query->where('name',$request->search);
                        })->orWhere('type', $request->search)
                        ->orWhere('booked_via_subscription', $request->search);
            }
            if($request->has('appointment_id')){
                $query = $query->where('id', $request->appointment_id);
            }
            if($request->has('patient_id')){
                $query = $query->where('user_id', $request->patient_id);
            }
            if($request->has('patient_name')){
                $query = $query->whereHas('patientInfo', function ($query) use ($request){
                    $query->where('name',$request->patient_name);
                });
            }
            if($request->has('appointment_type')){
                $query = $query->where('type', $request->appointment_type);
            }
            if($request->has('payment_type')){
                $query = $query->where('booked_via_subscription', $request->payment_type);
            }
            if($request->has('from')){
                $query = $query->whereDate('created_at', '<=', $request->from);
            }
            if($request->has('to')){
                $query = $query->whereDate('created_at', '<=', $request->to);
            }
            if($progress){
                $query = $query->where('progress', $progress);
            }
            $getAppointments = $query->orderBy('id', 'DESC')->get();

            $getSettings['html'] = 1;
            $getSettings['facebook'] = $this->returnSettingByKey('facebook_link');
            $getSettings['instagram'] = $this->returnSettingByKey('instagram_link');
            $getSettings['youtube'] = $this->returnSettingByKey('youtube_link');
            $getSettings['twitter'] = $this->returnSettingByKey('twitter_link');
            $getSettings['linkedin'] = $this->returnSettingByKey('lindedin_link');
            $getSettings['uan_number'] = $this->returnSettingByKey('uan_number');
            $getSettings['email'] = $this->returnSettingByKey('email');

            $is_html = 1;
            $is_download = 0;
            if ($request->has('is_html')) {
                $is_html = $request->is_html;
            }
            if ($request->has('is_download')) {
                $folder = storage_path().'/app/public/appointment-downloads/';
                $public_folder = 'storage/appointment-downloads/';
                if(!File::isDirectory($folder)) {
                    File::makeDirectory($folder, 0777, true, true);
                }
                $now = Carbon::now()->format('d-m-Y_h.ia');
                $filename = 'appointments_'.$now.'.csv';
                $filePath=public_path($public_folder.$filename);

                $getSettings['html'] = 0;
                $title = 'appointments_'.$now;
                $columnNames = [
                    'Appt ID', 'Patient Name', 'Date', 'Time Slot', 'Appointment Type', 'Amount',
                    'Cancellation Fee', 'Payment Type'
                ];

                $file = fopen(public_path($public_folder.$filename), 'w');
                fputcsv($file, $columnNames);

                foreach ($getAppointments as $key => $data) {
                    $row['Appt ID']                   = $data->id;
                    $row['Patient Name']                    = $data->user->name;
                    $row['Date']           = Carbon::parse($data->call_started)->format('Y-m-d');
                    $row['Time Slot']            = Carbon::parse($data->call_started)->format('H:i:s');
                    $row['Appointment Type']             = $data->type;
                    $row['Amount']   = $data->grand_total;
                    $row['Cancellation Fee']            = 'NA';
                    $row['Payment Type']             = $data->booked_via_subscription;
                    fputcsv($file, $row);
                    $dataForGoogleSheet[] = $row;
                }

                fclose($file);
                if($request->header('platform')=='app')
                {
                    return $this->returnResponse(200, 'Download Successfully', [
                        'pdf_file_name' => $filename,
                        'pdf_download_link' => url($public_folder.$filename)
                    ]);
                }else{
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
            if ($is_html == 1) {
                return view('download.appointment', ['appointments' => $getAppointments, 'settings' => $getSettings]);
            } else {
                return $this->returnResponse(200, '', $getAppointments);
            }
        }
        catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function paymentHistory(Request $request)
    {
        try{
            if(Request()->segment(2) === "v2"){
                $user = \Auth::user() ?? \Auth::guard("api")->user();
                $user_id = ($user == null) ? $request->user_id : $user->id;
            }else{
                $user_id = $request->user_id ? $request->user_id : $request->header('user-id');
            }
            $data = [];
            $doctorPayables = DoctorPayable::whereHas('doctorEarning', function($q) use($user_id){
                $q->where('doctor_id', $user_id);
            })
            ->where('status', 'paid')->get();
            foreach($doctorPayables as $key => $payable){
                $data[$key] = [
                    'date' => $payable->transaction_date,
                    'amount' => $payable->amount_paid,
                    'transaction_id' => $payable->bank_transaction_id,
                    'id' => $payable->id
                ];
            }
            $data = $this->paginate($data);
            $data->withPath($request->url());
            return $this->returnResponseWithListing(200, '', $data);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function deductionList(Request $request)
    {
        try{
            if(Request()->segment(2) === "v2"){
                $user = \Auth::user() ?? \Auth::guard("api")->user();
                $user_id = ($user == null) ? $request->user_id : $user->id;
            }else{
                $user_id = $request->user_id ? $request->user_id : $request->header('user-id');
            }
            $data = [];
            $deductions = Deduction::whereHas('doctorEarning', function($q) use($user_id){
                $q->where('doctor_id', $user_id);
            })
            ->where('status', 'deduct')->get();
            foreach($deductions as $key => $deduction){
                $data[$key] = [
                    'date' =>  Carbon::parse($deduction->created_at)->format('Y-m-d'),
                    'amount' => $deduction->amount_paid,
                    'id' =>  $deduction->id
                ];
            }
            $data = $this->paginate($data);
            $data->withPath($request->url());
            return $this->returnResponseWithListing(200, '', $data);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function paymentHistoryDetail(Request $request, $id)
    {
        try{
            if(Request()->segment(2) === "v2"){
                $user = \Auth::user() ?? \Auth::guard("api")->user();
                $user_id = ($user == null) ? $request->user_id : $user->id;
            }else{
                $user_id = $request->user_id ? $request->user_id : $request->header('user-id');
            }
            $data = [];
            $appointmentPayables = AppointmentPayable::whereHas('doctorPayables', function($q) use($user_id){
                $q->whereHas('doctorEarning', function($query) use($user_id){
                    $query->where('doctor_id', $user_id);
                });
            })
            ->with('appointment')->where('doctor_payable_id', $id)->get();
            foreach($appointmentPayables as $key => $payable){
                $data[$key] = [
                    'date' => Carbon::parse($payable->created_at)->format('Y-m-d'),
                    'consultation_fees' => $payable->appointment->consultation_fee,
                    'appointment_id' => $payable->appointment_id
                ];
            }
            $data = $this->paginate($data);
            $data->withPath($request->url());
            return $this->returnResponseWithListing(200, '', $data);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }

    public function deductionListDetail(Request $request, $id)
    {
        try{
            if(Request()->segment(2) === "v2"){
                $user = \Auth::user() ?? \Auth::guard("api")->user();
                $user_id = ($user == null) ? $request->user_id : $user->id;
            }else{
                $user_id = $request->user_id ? $request->user_id : $request->header('user-id');
            }
            $constant = new Constant();
            $data = [];
            $deductions = AppointmentDeduction::whereHas('deduction', function($q) use($user_id){
                $q->whereHas('doctorEarning', function($query) use($user_id){
                    $query->where('doctor_id', $user_id);
                });
            })
            ->with('appointment')
            ->where('deduction_id', $id)->get();
            foreach($deductions as $key => $deduction){
                $consultation_penalty = '';
                if($deduction->appointment->type == $constant::APPOINTMENT_TYPE_INSTANT){
                    $consultation_penalty = Settings::getValue('instant_consultation_penalty_charges');
                }
                else if($deduction->appointment[0]->type == $constant::APPOINTMENT_TYPE_IN_PERSON){
                    $consultation_penalty = Settings::getValue('clinic_visits_penalty_charges');
                }
                $data[$key] = [
                    'date' =>  Carbon::parse($deduction->created_at)->format('Y-m-d'),
                    'consultation_penalty' => $consultation_penalty,
                    'id' =>  $deduction->id
                ];
            }
            $data = $this->paginate($data);
            $data->withPath($request->url());
            return $this->returnResponseWithListing(200, '', $data);
        } catch(\Exception $e) {
            return $this->returnResponse(500, $e->getMessage());
        }
    }
}
