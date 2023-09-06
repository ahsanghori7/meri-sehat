<?php

namespace App\Http\Resources\Disease;

use App\Http\Resources\Article\ArticleWidgetResource;
use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class DiseaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->lang_id == 1){
            $redirectUrl = "/disease/$this->slug";
        }else{
            $getLanguage = Language::find($this->lang_id);
            $redirectUrl = "/$getLanguage->slug/disease/$this->slug";
        }
        return [
            'id' => $this->id,
            'translation_of' => $this->translation_of,
            'lang_id' => $this->lang_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'redirect_url' => $redirectUrl,
            'status' => (boolean) $this->status,

            'widgets' => ArticleWidgetResource::collection($this->diseaseWidgetDetails),
        ];
    }
}
