<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSubscriptionResource extends JsonResource
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
            'subscription_id' => $this->subscription_id,
            'receipt_data' => $this->receipt_data,
            'start_date' => $this->start_date ? Carbon::parse($this->start_date)->format('Y-m-d') : null,
            'end_date' => $this->end_date ? Carbon::parse($this->end_date)->format('Y-m-d') : null,
            'is_paid' => (boolean) $this->is_paid,
            'status' => (boolean) $this->status,
            'is_social_login' => $this->user->socialAccounts->count() > 0 ? true : false,

            'user' => $this->user_id ? new UserSummeryResource($this->user) : null,
            'subscribed_package' => $this->package,
        ];
    }
}
