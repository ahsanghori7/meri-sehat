<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Common\Constant;
use App\Http\Resources\Article\ArticleSummeryResource;
use App\Models\Review;

class ClinicResource extends JsonResource
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
            'id' => $this->clinic->id,
            'name' => $this->clinic->name,
        ];
    }
}
