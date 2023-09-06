<?php

namespace Database\Seeders;
use App\Models\{PrescriptionElementType};

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrescriptionElementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PrescriptionElementType::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        PrescriptionElementType::create([
            'name' => 'Medicine',
            'slug' => 'medicine'
        ]);
        PrescriptionElementType::create([
            'name' => 'Lab Test',
            'slug' => 'lab-test'
        ]);
        PrescriptionElementType::create([
            'name' => 'Prescription',
            'slug' => 'prescription'
        ]);
    }
}
