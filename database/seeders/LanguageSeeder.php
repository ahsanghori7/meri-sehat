<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Language::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Language::truncate();
        Language::create([
            'id' => 1,
            'name' => 'English',
            'slug' => 'en',
            'direction' => 'ltr'
        ]);
        Language::create([
            'id' => 2,
            'name' => 'Urdu',
            'slug' => 'ur',
            'direction' => 'rtl'
        ]);
    }
}
