<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Hoyvoy\CrossDatabase\Eloquent\Model;


class FitnessSpeciality extends Model
{
    protected $connection= 'mysql';
    protected $table = 'fitness_experts_specialities';
    protected $guarded = [
        'id'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    use HasFactory;

    public function speciality(){
        return $this->setConnection('mysql')->belongsTo(Speciality::class, 'speciality_id');
    }

    public function fitness(){
        return $this->setConnection('mysql')->belongsTo(User::class, 'fitness_experts_id')
        ->where(['role_id' => 7, 'status' => true, 'is_blocked' => false])
        ->whereHas('fitnessDetail')
        ->whereHas('city')
        ->whereHas('fitnessServiceDetails');
    }
}
