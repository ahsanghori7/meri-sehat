<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessDetail extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $table = 'fitness_experts_details';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public static function getValidationRules($id = ""){
        return [
            'phone' => 'required',
            'name' => 'required',
            'email' => 'required',
            'city' => 'required',
            'gender' => 'required',
            'experience_year' => 'required',
        ];
    }

     public function fitnessServices(){
         return $this->hasMany(FitnessService::class, 'fitness_experts_id');
     }

     public function fitnessSpecialities(){
         return $this->hasMany(FitnessSpeciality::class, 'fitness_experts_id');
     }

     public function fitnessExperiences(){
         return $this->hasMany(FitnessExperience::class, 'fitness_experts_id');
     }

     public function fitnessEducation(){
         return $this->hasMany(FitnessEducation::class,'fitness_experts_id');
     }

    public function fitnessArticles(){
        return $this->hasMany(Article::class, 'fitness_experts_id', 'approved_by');
    }

    public function user(){
        return $this->belongsTo(User::class,'fitness_experts_id');
    }
}
