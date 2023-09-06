<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionMedicineUnit extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $table = 'prescription_medicine_units';

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }
    public function prescriptionMedicineType()
    {
        return $this->belongsTo(PrescriptionMedicineType::class,'prescription_medicine_types_id');
    }

}
