<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $casts = [
        'status' => 'boolean',
    ];
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
    public function prescribedMedicine()
    {
        return $this->hasMany(PrescribedElement::class, 'prescription_id')
        ->whereHas('prescriptionElement.type', function($query){
            $query->where('id', PrescriptionElementType::MEDICINE);
        });
    }
    public function prescribedLab()
    {
        return $this->hasMany(PrescribedElement::class, 'prescription_id')
        ->whereHas('prescriptionElement.type', function($query){
            $query->where('id', PrescriptionElementType::LABTEST);
        });
        return $this->hasMany(PrescribedElement::class, 'prescription_id')->where('prescription_element_id', PrescriptionElementType::LABTEST);
    }
    public function prescribedPrescription()
    {
        return $this->hasMany(PrescribedElement::class, 'prescription_id')
        ->whereHas('prescriptionElement.type', function($query){
            $query->where('id', PrescriptionElementType::PRESCRIPTION);
        });
        return $this->hasMany(PrescribedElement::class, 'prescription_id')->where('prescription_element_id', PrescriptionElementType::PRESCRIPTION);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function prescriptionElement()
    {
        return $this->hasMany(PrescribedElement::class,'prescription_id');
    }

}
