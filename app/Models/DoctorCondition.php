<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorCondition extends Model
{
    use HasFactory;

    protected $table = 'doctor_condition';

    protected $fillable = [
        'doctor_id' , 'disease_id', 'speciality_id'
    ];
}
