<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Common\Constant;

class MedicalRecordFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'medical_record_id' => $this->medical_record_id,
            'file' => env('ASSETS_STORAGE').$this->file,
            
            'prescription_element_type' => ($this->prescriptionElementType)->name,
            'prescription_element_type_id' => $this->prescription_element_type_id,

            'uploaded_by' => $this->uploaded_by,
            'uploaded_user' => new UserDetailResource($this->uploadedUser),
        ];
    }
}
