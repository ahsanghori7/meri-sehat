<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorCertification extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id', 'certification_id',
    ];

    protected $appends = [
        'certification'
    ];

    public function doctors(){
        return $this->hasMany(User::class, 'id', 'doctor_id');
    }

    public function certification()
    {
        return $this->hasMany(Certification::class, 'id', 'certification_id');
    }

    public function getCertificationAttribute(){
        $value = $this->attributes['certification_id'];
        return Certification::find($value)? (Certification::find($value)->name? Certification::find($value)->name : null) : null;
    }
}
