<?php

namespace App\Http\Resources\User;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Common\Constant;
use App\Http\Resources\Article\ArticleSummeryResource;

class UserDoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $city = null;
        if ($this->city) {
            $city = $this->city->name;
        }
        return [
            'id' => $this->id,
            'name' => $this->name ?? null,
            'city' => $city,
//            'type' => $this->type ?? null,
//            'type_data' => $this->type_data ?? null,
//            'user_data' => $getUserData ?? null,
//            'ref_data' => $getRefData ?? null,
        ];
    }
}
