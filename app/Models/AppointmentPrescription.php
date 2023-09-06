<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentPrescription extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = ['id'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
