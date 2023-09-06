<?php

namespace App\Http\Resources\Page;

use App\Http\Common\Constant;
use Illuminate\Http\Resources\Json\JsonResource;

class PageWidgetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $platformType = ($request->header('platform') == "web") ? $this->widget->web_key : $this->widget->mobile_key;

        return [
            'id' => $this->id,
            'sequence' => $this->sequence,
            'status' => $this->status,
            'widget_id' => $this->widget_id,
            'key_type' => $platformType,
            'data' => $this->getWidgetById($this->widget_id),
        ];
    }

    public function getWidgetById($id)
    {
        switch($id){
            case Constant::BANNER_WIDGET:
                return $this->banners;
            case Constant::RESOURCE_WIDGET:
                return $this->resources;
        }
    }
}
