<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionElementTypeCategory extends Model
{
    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $table = 'prescription_element_type_categories';

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function prescription_element_type(){
        return $this->belongsTo(PrescriptionElementType::class,'prescription_element_type_id'); 
    }

    public function prescription_element()
    {
        return $this->belongsTo(PrescriptionElement::class, 'prescription_element_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
