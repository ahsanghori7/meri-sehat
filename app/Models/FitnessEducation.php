<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessEducation extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $table = 'fitness_experts_educations';

    protected $guarded = ['id'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'degree_full_name',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function degree()
    {
        return $this->belongsTo(Degree::class, 'degree');
    }
    public function institute()
    {
        return $this->belongsTo(University::class, 'institute');
    }

    public function getDegreeAttribute($value)
    {
        return Degree::find($value)? (Degree::find($value)->name? Degree::find($value)->name : null) : null;
    }
    public function getInstituteAttribute($value)
    {
        return University::find($value)? (University::find($value)->name? University::find($value)->name : null) : null;
    }
    public function getDegreeFullNameAttribute() {
        return Degree::where('name', $this->degree)->where('status', 1) ? (Degree::where('name', $this->degree)->first()->full_name ?? null) : null;
    }

}
