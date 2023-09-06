<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentPayable extends Model
{
    use HasFactory;
    protected $table = 'appointment_payables';
    protected $fillable = ['doctor_payable_id', 'appointment_id'];


    public function doctorPayables()
    {
        return $this->belongsTo(DoctorPayable::class, 'doctor_payable_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
