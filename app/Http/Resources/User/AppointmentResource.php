<?php

namespace App\Http\Resources\User;

use App\Http\Common\Constant;
use App\Models\{PatientInfo, ClinicTiming};
use App\Models\{Review, Appointment};
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $date = \Carbon\Carbon::parse($this->date)->format('M d');
        $day = \Carbon\Carbon::createFromFormat('m/d/Y', date('m/d/Y', strtotime($date)))->format('l');
        if($this->date == date('Y-m-d')){
            $dateTime = "Today, " . date('h:i a', strtotime($this->time));
        }else{
            $dateTime = $day . ', ' . $date . ' ' . date('h:i a', strtotime($this->time));
        }
        $getRview = Review::where(['user_id' => $request->header('user_id'), 'appointment_id' => $this->id])->first();


        $getTimings = ClinicTiming::where(
            ['doctor_clinic_id' => $this->doctor_clinic_id ,
             'day' => strtolower(date("l" , strtotime($this->date))),
             'start_time' => $this->time
            ])->first();


        $time = strtotime($this->date." ".$this->time)  - strtotime("now");
        if(!$getTimings){
            $time = 0;
        }
        elseif(strtotime($this->date." ".$this->time) - strtotime("now") < 0){
            $time = strtotime($this->date." ".$getTimings->end_time) - strtotime("now");
        }


        if($this->type == Constant::APPOINTMENT_TYPE_INSTANT){
            $time = Constant::APPOINTMENT_INSTANT_TIME;
        }
        $prescription_here = '';
        $prescriptionHere = $this->getAppointmentPrescription;
        foreach($prescriptionHere as $pres){
            $prescription_here .= $pres->prescription . "\n";
        }
        if($prescription_here == ''){
            $prescription_here = null;
        }
        $date=\Carbon\Carbon::parse($this->date)->format('d/m/Y');    
        $doctor_id = 0;
        if($request->user()){
            $doctor_id = $request->user()->id;
        }
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'doctor_id' => $this->doctor_id,
            'clinic_id' => $this->doctor_clinic_id,
            'family_member_id' => $this->family_member_id,
            'consultation_fee' => $this->consultation_fee,
            'reason' => $this->reason,
            'type' => $this->type,
            'appointment_type' => $this->type == "instant-consultation" ? "Doctor Now" : $this->type,
            'date' => $date,
            'time' => date('h:i a', strtotime($this->time)),
            'date_time' => $dateTime,
            //'remaining_time' => ( strtotime($this->date." ".$this->time) - strtotime("now")  < 0 ) ? 0 : strtotime($this->date." ".$this->time) - strtotime("now"),
            'remaining_time' => $this->calculateTime(( $time  < 0 ) ? 0 : $time, $this->call_started),
            'formatted_date_time' => $this->date . ' ' . $this->time,
            'progress' => $this->progress,
            'action_by' => $this->action_by,
            'is_paid' => $this->is_paid,
            'status' => $this->status,
            'prescription_here' => $prescription_here ? $prescription_here : null,
            'prescription' => $this->getPrescription,
            'review' => $getRview ?? null,
            'clinic' => $this->doctorClinic ? $this->doctorClinic->clinic : null,
            'doctor' => new UserSummeryResource($this->doctor),
            'user' => new UserSummeryResource($this->user),

            'previous_consultation' => $this->getPreviousConsultation($this),
            'medicine' => $this->medicine,
            'lab_test' => $this->lab_test,
            'transaction_details' => $this->transactionDetails,
            'patient_info' => $this->getPatientInfoDetail($this->id),
            'conditions' => $this->getCondition,
            'reason_for_visit' => $this->reason_for_visit,
            'additional_detail' => $this->additional_detail,
            'patient_count' => Appointment::where('doctor_id', $doctor_id)->where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->count() > 1 ? 1 : 0 
        ];
    }

    private function getPreviousConsultation($appointment){
        return Appointment::where([
            ['user_id', $appointment->user_id],
            // ['doctor_id', $appointment->doctor_id],
            ['progress', 'completed'],
            ['id', '<', $appointment->id],          
        ])->with(['getPrescription', 'doctor.doctorDetail'])->orderBy('id', 'desc')->take(5)->get();
    }

    private function getPatientInfoDetail($appointmentId){
        return PatientInfo::where('appointment_id', $appointmentId)->first();
    }

    private function calculateTime($time = 0, $call_started = null){
        if($time != 0 && $call_started != null){
            $start  = new Carbon($call_started);
            $end    = Carbon::now();
            $call_started_diff = $start->diffInSeconds($end);
            $remaining_time = $time - $call_started_diff;
            return $remaining_time < 0 ? 0 : $remaining_time;
        }
        return $time;
    }
}
