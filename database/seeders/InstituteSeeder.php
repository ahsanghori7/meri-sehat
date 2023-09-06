<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institute;
class InstituteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Institute::create([
            'name' => 'liaquat hp',
        ]);
        Institute::create([
            'name' => 'NICD hp',
        ]);
    }
}
