<?php

namespace App\Http\Resources\Menu;

use App\Models\Article;
use App\Models\Menu;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Common\Constant;
use App\Http\Resources\Article\ArticleSummeryResource;

class FooterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'translation_of' => $this->translation_of ?? null,
            'lang_id' => $this->lang_id ?? null,
            'parent_id' => $this->parent_id ?? null,
            'name' => $this->name ?? null,
            'link' => $this->link ?? null,
            'type' => $this->type ?? null,
            'col_width' => $this->col_width ?? null,
            'status' => $this->status ?? null,
            'target' => $this->target ?? null,
            'e_id' => $this->e_id ?? null,
            'children' => $this->children ?? null,
        ];
    }
}
