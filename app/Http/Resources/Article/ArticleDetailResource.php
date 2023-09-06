<?php

namespace App\Http\Resources\Article;

use App\Http\Resources\User\{UserSummeryResource};
use App\Models\{InfoModal, Language};
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleDetailResource extends JsonResource
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
            'redirect_url' => $redirectUrl,
            'descripton' => $this->descripton,
            'is_description_show' => $this->is_description_show,
            'hide_image_in_detail' => $this->hide_image_in_detail,
            'keywords' => $this->keywords,
            'image' => $this->image ? env('ASSETS_STORAGE').$this->image : "",
            'status' => $this->status,
            'updated_on' => date('F j, Y', strtotime($this->updated_at)),
            'label' => $this->articleLabel && isset($labelDetail) ? [
                'value' => $this->articleLabel->articleFact->name,
                'color' => $this->articleLabel->articleFact->color,
                'key' => $labelDetail->key,
                'description' => $labelDetail->content,
            ] : null,

            'approved_user' => $this->approved_by ? new UserSummeryResource($this->approvedUser) : null,
            'written_user' => $this->written_by ? new UserSummeryResource($this->writtenUser) : null,
            'widgets' => ArticleWidgetResource::collection($this->articleWidgets),
        ];
    }
}
