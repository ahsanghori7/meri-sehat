<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings;

class DoctorEarningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Settings::create([
            'key' => 'instant_consultation_fee',
            'input_type' => 'numeric',
            'value' => '500',
            'title' => 'Instant Consultation Fee',
            'description' => 'Instant Consultation Fee',
            'json_params' => '',
            'created_at' => now(),
            'updated_at' => now(),
            'group' => 'Instant Consultation',
        ]);
        Settings::create([
            'key' => 'instant_consultation_discounted_fees',
            'input_type' => 'numeric',
            'value' => '250',
            'title' => 'Instant Consultation Discounted Fees',
            'description' => 'Instant Consultation Discounted Fees',
            'json_params' => '',
            'created_at' => now(),
            'updated_at' => now(),
            'group' => 'Instant Consultation',
        ]);
        Settings::create([
            'key' => 'instant_consultation_discounted_percentage',
            'input_type' => 'numeric',
            'value' => '10',
            'title' => 'Instant Consultation Discounted Percentage',
            'description' => 'Instant Consultation Discounted Percentage',
            'json_params' => '',
            'created_at' => now(),
            'updated_at' => now(),
            'group' => 'Instant Consultation',
        ]);
        Settings::create([
            'key' => 'instant_consultation_penalty_charges',
            'input_type' => 'numeric',
            'value' => '5',
            'title' => 'Instant Consultation Penalty Charges',
            'description' => 'Instant Consultation Penalty Charges',
            'json_params' => '',
            'created_at' => now(),
            'updated_at' => now(),
            'group' => 'Instant Consultation',
        ]);
        Settings::create([
            'key' => 'instant_consultation_ms_commission',
            'input_type' => 'numeric',
            'value' => '10',
            'title' => 'Instant Consultation MS Comission',
            'description' => 'Instant Consultation MS Comission',
            'json_params' => '',
            'created_at' => now(),
            'updated_at' => now(),
            'group' => 'Instant Consultation',
        ]);
    }
}
