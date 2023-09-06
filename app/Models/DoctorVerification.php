<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorVerification extends Model
{
    use HasFactory;

    protected $connection= 'mysql';
    protected $table = 'doctor_verification';

    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
