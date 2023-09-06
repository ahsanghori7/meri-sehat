<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorPayable extends Model
{
    use HasFactory;
    protected $table = 'doctor_payables';
    protected $fillable = ['doctor_earning_id', 'total_receivable', 'amount_paid', 'status', 'bank_transaction_id', 'transaction_date'];


    public function doctorEarning()
    {
        return $this->hasMany(DoctorEarning::class, 'id', 'doctor_earning_id');
    }

    public function appointmentPayables()
    {
        return $this->hasMany(AppointmentPayable::class, 'doctor_payable_id');
    }
}
