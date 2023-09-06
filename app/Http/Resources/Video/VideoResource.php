<?php

namespace App\Http\Resources\Video;

use App\Http\Resources\Disease\DiseaseResource;
use App\Http\Resources\Page\PageResource;
use App\Models\Article;
use App\Models\Disease;
use App\Models\Menu;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Common\Constant;
use App\Http\Resources\Article\ArticleSummeryResource;
use Illuminate\Support\Carbon;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $clicks_count = 0;
        $article = null;
        $disease = null;
        if ($this->referenceWidget->reference_type == 'article') {
            $clicks_count = $this->referenceWidget->article->visit_counts;
            $article = $this->referenceWidget->article ? new ArticleSummeryResource($this->referenceWidget->article) : null;
        } elseif ($this->referenceWidget->reference_type == 'page'){
            $clicks_count = $this->referenceWidget->page->visit_counts;
            $disease = $this->referenceWidget->page->disease ?? null;
        }

        $video = array(
            'id' => $this->id,
            'language_id' => $this->language_id,
            'source' => $this->source,
            'is_default' => (bool)$this->is_default,
            'status' => (bool)$this->status,
            'heading' => $this->heading,
            'alt' => $this->alt,
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
            'file_url' => $this->file_url,
            'language' => $this->language,
            'clicks_count' => $clicks_count,
            'articles' => $article,
            'disease' => $disease,
        );
        return $video;
    }
}
