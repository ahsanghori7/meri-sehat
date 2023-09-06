<?php

namespace App\Models;

use App\Http\Common\Constant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $casts = [
        'status' => 'boolean'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $appends = ['e_id'];

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

    public function doctor(){
        return $this->hasMany(User::class, 'city_id')
        ->where(['role_id' => 3, 'status' => true, 'is_blocked' => false])
        ->whereHas('doctorDetail')
        ->whereHas('doctorSpecialityDetails')
        ->whereHas('doctorServiceDetails');
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }
    /**
     * This method is used to get all the Cities
     */
    public static function getCities($locale, $search = null)
    {
        return City::where(['status' => true, 'lang_id' => $locale])
        ->where(function($query) use ($search){
            if($search){
                $query->where('name', 'like', "%$search%");
                $query->orWhere('slug', 'like', "%$search%");
            }
        })
        ->orderBy('name', 'ASC')
        ->get();
    }

    /**
     * This method is used to get the cities listing by alphabetical order with doctor's count
     */
    public static function getCitiesByAlphabeticalOrder($locale, $search = null)
    {
        $getCities = City::where('lang_id', $locale)
        ->where('status', true)
        ->where(function($query) use ($search){
            if($search){
                $query->orWhere('name', 'like', "%$search%");
                $query->orWhere('slug', 'like', "%$search%");
            }
        })
        ->where(function($query) use($locale){
            if($locale == Language::ENGLISH){
                $query->whereHas('doctor');
            }else{
                $query->whereHas('translationOf.doctor');
            }
        })
        ->withCount('doctor as doctor_count')
        ->orderBy('name', 'ASC')
        ->get();
        if(!count($getCities)){
            return null;
        }
        if(isset($all)){
            return $getCities;
        }
        $response = [];
        foreach($getCities as $getCity){
            if(!$getCity->doctor_count){
                $getCity['doctor_count'] = User::where('city_id', City::find($getCity->translation_of)->id)->whereHas('hasDoctor')->count();
            }
            $response[mb_substr($getCity['name'], 0, 1)][] = $getCity;
        }
        $response['headings'] = array_keys($response);
        return $response;
    }
}
