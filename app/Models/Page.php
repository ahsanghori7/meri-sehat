<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Page extends Model
{
    use SoftDeletes;
    protected $connection= 'mysql3';
    protected $table = 'pages';
    protected $guarded = ['id'];
    protected $appends = ['e_id', 'image_url'];
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

    public function checkPlatform() {
        $platformType = 'web';
        if (app('request')->header('platform'))
        {
            $platformType = app('request')->header('platform');
        }
        return $platformType;
    }

    public static function getValidationRules($id = ""){
        return [
            'name' => 'required',
            'reference_type' => 'required',
            'descripton' => 'required',
            'image' => !$id? 'required':'',
            'reference_id' => 'required',
        ];
    }
    public function getImageUrlAttribute(){
        return ($this->image) ? env('ASSETS_STORAGE').$this->image : env('ASSETS_STORAGE')."assets/img/default.png";
    }

    public function parent(){
        return $this->belongsTo(Topics::class, 'parent_id');
    }

    public function disease(){
        return $this->belongsTo(Topics::class, 'id');
    }

    public function pageWidgets(){
        return $this->morphMany(ReferenceWidget::class,'reference')->orderBy('sequence', 'ASC');
    }
    public function pageWidgetDetails(){
        if ($this->checkPlatform() == 'app') {
            return $this->morphMany(ReferenceWidget::class, 'reference')->where('status', true)->where('is_mobile_show', 1)->orderBy('sequence', 'ASC');
        } else {
            return $this->morphMany(ReferenceWidget::class, 'reference')->where('status', true)->where('is_web_show', 1)->orderBy('sequence', 'ASC');
        }
    }
    public function language(){
        return $this->belongsTo(Language::class, 'lang_id','id');
    }
    public function getEIdAttribute(){
        return encrypt($this->id);
    }
    public function reviewedUser(){
        return $this->belongsTo(User::class, 'reviewed_by');
    }
    public function writtenUser(){
        return $this->belongsTo(User::class, 'written_by');
    }
    public function pageTags(){
        return $this->hasMany(PageTag::class, 'page_id');
    }

}
