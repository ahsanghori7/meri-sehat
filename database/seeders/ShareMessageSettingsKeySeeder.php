<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class ShareMessageSettingsKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $key1 = Settings::where('key','health_scan_report_title')->exists();
        $key2 = Settings::where('key','health_scan_report_chooserTitle')->exists();
        $key3 = Settings::where('key','health_scan_repport_linkUrl')->exists();
        if(!$key1){
            Settings::create([
                'key' => 'health_scan_report_title',
                'input_type' => 'string',
                'value' => 'Health Check Result',
                'title' => 'Health Scan Report Title',
            ]);
        }
        if(!$key2){
            Settings::create([
                'key' => 'health_scan_report_chooserTitle',
                'input_type' => 'string',
                'value' => '',
                'title' => 'Health Scan Report Chooser Title',
            ]);
        }
        if(!$key3){
            Settings::create([
                'key' => 'health_scan_repport_linkUrl',
                'input_type' => 'string',
                'value' => 'www.merisehat.pk/app',
                'title' => 'Health Scan Report Url',
            ]);
        }
    }
}
