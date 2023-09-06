<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    use HasFactory;
    protected $connection= 'mysql';

    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'status' => 'boolean'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    /**
     * This method is used to get all the Universities
     */
    public static function getAllDegrees()
    {
        return Degree::where('status', true)->orderBy('name', 'ASC')->get();
    }

    public function doctorEducation(){

        return $this->hasMany(DoctorEducation::class,'degree');

    }

}
