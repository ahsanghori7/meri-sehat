<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorDetail extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'is_physical_consultancy' => 'boolean',
        'is_video_consultancy' => 'boolean',
        'is_voice_consultancy' => 'boolean',
        'consultation_fee' => 'double',
        'consultation_duration' => 'string',
        'badge' => 'string',
        'is_available' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public static function getValidationRules($id = ""){
        return [
//            'prefix' => 'required',
            // 'experience_year' => 'required',
//            'pmc_no' => 'required',
        ];
    }

    // public function doctorServices(){
    //     return $this->hasMany(DoctorService::class, 'doctor_id');
    // }

    // public function doctorSpecialities(){
    //     return $this->hasMany(DoctorSpeciality::class, 'doctor_id');
    // }

    // public function doctorExperiences(){
    //     return $this->hasMany(DoctorExperience::class, 'doctor_id');
    // }

    // public function doctorEducation(){
    //     return $this->hasMany(DoctorEducation::class,'doctor_id');
    // }

    // public function appointments(){
    //     return $this->hasMany(Appointment::class,'doctor_id');
    // }

    public function user(){
        return $this->belongsTo(User::class,'doctor_id');
    }
}
