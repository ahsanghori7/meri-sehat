<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class ShareMedicalRecord extends JsonResource
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
            'user_id' => $this->user_id,
            'doctor_id' => $this->doctor_id,
            'medical_record_id' => $this->medical_record_id,
            'type' => $this->type,
            'uploaded_by' => $this->uploaded_by,
            'status' => (boolean) $this->status,

            'doctor' => new UserDetailResource($this->doctor),
            'user' => new UserDetailResource($this->user),
            'uploadedBy' => new UserDetailResource($this->uploadedBy),
        ];
    }
}
