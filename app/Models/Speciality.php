<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    protected $connection= 'mysql';
    protected $guarded=['id'];
    protected $appends = ['e_id', 'image_url'];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'status' => 'boolean'
    ];

    public static function getValidationRules($id = ""){
        return [
            'name' => 'required',
//            'image' => !$id? 'required':'',
        ];
    }

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function getImageUrlAttribute(){
        return ($this->image) ? env('ASSETS_STORAGE').$this->image : env('ASSETS_STORAGE')."assets/img/default.png";
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    public function questionaire_forms(){
        return $this->hasMany(QuestionaireForm::class,'speciality_id');
    }

    public function questionaire_form(){
        return $this->hasOne(QuestionaireForm::class,'speciality_id');
    }

    public function doctorSpecialities()
    {
        return $this->hasMany(DoctorSpeciality::class, 'speciality_id')->whereHas('doctor');
    }

    public function doctorSpeciality()
    {
        return $this->hasMany(DoctorSpeciality::class, 'speciality_id');
    }

    public static function getSpecialities()
    {
        return Speciality::where('status',1)->orderBy('name', 'asc')->get();
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

    /**
     * This method is used to get the speciality listing by alphabetical order with doctor's count
     */
    public static function getSpecialityByAlphabeticalOrder($locale, $search = null)
    {
        $getSpecialities = Speciality::where('lang_id', $locale)
        ->where('status', true)
        ->where(function($query) use ($search){
            if($search){
                $query->orWhere('name', 'like', "%$search%");
                $query->orWhere('slug', 'like', "%$search%");
            }
        })
        ->where(function($query) use($locale){
            if($locale == Language::ENGLISH){
                $query->whereHas('doctorSpecialities');
            }else{
                $query->whereHas('translationOf.doctorSpecialities');
            }
        })
        ->withCount('doctorSpecialities as doctor_count')
        ->orderBy('name', 'ASC')
        ->get();
        if(!count($getSpecialities)){
            return null;
        }
        if(isset($all)){
            return $getSpecialities;
        }
        $response = [];
        foreach($getSpecialities as $getSpeciality){
            if(!$getSpeciality->doctor_count){
                $getSpeciality['doctor_count'] = DoctorSpeciality::where('speciality_id', Speciality::find($getSpeciality->translation_of)->id)->whereHas('doctor')->count();
            }
            $getSpeciality['image'] = $getSpeciality->image ? env('ASSETS_STORAGE'). $getSpeciality->image : null;
            $response[mb_substr($getSpeciality['name'], 0, 1)][] = $getSpeciality;
        }
        $response['headings'] = array_keys($response);
        return $response;
    }

}
