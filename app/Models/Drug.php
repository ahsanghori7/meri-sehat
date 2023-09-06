<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    use HasFactory;
    protected $connection= 'mysql';

    protected $guarded = ['id'];
    protected $appends = ['e_id', 'redirect_url'];
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

    public function translationOf(){
        return $this->belongsTo(self::class,'translation_of')->where('lang_id', Language::ENGLISH);
    }

    public function translations(){
        return $this->hasMany(self::class,'translation_of')->where('lang_id', '!=', Language::ENGLISH);
    }

    public function language(){
        return $this->belongsTo(Language::class, 'lang_id','id');
    }
    public function getRedirectUrlAttribute(){
        if($this->lang_id == 1){
            return "/drug/$this->slug";
        }else{
            $getLanguage = Language::find($this->lang_id);
            return "/$getLanguage->slug/drug/$this->slug";
        }
    }
    public function getSlugAttribute($value){
        return $this->page ? $this->page->slug : $value;
    }

    public static function getValidationRules($id = ""){
        return [
            'name' => 'required',
        ];
    }

    public function page()
    {
        return $this->morphOne(Page::class, 'reference');
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }
    /**
     * Relationships
     */
    public function drugWidgets(){
        return $this->morphMany(ReferenceWidget::class, 'reference')->orderBy('sequence', 'ASC');
    }
    public function drugWidgetDetails(){
        return $this->morphMany(ReferenceWidget::class, 'reference')->where('status', true)->orderBy('sequence', 'ASC');
    }
    /**
     * This method is used to get all the diseases in Alphabetical format
     */
    public static function getDrugsByAlphabeticalOrder($locale, $search, $all)
    {
        $getDrugs = Drug::where('lang_id', $locale)
        ->where('status', true)
        ->where('draft', false)
        ->where(function($query) use ($search, $all){
            if(!$all){
                $query->whereHas('page');
            }
            if($search){
                $query->where(function($subQuery) use ($search){
                    $subQuery->orWhere('name', 'like', "%$search%");
                    $subQuery->orWhere('slug', 'like', "%$search%");
                    $subQuery->orWhere('description', 'like', "%$search%");
                });
            }
        })
        ->orderBy('name', 'ASC')
        ->get();
        if(!count($getDrugs)){
            return null;
        }
        if(isset($all)){
            return $getDrugs;
        }
        $response = [];
        foreach($getDrugs as $getDrug){
            $response[mb_substr($getDrug['name'], 0, 1, "UTF-8")][] = $getDrug;
        }
        $response['headings'] = array_keys($response);
        return $response;
    }
}
