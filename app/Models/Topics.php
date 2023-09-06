<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topics extends Model
{
    protected $connection= 'mysql';
    protected $table = 'topics';
    protected $guarded=['id'];
    protected $appends = ['e_id','outer_image_url', 'banner_image_url'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public static function getValidationRules($id = ""){
        return [
            'title' => 'required',
            'color_code' => 'required',
            'outer_image' => !$id? 'required':'',
            'outer_text' => 'required',
            'banner_text' => 'required',
            // 'banner_image' => !$id? 'required':'',
        ];
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }
    public function getOuterImageUrlAttribute(){
        return ($this->outer_image) ? env('ASSETS_STORAGE').$this->outer_image : env('ASSETS_STORAGE')."assets/img/default.png";
    }
    public function getBannerImageUrlAttribute(){
        return ($this->banner_image) ? env('ASSETS_STORAGE').$this->banner_image : env('ASSETS_STORAGE')."assets/img/default.png";
    }
    /**
     * Relationships
     */
    public function createdUser(){
        return $this->belongsTo(User::class, 'created_by');
    }
    public function parent(){
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(){
        return $this->hasMany(self::class, 'parent_id')->with('children');
    }
    public function translationOf(){
        return $this->belongsTo(self::class,'translation_of')->where('lang_id', Language::ENGLISH);
    }
    public function translations(){
        return $this->hasMany(self::class,'translation_of')->where('lang_id', '!=', Language::ENGLISH);
    }
    public function language(){
        return $this->belongsTo(Language::class, 'lang_id','id');
    }
    public function topicWidgetDetails(){
        return $this->morphMany(ReferenceWidget::class, 'reference')->where('status', true)->orderBy('sequence', 'ASC');
    }
    public function page(){
        return $this->morphMany(Page::class, 'reference');
    }

    /**
     * This method is used to get all the Topics
     */
    public static function getTopics($locale, $search = null)
    {
        return Topics::where(['status' => true, 'draft' => false, 'lang_id' => $locale])
        ->where(function($query) use ($search){
            if($search){
                $query->orWhere('meta_keyword', 'like', "%$search%");
                $query->orWhere('meta_description', 'like', "%$search%");
                $query->orWhere('title', 'like', "%$search%");
                $query->orWhere('slug', 'like', "%$search%");
                $query->orWhere('outer_text', 'like', "%$search%");
                $query->orWhere('banner_text', 'like', "%$search%");
            }
        })
        ->orderBy('sequence', 'ASC')
        ->get();
    }

    public function getSlugAttribute($value)
    {
        return $this->parent_id == 0 ? $value : Topics::find($this->parent_id)->slug . '/' . $value;
    }

}
