<?php

namespace App\Http\Resources\Article;

use App\Http\Resources\User\{UserSummeryResource};
use App\Models\{Article, InfoModal, Language, UserArticleReview};
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $getTags = $this->articleTags->pluck('tag_id')->toArray();
        $getRelated = Article::where(['status' => true, 'lang_id' => $request->header('locale')])->where('id', '!=', $this->id)->whereHas('articleTags', function($query) use($getTags){
            $query->whereIn('tag_id', $getTags);
        })->withCount('articleTags')->orderBy('article_tags_count', 'desc')->get();

        $review_positive = UserArticleReview::where('article_id', $this->id)->where('is_like', 1)->get();
        $review_negative = UserArticleReview::where('article_id', $this->id)->where('is_like', 0)->get();
        $review_positive_count = count($review_positive);
        $review_native_count = count($review_negative);
        $reviews = [
            'positive_reviews' => $review_positive_count,
            'negative_reviews' => $review_native_count
        ];

        if($this->lang_id == 1){
            $redirectUrl = "/article/$this->slug";
        }else{
            $getLanguage = Language::find($this->lang_id);
            $redirectUrl = "/$getLanguage->slug/article/$this->slug";
        }
        if($this->articleLabel){
            $labelDetail = InfoModal::where(['key' => \Str::slug($this->articleLabel->articleFact->name), 'lang_id' => $request->header('locale')])->first();
        }
        return [
            'id' => $this->id,
            'approved_by' => $this->approved_by,
            'written_by' => $this->written_by,
            'parent_id' => $this->parent_id,
            'lang_id' => $this->lang_id,
            'type' => $this->type,
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
            'hide_image_in_detail' => $this->hide_image_in_detail,
            'visit_counts' => $this->visit_counts ?? 0,
            'keywords' => $this->keywords,
            'image' => $this->image ? env('ASSETS_STORAGE'). $this->image: "",
            'status' => $this->status,
            'label' => $this->articleLabel && isset($labelDetail) ? [
                'value' => $this->articleLabel->articleFact->name,
                'color' => $this->articleLabel->articleFact->color,
                'key' => $labelDetail->key,
                'description' => $labelDetail->content,
            ] : null,
            'updated_on' => date('F j, Y', strtotime($this->updated_at)),
            'approved_user' => $this->approved_by ? new UserSummeryResource($this->approvedUser) : null,
            'written_user' => $this->written_by ? new UserSummeryResource($this->writtenUser) : null,

            'related' => ArticleSummeryResource::collection($getRelated->take(6)->all()),
            'read_this_later' => ArticleDetailResource::collection($getRelated->take(10)->all()),

            'widgets' => ArticleWidgetResource::collection($this->articleWidgets),
            'user_reviews' => $reviews,
        ];
    }
}
