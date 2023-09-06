<?php

namespace Database\Seeders;

use App\Models\Feedbacks;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Feedbacks::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Feedbacks::create([
            'name' => 'Feedback 1',
            'status' => 1
        ]);

        Feedbacks::create([
            'name' => 'Feedback 2',
            'status' => 1
        ]);
    }
}
