<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $getAppointment = $this->appointment;
        $getPrescribed = $this->appointment;
        return [
            'id' => $this->id,
            'user_id' => $getAppointment->user_id,
            'doctor_id' => $this->doctor_id,
            'appointment_id' => $getAppointment->id,
            'clinic_id' => $getAppointment->clinic_id,
            'family_member_id' => $getAppointment->family_member_id,
            'consultation_fee' => $getAppointment->consultation_fee,
            'type' => $getAppointment->type,
            'date' => $getAppointment->date,
            'time' => $getAppointment->time,
            'progress' => $getAppointment->progress,
            'reason' => $getAppointment->reason,
        ];
    }
}
