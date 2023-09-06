<?php

namespace App\Http\Resources\User;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Common\Constant;
use App\Http\Resources\Article\ArticleSummeryResource;

class UserNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $getUserData = null;
        $getRefData = null;
        if ($this->user_id) {
            $getUser = User::find($this->user_id);
            $getUserData = [
                'name' => $getUser->name ?? null,
                'email' => $getUser->email ?? null,
            ];
        }
        if ($this->ref_id) {
            $getRef = User::find($this->ref_id);
            $getRefData = [
                'name' => $getRef->name ?? null,
                'email' => $getRef->email ?? null,
            ];
        }
        $array_subscription = [
            'user_subscriptions_last_day',
            'user_subscriptions_expiring_in_3_days',
            'user_subscriptions_active',
            'user_subscriptions_expired',
        ];
        $array_appointment = [
            'appointment_user_cancelled',
            'appointment_doctor_cancelled',
            'appointment_feedback',
        ];
        $array_article = ['article'];
        $key = null;
        if (in_array($this->type, $array_subscription)) {
            $key = 'subscription';
        } elseif (in_array($this->type, $array_appointment)) {
            $key = 'appointment';
        } elseif (in_array($this->type, $array_article)) {
            $key = 'article';
        }

        return [
            'id' => $this->id,
            'ref_id' => $this->ref_id ?? null,
            'title' => $this->title ?? null,
            'sub_title' => $this->sub_title ?? null,
            'text' => $this->text ?? null,
            'type' => $this->type ?? null,
            'type_data' => $this->type_data ?? null,
            'user_data' => $getUserData ?? null,
            'ref_data' => $getRefData ?? null,
            'read_status' => $this->read_status,
            'key' => $key,
            'created_at' => $this->getTimeAgo($this->created_at). ' at '.Carbon::parse($this->created_at)->isoFormat('hh:mma')
//            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
