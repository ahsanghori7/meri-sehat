<?php

namespace App\Http\Resources\Menu;

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

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $children = array();
        if (count($this->children) > 0) {
            if (count($this->children[0]->children) > 0) {
                if ($this->children[0]->children[0]->name == '{featured_articles}') {
                    $featured_articles = Article::where('is_featured', 1)
                        ->where('draft', 0)
                        ->where('status', 1)
                        ->get();
                    $array = [
                        'id' => $this->children[0]->id,
                        'translation_of' => $this->children[0]->translation_of ?? null,
                        'lang_id' => $this->children[0]->lang_id ?? null,
                        'parent_id' => $this->children[0]->parent_id ?? null,
                        'name' => $this->children[0]->name ?? null,
                        'link' => $this->children[0]->link ?? null,
                        'type' => $this->children[0]->type ?? null,
                        'col_width' => $this->children[0]->col_width ?? null,
                        'status' => $this->children[0]->status ?? null,
                        'target' => $this->children[0]->target ?? null,
                        'e_id' => $this->children[0]->e_id ?? null,
                        'children' => ArticleSummeryResource::collection($featured_articles),
                    ];
                    array_push($children, $array);
                } elseif ($this->children[0]->children[0]->name == '{best_doctor}') {
                    $child = $this->children[0]->children[0];
                    $child->button_link = '/doctors/Karachi';
                    $child->button_text = 'Schedule Appointment';
                    $array = [
                        'id' => $this->children[0]->id,
                        'translation_of' => $this->children[0]->translation_of ?? null,
                        'lang_id' => $this->children[0]->lang_id ?? null,
                        'parent_id' => $this->children[0]->parent_id ?? null,
                        'name' => $this->children[0]->name ?? null,
                        'link' => $this->children[0]->link ?? null,
                        'type' => $this->children[0]->type ?? null,
                        'col_width' => $this->children[0]->col_width ?? null,
                        'status' => $this->children[0]->status ?? null,
                        'target' => $this->children[0]->target ?? null,
                        'e_id' => $this->children[0]->e_id ?? null,
                        'children' => $child,
                    ];
                    array_push($children, $array);
                } elseif ($this->children[0]->children[0]->name == '{featured_article2}') {
                    $featured_articles = Article::where('is_featured2', 1)
                        ->where('status', 1)
                        ->where('draft', 0)
                        ->get();
                    $array = [
                        'id' => $this->children[0]->id,
                        'translation_of' => $this->children[0]->translation_of ?? null,
                        'lang_id' => $this->children[0]->lang_id ?? null,
                        'parent_id' => $this->children[0]->parent_id ?? null,
                        'name' => $this->children[0]->name ?? null,
                        'link' => $this->children[0]->link ?? null,
                        'type' => $this->children[0]->type ?? null,
                        'col_width' => $this->children[0]->col_width ?? null,
                        'status' => $this->children[0]->status ?? null,
                        'target' => $this->children[0]->target ?? null,
                        'e_id' => $this->children[0]->e_id ?? null,
                        'children' => ArticleSummeryResource::collection($featured_articles),
                    ];
                    array_push($children, $array);
                }else {
                    if (isset($this->children[0])) {
                        array_push($children, $this->children[0]);
                    }
                }
                if (isset($this->children[1])) {
                    array_push($children, $this->children[1]);
                }
                if (isset($this->children[2])) {
                    if ($this->children[2]->name == '{discover_wellness}') {
                        $wellness_pages = Page::whereIn('slug', ['wellness/mens-wellness', 'wellness/womens-wellness'])->where('status', 1)->get();
                        if (isset($wellness_pages[0]->slug)) {
                            $wellness_pages[0]->slug = '/'.$wellness_pages[0]->slug;
                        }
                        if (isset($wellness_pages[1]->slug)) {
                            $wellness_pages[1]->slug = '/'.$wellness_pages[1]->slug;
                        }
//                        $array = [
//                            'id' => $this->children[2]->id,
//                            'translation_of' => $this->children[2]->translation_of ?? null,
//                            'lang_id' => $this->children[2]->lang_id ?? null,
//                            'parent_id' => $this->children[2]->parent_id ?? null,
//                            'name' => $this->children[2]->name ?? null,
//                            'link' => $this->children[2]->link ?? null,
//                            'type' => $this->children[2]->type ?? null,
//                            'col_width' => $this->children[2]->col_width ?? null,
//                            'status' => $this->children[2]->status ?? null,
//                            'target' => $this->children[2]->target ?? null,
//                            'e_id' => $this->children[2]->e_id ?? null,
//                            'children' => $wellness_pages,
//                        ];
                        array_push($children, $wellness_pages);
                    } else {
                        array_push($children, $this->children[2]);
                    }

                }
                if (isset($this->children[3])) {
                    if ($this->children[3]->children[0]->name == '{recent_articles}') {
                        $recent_articles = Article::where('lang_id', $this->lang_id)
                            ->where('draft', 0)
                            ->where('status', 1)
                            ->orderBy('created_at', 'DESC')
                            ->take('3')
                            ->get();
                        if ($recent_articles) {
                            $recent_articles = collect($recent_articles)->map(function ($arr) {
                                $arr['type'] = 'recent-articles';
                                return $arr;
                            });
                        }

                        $array = [
                            'id' => $this->children[3]->id,
                            'translation_of' => $this->children[3]->translation_of ?? null,
                            'lang_id' => $this->children[3]->lang_id ?? null,
                            'parent_id' => $this->children[3]->parent_id ?? null,
                            'name' => $this->children[3]->name ?? null,
                            'link' => $this->children[3]->link ?? null,
                            'type' => $this->children[3]->type ?? null,
                            'col_width' => $this->children[3]->col_width ?? null,
                            'status' => $this->children[3]->status ?? null,
                            'target' => $this->children[3]->target ?? null,
                            'e_id' => $this->children[3]->e_id ?? null,
                            'children' => ArticleSummeryResource::collection($recent_articles),
                        ];
                        array_push($children, $array);
                    } else {
                        array_push($children, $this->children[3]);
                    }
                }
                if (isset($this->children[4])) {
                    if ($this->children[4]->children[0]->name == '{teleheath_specialist}') {
                        $child = $this->children[4]->children[0];
                        $child->button_link = '/doctor-now';
                        $child->button_text = 'Consult Now';
                        $array = [
                            'id' => $this->children[4]->id,
                            'translation_of' => $this->children[4]->translation_of ?? null,
                            'lang_id' => $this->children[4]->lang_id ?? null,
                            'parent_id' => $this->children[4]->parent_id ?? null,
                            'name' => $this->children[4]->name ?? null,
                            'link' => $this->children[4]->link ?? null,
                            'type' => $this->children[4]->type ?? null,
                            'col_width' => $this->children[4]->col_width ?? null,
                            'status' => $this->children[4]->status ?? null,
                            'target' => $this->children[4]->target ?? null,
                            'e_id' => $this->children[4]->e_id ?? null,
                            'children' => $child,
                        ];
                        array_push($children, $array);
                    } else {
                        array_push($children, $this->children[4]);
                    }
                }

            }
        } else {
//            array_push($children, null);
        }

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
            'image' => $this->image ? env('ASSETS_STORAGE'). $this->image : null,
            'children' => $children ?? null,
        ];
    }
}
