<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorExperience extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $connection= 'mysql';
    protected $table = 'doctor_experiences';

    protected $appends = [
        'position_id', 'institute_id'
    ];

    // protected $visible = ['id', 'institute', 'position'];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute')->where('status', 1);
    }

    public function position()
    {
        return $this->belongsTo(Designation::class, 'position');
    }

    // public function getInstituteAttribute($value){
    //     return Institute::find($value)? (Institute::find($value)->name? Institute::find($value)->name : null) : null;
    // }
    public function getInstituteIdAttribute(){
        $value = $this->attributes['institute'];
        return Institute::find($value)? (Institute::find($value)->id? Institute::find($value)->id : null) : null;
    }
    // public function getPositionAttribute($value){
    //     return Designation::find($value)? (Designation::find($value)->name? Designation::find($value)->name : null) : null;
    // }
    public function getPositionIdAttribute(){
        $value = $this->attributes['position'];
        return Designation::find($value)? (Designation::find($value)->id? Designation::find($value)->id : null) : null;
    }
}
