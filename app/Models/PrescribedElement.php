<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescribedElement extends Model
{
    use HasFactory;

    protected $connection= 'mysql';
    protected $table = 'prescribed_elements';
    protected $guarded = ['id'];
    protected $with = ['prescriptionElement'];
    protected $casts = [
        'status' => 'boolean',
        'dosage' => 'double',
        'is_after_meal' => 'boolean',
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

    public function getNumberOfDaysAttribute($value)
    {
        if ($value != '') {
            if (Request()->segment(2) === 'v1' && Request()->header('platform') == 'app') {
                if (str_contains($value, 'week')) {
                    $value = Intval($value)*7;
                } elseif (str_contains($value, 'month')) {
                    $value = Intval($value)*30;
                } elseif (str_contains($value, 'day')) {
                    $value = Intval($value)*1;
                } elseif (str_contains($value, 'year')) {
                    $value = Intval($value)*365;
                }
                return $value;
            } else {
                return $value;
            }
        }
    }

    public function prescription(){
        return $this->belongsTo(Prescription::class,'prescription_id');
    }
    public function prescriptionElement(){
        return $this->belongsTo(PrescriptionElement::class);
    }
}
