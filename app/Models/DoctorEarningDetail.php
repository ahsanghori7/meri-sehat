<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorEarningDetail extends Model
{
    use HasFactory;
    protected $table = 'doctor_earning_detail';
    protected $fillable = ['doctor_earning_id', 'appointment_id', 'status'];


    public function doctorEarning()
    {
        return $this->belongsTo(DoctorEarning::class, 'doctor_earning_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class,'appointment_id', 'id');
    }

    //get deduct appointment from appointment deduction (doctor earning detail)

    public function appointment_deduction()
    {
        return $this->hasMany(AppointmentDeduction::class, 'appointment_id', 'appointment_id')->where('status', 'paid');
    }

}
