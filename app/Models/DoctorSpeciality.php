<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSpeciality extends Model
{
    use HasFactory;
    protected $connection= 'mysql';

    protected $guarded = [
        'id'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function speciality(){
        return $this->belongsTo(Speciality::class, 'speciality_id');
    }

    public function doctor(){
        return $this->belongsTo(User::class, 'doctor_id')
        ->where(['role_id' => 3, 'status' => true, 'is_blocked' => false])
        ->whereHas('doctorDetail')
        ->whereHas('city')
        ->whereHas('doctorServiceDetails');
    }
}
