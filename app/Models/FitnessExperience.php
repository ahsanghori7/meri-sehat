<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessExperience extends Model
{
    protected $connection= 'mysql';
    protected $guarded = ['id'];
    use HasFactory;
    protected $table = 'fitness_experts_experiences';

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }
}
