<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorBankDetail extends Model
{
    use HasFactory;
    protected $connection= 'mysql';

    protected $guarded = [
        'id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function DoctorDetail(){
        return $this->belongsTo(DoctorDetail::class, 'doctor_id', 'doctor_id');
    }
}
