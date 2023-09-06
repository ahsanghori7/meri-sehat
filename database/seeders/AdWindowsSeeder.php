<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdWindowsSeeder extends Seeder
{
    public function run()
    {
        DB::table('ad_windows')->insert([
            'name' => 'Portrait',
            'dimensions' => '240 X 760',
        ]);

        DB::table('ad_windows')->insert([
            'name' => 'Landscape',
            'dimensions' => '760 X 240',
        ]);
    }
}
