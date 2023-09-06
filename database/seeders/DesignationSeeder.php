<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Designation;
class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Designation::create([
            'name' => 'Senior',
        ]);
        Designation::create([
            'name' => 'Consultant',
        ]);
    }
}
