<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorEarning extends Model
{
    use HasFactory;
    protected $table = 'doctor_earnings';
    protected $fillable = ['parent_id', 'doctor_id', 'total_payable', 'remaining_payable', 'income', 'deduction', 'status'];

    public function doctorEarning()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function doctorEarningChildrens()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function doctorEarningDetails()
    {
        return $this->hasMany(DoctorEarningDetail::class, 'doctor_earning_id');
    }

    public function doctorPayables()
    {
        return $this->hasMany(DoctorPayable::class, 'doctor_earning_id');
    }

    public function deductions()
    {
        return $this->hasMany(Deduction::class, 'doctor_earning_id');
    }

}
