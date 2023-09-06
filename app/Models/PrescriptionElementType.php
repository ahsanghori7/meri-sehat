<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionElementType extends Model
{
    use HasFactory;
    protected $connection= 'mysql';
    protected $guarded = ['id'];

    public const MEDICINE = 1;
    public const LABTEST = 2;
    public const PRESCRIPTION = 3;

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }
    public function prescriptionElement()
    {
        return $this->hasMany(PrescriptionElement::class,'prescription_element_types_id');
    }
    /**
     * This method is used to get all the Prescription Element Types
     */
    public static function getPrescriptionElementTypes()
    {
        return PrescriptionElementType::where('status', true)->get();
    }

    public function prescriptionElementTypeCategories()
    {
        return $this->hasMany(PrescriptionElementTypeCategory::class,'prescription_element_type_id');
    }

}
