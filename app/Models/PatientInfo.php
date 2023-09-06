<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientInfo extends Model
{
    protected $connection= 'mysql';
    protected $table = 'patient_info';
    protected $guarded=['id'];
    protected $fillable = ['user_id', 'appointment_id', 'name', 'gender', 'date_of_birth', 'height', 'weight'];


    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }
}
