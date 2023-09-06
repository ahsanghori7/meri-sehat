<?php

namespace App\Models;

use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferenceWidget extends Model
{
    use SoftDeletes;
    protected $connection= 'mysql';
    protected $table = 'reference_widgets';
    protected $guarded=['id'];
    protected $appends = ['e_id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'status' => 'boolean',
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

    public function widget(){
        return $this->belongsTo(Widget::class);
    }
    public function widgetBanner(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasOne(WidgetBanner::class, 'reference_id')->where('is_mobile_show', 1);
        } else {
            return $this->hasOne(WidgetBanner::class, 'reference_id')->where('is_web_show', 1);
        }
    }
    public function widgetArticles(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasMany(WidgetArticle::class, 'reference_id')->orderBy('sequence', 'ASC')->where('is_mobile_show', 1);
        } else {
            return $this->hasMany(WidgetArticle::class, 'reference_id')->orderBy('sequence', 'ASC')->where('is_web_show', 1);
        }
    }
    public function widgetCards(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasMany(WidgetCard::class, 'reference_id')->where('is_mobile_show', 1);
        } else {
            return $this->hasMany(WidgetCard::class, 'reference_id')->where('is_web_show', 1);
        }
    }
    public function widgetDiseases(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasMany(WidgetDisease::class, 'reference_id')->where('status', 1)->where('is_mobile_show', 1)->orderBy('sequence', 'ASC')->with('disease');
        } else {
            return $this->hasMany(WidgetDisease::class, 'reference_id')->where('status', 1)->where('is_web_show', 1)->orderBy('sequence', 'ASC')->with('disease');
        }
    }
    public function widgetDoctor(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasOne(WidgetDoctor::class, 'reference_id')->where('is_mobile_show', 1);
        } else {
            return $this->hasOne(WidgetDoctor::class, 'reference_id')->where('is_web_show', 1);
        }
    }
    public function widgetReferences(){
        return $this->hasMany(WidgetReference::class, 'reference_id')->orderBy('sequence', 'ASC');
    }
    public function widgetSearches(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasOne(WidgetSearch::class, 'reference_id')->where('is_mobile_show', 1);
        } else {
            return $this->hasOne(WidgetSearch::class, 'reference_id')->where('is_web_show', 1);
        }
    }
    public function widgetSpecialities(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasMany(WidgetSpeciality::class, 'reference_id')->where('is_mobile_show', 1);
        } else {
            return $this->hasMany(WidgetSpeciality::class, 'reference_id')->where('is_web_show', 1);
        }
    }
    public function widgetHeadingAndDescription(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasOne(HeadingAndDescription::class, 'reference_id')->where('is_mobile_show', 1);
        } else {
            return $this->hasOne(HeadingAndDescription::class, 'reference_id')->where('is_web_show', 1);
        }
    }
    public function widgetCallToAction(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasOne(WidgetCallToAction::class, 'reference_id')->where('is_mobile_show', 1);
        } else {
            return $this->hasOne(WidgetCallToAction::class, 'reference_id')->where('is_web_show', 1);
        }
    }
    public function widgetSearch(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasOne(WidgetSearch::class, 'reference_id')->where('is_mobile_show', 1);
        } else {
            return $this->hasOne(WidgetSearch::class, 'reference_id')->where('is_web_show', 1);
        }
    }
    public function widgetTopicPills(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasMany(WidgetTopicPill::class, 'reference_id')->orderBy('sequence', 'ASC')->where('is_mobile_show', 1);
        } else {
            return $this->hasMany(WidgetTopicPill::class, 'reference_id')->orderBy('sequence', 'ASC')->where('is_web_show', 1);
        }
    }
    public function widgetVitalHealthScans(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasMany(WidgetVitalHealthScan::class, 'reference_id')->orderBy('sequence', 'ASC')->where('is_mobile_show', 1);
        } else {
            return $this->hasMany(WidgetVitalHealthScan::class, 'reference_id')->orderBy('sequence', 'ASC')->where('is_web_show', 1);
        }
    }
    public function widgetCallByReferenceCard(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasMany(WidgetCallByReferenceCard::class, 'reference_id')->orderBy('id', 'ASC')->where('is_mobile_show', 1);
        } else {
            return $this->hasMany(WidgetCallByReferenceCard::class, 'reference_id')->orderBy('id', 'ASC')->where('is_web_show', 1);
        }
    }
    public function widgetFaq(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasOne(WidgetFaq::class, 'reference_id')->where('is_mobile_show', 1);
        } else {
            return $this->hasOne(WidgetFaq::class, 'reference_id')->where('is_web_show', 1);
        }
    }
    public function widgetMedia(){
        if ($this->checkPlatform() == 'app') {

            return $this->hasMany(WidgetMedia::class, 'reference_id')->where('is_mobile_show', 1);
        } else {
            return $this->hasMany(WidgetMedia::class, 'reference_id')->where('is_web_show', 1);
        }
    }
    public function widgetMediaVideo(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasMany(WidgetMedia::class, 'reference_id')->where('type','video')->where('is_mobile_show', 1);
        } else {
            return $this->hasMany(WidgetMedia::class, 'reference_id')->where('type','video')->where('is_web_show', 1);
        }
    }
    public function widgetMostSearchedSpecialities(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasMany(WidgetMostSearchSpeciality::class, 'reference_id')->with('speciality')->where('is_mobile_show', 1);
        } else {
            return $this->hasMany(WidgetMostSearchSpeciality::class, 'reference_id')->with('speciality')->where('is_web_show', 1);
        }
    }
    public function widgetInFeedArticle(){
        if ($this->checkPlatform() == 'app') {
            return $this->hasOne(WidgetInFeedArticle::class, 'reference_id')->where('is_mobile_show', 1);
        } else {
            return $this->hasOne(WidgetInFeedArticle::class, 'reference_id')->where('is_web_show', 1);
        }
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }
    public function page()
    {
        if ($this->checkPlatform() == 'app') {
            return $this->belongsTo(Page::class, 'reference_id')->whereIn('reference_type', ['page', 'disease', 'topic', 'drug'])->where('is_mobile_show', 1);
        } else {
            return $this->belongsTo(Page::class, 'reference_id')->whereIn('reference_type', ['page', 'disease', 'topic', 'drug'])->where('is_web_show', 1);
        }
    }
    public function article()
    {
        if ($this->checkPlatform() == 'app') {
            return $this->belongsTo(Article::class, 'reference_id')->where('is_mobile_show', 1);
        } else {
            return $this->belongsTo(Article::class, 'reference_id')->where('is_web_show', 1);
        }
    }
}
