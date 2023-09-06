<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'name' => $this->name,
            'duration' => $this->duration,
            'duration_yearly' => $this->duration_yearly,
            'rules' => json_decode($this->rules),
            'duration_text' => $this->duration_text,
            'duration_text_yearly' => $this->duration_text_yearly,
            'color_code' => $this->color_code ?? "",
            'price' => $this->price ?? "",
            'price_yearly' => $this->price_yearly ?? "",
            'discounted_price' => $this->discounted_price ?? null,
            'discounted_price_yearly' => $this->discounted_price_yearly ?? null,
            'discounted_percent_yearly' => $this->discounted_percent_yearly ?? null,
            'description' => $this->description ?? "",
            'addon_heading' => $this->addon_heading ?? "",
            'addon_text' => $this->addon_text ?? "",
            'is_starred' => (boolean) $this->is_starred,
            'status' => (boolean) $this->status,
        ];
    }
}
