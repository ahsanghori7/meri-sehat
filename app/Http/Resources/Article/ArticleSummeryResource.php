<?php

namespace App\Http\Resources\Article;

use App\Models\{InfoModal, Language};
use Illuminate\Http\Resources\Json\JsonResource;
use File;

class ArticleSummeryResource extends JsonResource
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
            $redirectUrl = "/article/$this->slug";
        }else{
            $getLanguage = Language::find($this->lang_id);
            $redirectUrl = "/$getLanguage->slug/article/$this->slug";
        }
        if($this->articleLabel){
            $labelDetail = InfoModal::where(['key' => \Str::slug($this->articleLabel->articleFact->name), 'lang_id' => 1])->first();
        }
        if($url=@getimagesize(env('ASSETS_STORAGE').$this->image)){
            $imagePath =env('ASSETS_STORAGE'). $this->image;
        }else{
            $imagePath =env('ASSETS_STORAGE')."assets/img/default.png";
        }
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'descripton' => $this->descripton,
            'is_description_show' => $this->is_description_show,
            'hide_image_in_detail' => $this->hide_image_in_detail,
            'slug' => $this->slug,
            'redirect_url' => $redirectUrl,
            'image' => $imagePath,
            'status' => $this->status,
            'like' => '300+',
            'read_time' => 20,
            'updated_on' => date('F j, Y', strtotime($this->updated_at)),
            'label' => $this->articleLabel && isset($labelDetail) ? [
                'value' => $this->articleLabel->articleFact->name,
                'color' => $this->articleLabel->articleFact->color,
                'key' => $labelDetail->key,
                'description' => $labelDetail->content,
            ] : null,
        ];
    }
}
