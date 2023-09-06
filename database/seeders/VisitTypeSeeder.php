<?php

namespace Database\Seeders;

use App\Models\VisitType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisitTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        VisitType::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        VisitType::create([
            'name' => 'Visit Type 1',
            'status' => 1
        ]);

        VisitType::create([
            'name' => 'Visit Type 2',
            'status' => 1
        ]);
    }
}
