<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Common\Constant;

class PrescriptionElement extends Model
{
    use HasFactory;

    protected $connection= 'mysql';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $with = ['MedicineUnits','MedicineType'];
    protected $casts = [
        'status' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = $this->getConnection()->getDatabaseName().'.'.$this->getTable();
        parent::__construct($attributes);
    }

    public function getJsonAttributeAttribute($value)
    {
        return json_decode($value);
    }

    public function MedicineType()
    {
        return $this->belongsTo(PrescriptionMedicineType::class, 'type_id')->where('status', true);
    }

    public function MedicineUnits()
    {
        return $this->hasMany(PrescriptionMedicineUnit::class, 'prescription_medicine_types_id', 'type_id')->where('status', true);
    }

    /**
     * This method is used to get all the diseases in Alphabetical format
     */
    public static function getMedicinesByAlphabeticalOrder($search, $all)
    {
        $getMedicines = self::where('prescription_element_types_id', Constant::MEDICINE_ID)
        ->where('status', true)
        ->where(function($query) use ($search){
            if($search){
                $query->orWhere('name', 'like', "%$search%");
                $query->orWhere('description', 'like', "%$search%");
            }
        })
        ->orderBy('name', 'ASC')
        ->get();
        if(!count($getMedicines)){
            return null;
        }
        if(isset($all)){
            return $getMedicines;
        }
        $response = [];
        foreach($getMedicines as $getMedicine){
            $response[substr($getMedicine['name'], 0, 1)][] = $getMedicine;
        }
        $response['headings'] = array_keys($response);
        return $response;
    }
    /**
     * This method is used to get all the Prescription Elements with respect to their type
     */

    public function type(){
        return $this->belongsTo(PrescriptionElementType::class,'prescription_element_types_id');
    }

    public function category()
    {
        return $this->hasMany(PrescriptionElementTypeCategory::class, 'prescription_element_id');
    }

    public static function getPrescriptionElements($type)
    {
        return PrescriptionElement::where('status', true)
        ->where(function($query) use($type){
            if($type){
                $query->where('prescription_element_types_id', $type);
            }

        })->with('type', 'category')
        ->orderBy('id', 'ASC')
        ->get();
    }
}
