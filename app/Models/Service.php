<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Language;

class Service extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $table = 'services';
    protected $appends = ['e_id', 'image_url'];

    protected $hidden = [
        'action_by',
        'created_at',
        'updated_at',
        'laravel_through_key',
    ];
    protected $casts = [
        'status' => 'boolean'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public static function getValidationRules($id = ""){
        return [
            'name' => 'required',
            'speciality_id' => 'required',
        ];
    }

    public function getImageUrlAttribute(){
        return ($this->image) ? env('ASSETS_STORAGE').$this->image : env('ASSETS_STORAGE')."assets/img/default.png";
    }

    public function speciality(){
        return $this->belongsTo(Speciality::class);
    }

    public function doctorServices(){
        return $this->hasMany(DoctorService::class, 'service_id')->whereHas('doctor');
    }

    public function translationOf(){
        return $this->belongsTo(self::class,'translation_of')->where('lang_id', Language::ENGLISH);
    }

    public function translations(){
        return $this->hasMany(self::class,'translation_of')->where('lang_id', '!=', Language::ENGLISH);
    }

    public function language(){
        return $this->belongsTo(Language::class, 'lang_id');
    }

    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    public static function getService($specitialityId)
    {
        return Service::where('status',1)->where(function($query) use($specitialityId){
            if($specitialityId){
                $query->where('speciality_id', $specitialityId);
            }
        })->get();
    }

    /**
     * This method is used to get the services listing by alphabetical order with doctor's count
     */
    public static function getServicesByAlphabeticalOrder($locale, $search = null)
    {
        $getServices = Service::where('lang_id', $locale)
        ->where('status', true)
        ->where(function($query) use ($search){
            if($search){
                $query->orWhere('name', 'like', "%$search%");
            }
        })
        ->where(function($query) use($locale){
            if($locale == Language::ENGLISH){
                $query->whereHas('doctorServices');
            }else{
                $query->whereHas('translationOf.doctorServices');
            }
        })
        ->withCount('doctorServices as doctor_count')
        ->orderBy('name', 'ASC')
        ->get();
        if(!count($getServices)){
            return null;
        }
        if(isset($all)){
            return $getServices;
        }
        $response = [];
        foreach($getServices as $getService){
            if(!$getService->doctor_count){
                $getService['doctor_count'] = DoctorService::where('service_id', Service::find($getService->translation_of)->id)->whereHas('doctor')->count();
            }
            $getService['slug'] = \Str::slug($getService['name']);
            $response[mb_substr($getService['name'], 0, 1)][] = $getService;
        }
        $response['headings'] = array_keys($response);
        return $response;
    }
}
