<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{PrescriptionElement, PrescriptionElementTypeCategory};

class PrescriptionElementsTypeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(PrescriptionElement::count() > 10){
            $getPrescriptionElements = PrescriptionElement::where('prescription_element_types_id', 2)->where('status', 1)->get();
            foreach($getPrescriptionElements as $data){
                PrescriptionElementTypeCategory::create(
                    [
                        'prescription_element_type_id' => 2,
                        'name' => $data->name,
                        'slug' => $data->name,
                        'prescription_element_id' => $data->id,
                        'doctor_id' => null,
                    ]
                );
            }
        }
    }
}
