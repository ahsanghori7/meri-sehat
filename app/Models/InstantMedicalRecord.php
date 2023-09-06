<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstantMedicalRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection= 'mysql';

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

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function instantMedicalRecordFiles()
    {  
        return $this->hasMany(InstantMedicalRecordFile::class);
    }
    public function familyMember()
    {
        return $this->belongsTo(UserFamilyMember::class, 'family_member_id')->withTrashed();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function sharedMedicalReport()
    {
        return $this->hasMany(SharedMedicalReport::class);
    }

    public function prescriptionElementType()
    {
        return $this->hasOne(PrescriptionElementType::class, 'prescription_element_type_id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
