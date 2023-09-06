<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Common\Constant;

class FamilyMemberResource extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email ?? "",
            'image' => $this->image ? url(Constant::FILE_UPLOAD_PATH . "$this->user_id/$this->image") : "",
            'relationship' => $this->relationship ?? "",
            'gender' => $this->gender ?? "",
            'height' => ($this->height != "-") ? $this->height : null,
            'weight' => $this->weight ?? "",
            'birth_date' => $this->birth_date ?? "",
            'status' => $this->status,

            'user' => new UserDetailResource($this->user)
        ];
    }
}
