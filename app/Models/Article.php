<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Article extends Model
{
    use SoftDeletes;
    protected $connection= 'mysql3';
    protected $table = 'articles';
    protected $guarded = ['id'];
    protected $appends = ['e_id','image_url'];
    protected $casts = [
        'status' => 'boolean',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public static function getValidationRules($id = ""){
        return [
            'name' => 'required',
            'type' => 'required',
            'descripton' => 'required',
            // 'image' => !$id? 'required':'',
            'category' => 'required',
        ];
    }
    public function getImageUrlAttribute(){
        return ($this->image) ? env('ASSETS_STORAGE').$this->image : env('ASSETS_STORAGE')."assets/img/default.png";
    }

    public function parent(){
        return $this->belongsTo(Topics::class, 'parent_id');
    }
    public function articleTags(){
        return $this->hasMany(ArticleTags::class);
    }
    public function articleWidgets(){
        return $this->morphMany(ReferenceWidget::class, 'reference')->where('status', true)->orderBy('sequence', 'ASC');
    }
    public function articleVideoWidgets(){
        return $this->morphMany(ReferenceWidget::class, 'reference')->where('status', true)->whereIn('widget_id', ['17','29'])->orderBy('sequence', 'ASC');
    }
    public function adminArticleWidgets(){
        return $this->morphMany(ReferenceWidget::class, 'reference')->orderBy('sequence', 'ASC');
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
    public function getEIdAttribute(){
        return encrypt($this->id);
    }
    public function approvedUser(){
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function writtenUser(){
        return $this->belongsTo(User::class, 'written_by');
    }
    public function articleLabel(){
        return $this->hasOne(ArticleLabel::class, 'article_id');
    }

}
