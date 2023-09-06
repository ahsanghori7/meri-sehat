<?php

namespace App\Http\Resources\User;

use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Common\Constant;
use App\Models\PrescriptionElementType;
use App\Http\Common\MedicalRecordHelper;

class MedicalRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $conditions = '';
        if ($this->prescription_id) {
            $prescription = Prescription::where('id', $this->prescription_id)->where('status', 1)->first();
            $appointment = Appointment::where('id', $prescription->appointment_id)->first();
            $conditions = $appointment->getCondition()->get();
        }
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'prescription_id' => $this->prescription_id,
            'conditions' => $conditions,
            'file_name' => $this->file_name,
            'reports_for_user' => $this->family_member_id
                ? $this->familyMember->name . ' (' . ucfirst($this->familyMember->relationship) . ')'
                : $this->user->name  . ' (My Self)',

            'doctor_id' => $this->doctor_id,
            'total_reports' => MedicalRecordHelper::getTotalReports($this->id),
            'shared_with_doctors_count' => count($this->sharedMedicalReport),
            'date' => $this->date,
            'status' => (boolean) $this->status,

            'family_member' => new FamilyMemberResource($this->familyMember),
            'medical_record_files' => MedicalRecordFileResource::collection($this->medicalRecordFiles),
            'shared_with_doctors' => ShareMedicalRecord::collection($this->sharedMedicalReport),
            'created_at' => $this->created_at
        ];
    }
}
