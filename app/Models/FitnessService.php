<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessService extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $table = 'fitness_experts_services';
    protected $guarded = [
        'id',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function fitness(){
        return $this->belongsTo(User::class, 'fitness_experts_id')
        ->where(['role_id' => 7, 'status' => true, 'is_blocked' => false])
        ->whereHas('fitnessDetail')
        ->whereHas('city')
        ->whereHas('fitnessSpecialityDetails');
    }

    public function service(){
        return $this->belongsTo(Service::class, 'service_id');
    }
}
