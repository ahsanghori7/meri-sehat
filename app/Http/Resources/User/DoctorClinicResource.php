<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorClinicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $clinicTimings = $this->clinicTimings;
        $clinicDetail = [];
        if($clinicTimings){
            foreach($clinicTimings as $key =>  $clinicTiming){
                $dateTime = Carbon::parse($clinicTiming->day.' '.$clinicTiming->start_time);
                $end_date_time = Carbon::parse($clinicTiming->day.' '.$clinicTiming->end_time);
                if($end_date_time->lte(Carbon::now())){
                    $clinicDetail[$key]['date'] = $dateTime->addWeek();
                    $clinicDetail[$key]['is_physical'] = $clinicTiming->is_physical;
                }else{
                    $clinicDetail[$key]['date'] = $dateTime;
                    $clinicDetail[$key]['is_physical'] = $clinicTiming->is_physical;
                }
            }
        }
        sort($clinicDetail);
        $dateTime = count($clinicDetail) ? "Available " . ($clinicDetail[0]['is_physical'] ? Carbon::parse($clinicDetail[0]['date'])->calendar() : Carbon::parse($clinicDetail[0]['date'])->isoFormat('ddd, MMM DD')) : null;
        $next_available_date = count($clinicDetail) ? ($clinicDetail[0]['is_physical'] ? Carbon::parse($clinicDetail[0]['date'])->isoFormat('YYYY-MM-DD') : Carbon::parse($clinicDetail[0]['date'])->isoFormat('YYYY-MM-DD')) : null;
        return [
            'id' => $this->id,
            'consultation_fee' => $this->consultation_fee,
            'consultation_duration' => $this->consultation_duration,
            'name' => isset($this->clinic->name) ? $this->clinic->name : "Online Consultation",
            'clinic_timings' => $dateTime,
            'next_available_date' => $next_available_date,
            'is_physical' => count($clinicDetail) ? $clinicDetail[0]['is_physical'] : null,
            // 'clinic_timing_detail' => $this->clinicTimings,
        ];
    }
}
