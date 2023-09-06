<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorEducation extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $table = 'doctor_educations';

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
        'institute_id',
        'degree_id'
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
    public function getInstituteIdAttribute()
    {
        $value = $this->attributes['institute'];
        return University::find($value)? (University::find($value)->id? University::find($value)->id : null) : null;
    }
    public function getDegreeIdAttribute()
    {
        $value = $this->attributes['degree'];
        return Degree::find($value)? (Degree::find($value)->id? Degree::find($value)->id : null) : null;
    }
    public function getInstituteAttribute($value)
    {
        return University::find($value)? (University::find($value)->name? University::find($value)->name : null) : null;
    }

}
