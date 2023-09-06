<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Common\Constant;
use Illuminate\Support\Facades\Storage;


class InstantMedicalRecordFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $filename=explode('/', $this->file);
        if(Storage::disk('s3')->has($this->file)){
            $metadatafind=Storage::disk('s3')->getMetadata($this->file);
        }

        return [
            'id' => $this->id,
            'instant_medical_record_id' => $this->instant_medical_record_id,
            'file' => env('ASSETS_STORAGE').$this->file,
            'filename' => isset($metadatafind['metadata']['medicalrec-metadata']) ? $metadatafind['metadata']['medicalrec-metadata'] : ( isset($filename[1]) ? $filename[1] : $this->file ),
            'prescription_element_type' => $this->prescriptionElementType ? $this->prescriptionElementType :null ,
            'prescription_element_type_id' => $this->prescription_element_type_id,

            'uploaded_by' => $this->uploaded_by,
            'uploaded_user' => new UserDetailResource($this->uploadedUser),
        ];
    }
}
