<?php

namespace App\Http\Resources\User;

use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Common\Constant;
use App\Models\PrescriptionElementType;
use App\Models\{SharedMedicalReport,
    InstantMedicalRecordFile, User};
use App\Http\Common\InstantMedicalRecordHelper;

class InstantMedicalRecordResource extends JsonResource
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
        $share_medical_record=SharedMedicalReport::where('instant_medical_record_id',$this->id)->first();
        if(!empty($share_medical_record->instant_medical_record_id)){
            $share_medical_record=InstantMedicalRecordFile::where('instant_medical_record_id',$this->id)->get();
        }
        else{
            $share_medical_record=null;
        }
        $doctor_name ='';
        $doctor =  User::find($this->doctor_id);
        $doctor_id = '';
        if($doctor){
            $doctor_id = $doctor->id;
        }
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'prescription_id' => $this->prescription_id,
            'conditions' => $conditions ? $conditions : null,
            'doctor_id' => $this->doctor_id,
            'doctor_name' => isset($doctor) ? $doctor->name : '',
            'total_reports' => InstantMedicalRecordHelper::getTotalReports($this->id),
            'shared_with_doctors_count' => count($this->sharedMedicalReport),
            'date' => $this->date,
            'status' => (boolean) $this->status,
            'file_name' => $this->filename,
            'family_member' => new FamilyMemberResource($this->familyMember),
            'instant_medical_record_files' => InstantMedicalRecordFileResource::collection($this->instantMedicalRecordFiles),
            'shared_with_doctors' => $share_medical_record,
            'patient_count' => Appointment::where('doctor_id', $doctor_id)->where('progress', (new Constant)->APPOINTMENT_STATUS_PENDING)->count() > 1 ? 1 : 0
        ];
    }
}
