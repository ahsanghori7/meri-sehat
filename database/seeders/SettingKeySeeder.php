<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings;


class SettingKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        if (!Settings::where('key', 'clinic_visits_fee')->exists()) {
            Settings::create([
                'key' => 'clinic_visits_fee',
                'input_type' => 'numeric',
                'value' => '500',
                'title' => 'Clinic Visits Fee',
                'description' => 'Clinic Visits Fee',
                'json_params' => '',
                'created_at' => now(),
                'updated_at' => now(),
                'group' => 'Clinic Visits',
            ]);
        }
        if (!Settings::where('key', 'clinic_visits_discounted_fees')->exists()) {
            Settings::create([
                'key' => 'clinic_visits_discounted_fees',
                'input_type' => 'numeric',
                'value' => '450',
                'title' => 'Clinic Visits Discounted Fees',
                'description' => 'Clinic Visits Discounted Fees',
                'json_params' => '',
                'created_at' => now(),
                'updated_at' => now(),
                'group' => 'Clinic Visits',
            ]);
        }
        if (!Settings::where('key', 'clinic_visits_discounted_percentage')->exists()) {
            Settings::create([
                'key' => 'clinic_visits_discounted_percentage',
                'input_type' => 'numeric',
                'value' => '10',
                'title' => 'Clinic Visits Discounted Percentage',
                'description' => 'Clinic Visits Discounted Percentage',
                'json_params' => '',
                'created_at' => now(),
                'updated_at' => now(),
                'group' => 'Clinic Visits',
            ]);
        }
        if (!Settings::where('key', 'clinic_visits_penalty_charges')->exists()) {
            Settings::create([
                'key' => 'clinic_visits_penalty_charges',
                'input_type' => 'numeric',
                'value' => '3',
                'title' => 'Clinic Visits Penalty Charges',
                'description' => 'Clinic Visits Penalty Charges',
                'json_params' => '',
                'created_at' => now(),
                'updated_at' => now(),
                'group' => 'Clinic Visits',
            ]);
        }
        if (!Settings::where('key', 'clinic_visits_ms_commission')->exists()) {
            Settings::create([
                'key' => 'clinic_visits_ms_commission',
                'input_type' => 'numeric',
                'value' => '88',
                'title' => 'Clinic Visits MS Comission',
                'description' => 'Clinic Visits MS Comission',
                'json_params' => '',
                'created_at' => now(),
                'updated_at' => now(),
                'group' => 'Clinic Visits',
            ]);
        }
        if (!Settings::where('key', 'non_subscribed_member_health_scan_count')->exists()) {
            Settings::create([
                'key' => 'non_subscribed_member_health_scan_count',
                'input_type' => 'numeric',
                'value' => '2',
                'title' => 'Non Subscribed Member Health Scan Count',
                'description' => 'Non Subscribed Member Health Scan Count',
                'json_params' => '',
                'created_at' => now(),
                'updated_at' => now(),
                'group' => 'Scan Management',
            ]);
        }
        if (!Settings::where('key', 'non_subscribed_member_health_scan_days_limit')->exists()) {
            Settings::create([
                'key' => 'non_subscribed_member_health_scan_days_limit',
                'input_type' => 'numeric',
                'value' => '7',
                'title' => 'Non Subscribed Member Health Scan Days Limit',
                'description' => 'Non Subscribed Member Health Scan Days Limit',
                'json_params' => '',
                'created_at' => now(),
                'updated_at' => now(),
                'group' => 'Scan Management',
            ]);
        }
        if (!Settings::where('key', 'corporat_text')->exists()) {
            Settings::create([
                'key' => 'corporat_text',
                'input_type' => 'string',
                'value' => '50% Cut in hospitalisations',
                'title' => 'Corporat Text',
                'description' => 'Corporat Text',
                'json_params' => '',
                'created_at' => now(),
                'updated_at' => now(),
                'group' => 'Promotion',
            ]);
        }
    }
}
