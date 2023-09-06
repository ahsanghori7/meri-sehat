<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;
    protected $table = 'deductions';
    protected $fillable = ['doctor_earning_id', 'total_receivable', 'amount_paid', 'progress', 'status'];


    public function doctorEarning()
    {
        return $this->belongsTo(DoctorEarning::class, 'doctor_earning_id');
    }

    public function appointmentDeductions()
    {
        return $this->hasMany(AppointmentDeduction::class, 'deduction_id');
    }
}
