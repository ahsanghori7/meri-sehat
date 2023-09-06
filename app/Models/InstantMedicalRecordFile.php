<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstantMedicalRecordFile extends Model
{
    use HasFactory;
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
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function prescriptionElementType()
    {
        return $this->belongsTo(PrescriptionElementType::class);
    }
    public function instantMedicalRecord()
    {
        return $this->belongsTo(InstantMedicalRecord::class);
    }

    public function uploadedUser()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
