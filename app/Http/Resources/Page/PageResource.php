<?php

namespace App\Http\Resources\Page;

use App\Http\Resources\Article\ArticleSummeryResource;
use App\Http\Resources\Article\ArticleWidgetResource;
use App\Http\Resources\Topic\TopicDetailResource;
use App\Http\Resources\User\UserSummeryResource;
use App\Models\Ads;
use App\Models\Article;
use App\Models\Language;
use App\Models\Topics;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $getTags = $this->pageTags->pluck('tag_id')->toArray();
        if ($request->headers->has('locale')) {
            $getRelated = Article::where('status', true)->where('lang_id', $request->header('locale'))->where('id', '!=', $this->id)->whereHas('articleTags', function ($query) use ($getTags) {
                $query->whereIn('tag_id', $getTags);
            })->withCount('articleTags')->orderBy('article_tags_count', 'desc')->get();
        } else {
            $getRelated = Article::where('status', true)->where('lang_id', 1)->where('id', '!=', $this->id)->whereHas('articleTags', function ($query) use ($getTags) {
                $query->whereIn('tag_id', $getTags);
            })->withCount('articleTags')->orderBy('article_tags_count', 'desc')->get();
        }
//        $request->headers->set('locale', 1);


        if($this->reference_type == 'disease'){
            if($this->lang_id == 1){
                $redirectUrl = "/disease/$this->slug";
            }else{
                $getLanguage = Language::find($this->lang_id);
                $redirectUrl = "/$getLanguage->slug/disease/$this->slug";
            }
        }elseif($this->reference_type == 'drug'){
            if($this->lang_id == 1){
                $redirectUrl = "/drug/$this->slug";
            }else{
                $getLanguage = Language::find($this->lang_id);
                $redirectUrl = "/$getLanguage->slug/drug/$this->slug";
            }
        }elseif($this->reference_type == 'topic'){
            if($this->lang_id == 1){
                $redirectUrl = "/$this->slug";
            }else{
                $getLanguage = Language::find($this->lang_id);
                $redirectUrl = "/$getLanguage->slug/$this->slug";
            }
        }else{
            if($this->lang_id == 1){
                $redirectUrl = "/page/$this->slug";
            }else{
                $getLanguage = Language::find($this->lang_id);
                $redirectUrl = "/$getLanguage->slug/page/$this->slug";
            }
        }

        $data = [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'lang_id' => $this->lang_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'class_name' => $this->class_name,
            'meta_name' => $this->meta_name,
            'meta_description' => $this->meta_description,
            'canonical_link' => isset($this->canonical_link) ? $this->canonical_link : null,
            'canonical_title' => isset($this->canonical_title) ? $this->canonical_title : null,
            'canonical_description' => isset($this->canonical_description) ? $this->canonical_description : null,
            'seo_image' => $this->seo_image,
            'hidden_description' => $this->hidden_description,
            'redirect_url' => $redirectUrl,
            'descripton' => $this->descripton,
            'is_description_show' => $this->is_description_show,
            'keywords' => $this->keywords,
            'status' => $this->status,
            'image' => $this->image ? env('ASSETS_STORAGE').$this->image : "",
            'hide_image_in_detail' => $this->hide_image_in_detail ?? 0,
            'visit_counts' => $this->visit_counts ?? 0,
            // 'ads' => $this->ad_window_id ? $this->getRandomAds($this->ad_window_id) : null,

            'updated_on' => date('F j, Y', strtotime($this->updated_at)),
            'reviewed_by' => $this->written_by ? new UserSummeryResource($this->reviewedUser) : null,
            'written_by' => $this->written_by ? new UserSummeryResource($this->writtenUser) : null,
            'related' => ArticleSummeryResource::collection($getRelated->take(6)->all()),
            'widgets' => ArticleWidgetResource::collection($this->pageWidgetDetails),
//            'widgets_with_multiple' => ArticleWidgetResource::collection($this->pageWidgetDetails),
        ];
        if($this->reference_type == 'topic' && !str_contains($this->slug, '/')){
            $data['sub-categories'] = TopicDetailResource::collection(Topics::find($this->reference_id)->children);
        }

        return $data;
    }

    public function getRandomAds($ad_window_id)
    {
        return Ads::where('ad_window_id', $ad_window_id)->random()->first();
    }
}
