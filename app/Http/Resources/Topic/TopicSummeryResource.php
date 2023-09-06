<?php

namespace App\Http\Resources\Topic;

use App\Http\Resources\User\UserSummeryResource;
use App\Models\{Language, Page};
use Illuminate\Http\Resources\Json\JsonResource;

class TopicSummeryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $getPageDetails = Page::where(['reference_type' => 'topic', 'reference_id' => $this->id])->first();
        if($getPageDetails){
            $slug = $getPageDetails->slug;
        }else{
            $slug = $this->slug;
        }
        if($this->lang_id == 1){
            $redirectUrl = "/$slug";
        }else{
            $getLanguage = Language::find($this->lang_id);
            $redirectUrl = "/$getLanguage->slug/$slug";
        }
        $current_uri = request()->segments();
        $slug = explode('/', $this->slug);
        if (count($current_uri) > 4) {
            if (isset($current_uri[4]) == 'wellness') {
                if($this->lang_id == 1) {
                    $redirectUrl = '/article-listing/' . end($slug);
                    $this->slug = '/article-listing/' . end($slug);
                } else {
                    $redirectUrl = '/article-listing/'. $getLanguage->slug . '/' . end($slug);
                    $this->slug = '/article-listing/' . end($slug);
                }
            }
        }
        $redirectUrl_array = explode('/', $redirectUrl);
        $outer_image_url = '';
        $outer_image = '';
        if (last(request()->segments()) == 'home-new' || last(request()->segments()) == 'home') {
            if ($this->outer_home_image != '' && $this->outer_image != '') {
                $outer_image_url = env('ASSETS_STORAGE'). $this->outer_home_image;
                $outer_image = $this->outer_home_image;
            } elseif ($this->outer_home_image != '' && $this->outer_image == '') {
                $outer_image_url = env('ASSETS_STORAGE').$this->outer_home_image;
                $outer_image = $this->outer_home_image;
            } elseif ($this->outer_home_image == '' && $this->outer_image != '') {
                $outer_image_url = env('ASSETS_STORAGE').$this->outer_image;
                $outer_image = $this->outer_image;
            }
        } else {
            if ($this->outer_image != '') {
                $outer_image_url =env('ASSETS_STORAGE').$this->outer_image;
                $outer_image = $this->outer_image;
            }
        }

        return [
            'id' => $this->id,
            'translation_of' => $this->translation_of,
            'status' => $this->status,
            'lang_id' => $this->lang_id,
            'parent_id' => $this->parent_id,
            'created_by' => $this->created_by,

            'sequence' => $this->sequence,
            'meta_keyword' => $this->meta_keyword,
            'meta_description' => $this->meta_description,
            'title' => $this->title,
            'slug' => $this->slug,
            'redirect_url' => $redirectUrl,
            'listing_url' => $redirectUrl_array ? end($redirectUrl_array) : null,
            'color_code' => $this->color_code,
//            'outer_image' => $this->outer_image ? url("storage/" . $this->outer_image) : "",
            'outer_image' => $outer_image_url,
            'outer_text' => $this->outer_text,
            'banner_text' => $this->banner_text,
            'status' => (bool) $this->status,

            'name' => $this->title,
//            'image' => $this->outer_image ? url("storage/" . $this->outer_image) : "",
            'image' => $outer_image_url,
            'descripton' => $this->meta_description,
        ];
    }
}
