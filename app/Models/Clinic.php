<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
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

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function doctorClinics()
    {
        return $this->hasMany(DoctorClinic::class);
    }

    /**
     * This method is used to get all the Clinics
     */
    public static function getAllClinics()
    {
        return Clinic::where('status', true)->orderBy('name', 'ASC')->take(10)->get();
    }

    public function doctorTimings()
    {
        return $this->hasMany(ClinicTiming::class);
    }
}
