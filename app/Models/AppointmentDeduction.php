<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentDeduction extends Model
{
    use HasFactory;
    protected $table = 'appointment_deductionss';
    protected $fillable = ['deduction_id', 'appointment_id'];

    public function deduction()
    {
        return $this->belongsTo(Deduction::class, 'deduction_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
