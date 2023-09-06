<?php

namespace App\Http\Resources\Topic;

use App\Http\Resources\User\UserSummeryResource;
use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicDetailResource extends JsonResource
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
            $redirectUrl = "/$this->slug";
        }else{
            $getLanguage = Language::find($this->lang_id);
            $redirectUrl = "/$getLanguage->slug/$this->slug";
        }
        $outer_image_url = '';
        $outer_image = '';
        if (last(request()->segments()) == 'home-new' || last(request()->segments()) == 'home') {
            if ($this->outer_home_image != '' && $this->outer_image != '') {
                $outer_image_url =  env('ASSETS_STORAGE').$this->outer_home_image;
                $outer_image = $this->outer_home_image;
            } elseif ($this->outer_home_image != '' && $this->outer_image == '') {
                $outer_image_url = env('ASSETS_STORAGE').$this->outer_home_image;
                $outer_image = $this->outer_home_image;
            } elseif ($this->outer_home_image == '' && $this->outer_image != '') {
                $outer_image_url = env('ASSETS_STORAGE'). $this->outer_image;
                $outer_image = $this->outer_image;
            }
        } else {
            if ($this->outer_image != '') {
                $outer_image_url = env('ASSETS_STORAGE'). $this->outer_image;
                $outer_image = $this->outer_image;
            }
        }

        return [
            'id' => $this->id,
            'translation_of' => $this->translation_of,
            'lang_id' => $this->lang_id,
            'parent_id' => $this->parent_id,
            'sequence' => $this->sequence,
            'meta_keyword' => $this->meta_keyword,
            'meta_description' => $this->meta_description,
            'title' => $this->title,
            'slug' => $this->slug,
            'color_code' => $this->color_code,
            'outer_image' => $outer_image_url,
            'outer_text' => $this->outer_text,
            'banner_text' => $this->banner_text,
            'status' => (bool) $this->status,
            'created_by' => $this->created_by,
            'visit_counts' => $this->visit_counts,
            'outer_home_image' => $this->outer_home_image,
            'outer_home_image_url' => env('ASSETS_STORAGE'). $this->outer_home_image,
            'e_id' => $this->e_id,
            'banner_image_url' => $this->banner_image_url,
            'redirect_url' => $redirectUrl,
            'name' => $this->title,
            'image' => $outer_image_url,
            'descripton' => $this->meta_description,
            'created_by_user' => new UserSummeryResource($this->createdUser),
        ];
    }
}
