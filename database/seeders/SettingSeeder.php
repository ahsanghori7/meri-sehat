<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('settings')->truncate();

        DB::table('settings')->insert([
            'key' => 'app_version',
            'input_type' => 'number',
            'title' => 'Application Version',
            'value' => '1.0',
            'group' => '',
            'description' => ''
        ]);
        DB::table('settings')->insert([
            'key' => 'meri_sehat_commission_type',
            'input_type' => 'dropdown',
            'title' => 'MeriSehat Commision Type',
            'value' => '1',
            'description' => 'MeriSehat Commision Type',
            'json_params' => '{ "type":"dropdown","data":[{"label":"Percentage","value":1},{"label":"Fixed Amount","value":0}] }'
        ]);
        
        DB::table('settings')->insert([
            'key' => 'meri_sehat_commission',
            'input_type' => 'number',
            'title' => 'MeriSehat Commision',
            'value' => '0',
            'description' => 'MeriSehat Commision on every appointment'
        ]);

        // DB::table('settings')->insert([
        //     'key' => 'vat_percentage',
        //     'input_type' => 'number',
        //     'title' => 'Ticket Vat Charges Percentage',
        //     'value' => '15',
        //     'description' => ''
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'adult_general_charges',
        //     'input_type' => 'number',
        //     'title' => 'Adult General Access Charges',
        //     'value' => '100',
        //     'description' => ''
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'child_general_charges',
        //     'input_type' => 'number',
        //     'title' => 'Child General Access Charges',
        //     'value' => '50',
        //     'description' => ''
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'address',
        //     'input_type' => 'text',
        //     'title' => 'The Groves Address',
        //     'value' => 'Al Khuzama Park, Diplomatic Quarters Riyadh, KSA',
        //     'description' => ''
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'duration',
        //     'input_type' => 'text',
        //     'title' => 'The Groves Duration',
        //     'value' => 'Thursday November 11, 2021 to Friday March 11, 2022',
        //     'description' => ''
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'timing',
        //     'input_type' => 'text',
        //     'title' => 'The Groves Timing',
        //     'value' => '3:00 pm to 1:00 am',
        //     'description' => ''
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'weekdays',
        //     'input_type' => 'text',
        //     'title' => 'The Groves Opening Days',
        //     'value' => 'Everyday',
        //     'description' => ''
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'opening_time',
        //     'input_type' => 'text',
        //     'title' => 'The Groves Opening Time',
        //     'value' => '13:30:00',
        //     'description' => 'Opening Time (24 hour format)'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'closing_time',
        //     'input_type' => 'text',
        //     'title' => 'The Groves Closing Time',
        //     'value' => '02:00:00',
        //     'description' => 'Closing Time (24 hour format)'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'contact_number',
        //     'input_type' => 'text',
        //     'title' => 'Contact Number',
        //     'value' => '03006295658',
        //     'description' => ''
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'start_date',
        //     'input_type' => 'text',
        //     'title' => 'Grove Start Date',
        //     'value' => '2021-09-01',
        //     'description' => 'Format (YYYY-MM-DD)'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'end_date',
        //     'input_type' => 'text',
        //     'title' => 'Grove End Date',
        //     'value' => '2021-12-31',
        //     'description' => 'Format (YYYY-MM-DD)'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'hours_before_cancel',
        //     'input_type' => 'number',
        //     'title' => 'Ticket Cancel befors Hours',
        //     'value' => '4',
        //     'description' => ''
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'paytabs_profile_id',
        //     'input_type' => 'text',
        //     'title' => 'Paytabs Profile ID',
        //     'value' => '',
        //     'description' => ''
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'paytabs_server_key',
        //     'input_type' => 'text',
        //     'title' => 'Paytabs Server Key',
        //     'value' => '',
        //     'description' => ''
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'paytabs_client_key',
        //     'input_type' => 'text',
        //     'title' => 'Paytabs Client Key',
        //     'value' => '',
        //     'description' => ''
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'call_support_number',
        //     'input_type' => 'text',
        //     'title' => 'Call Support Phone',
        //     'value' => '+966123123456',
        //     'description' => ''
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'chat_support_number',
        //     'input_type' => 'text',
        //     'title' => 'Chat Support Phone',
        //     'value' => '+966123123456',
        //     'description' => ''
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'attendance_otp',
        //     'input_type' => 'text',
        //     'title' => 'Attendance Scan Screen OTP',
        //     'value' => '',
        //     'description' => 'System Generated Attendance Scan Screen OTP'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'tickets_limit_for_day',
        //     'input_type' => 'number',
        //     'title' => 'Ticket Booking Limit for Day',
        //     'value' => '100',
        //     'description' => 'Ticket Booking Limit for Day'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'location_url',
        //     'input_type' => 'text',
        //     'title' => 'Google Map Location Url',
        //     'value' => '',
        //     'description' => 'Google Map Location Url'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'android_app_link',
        //     'input_type' => 'text',
        //     'title' => 'Android Application Link',
        //     'value' => 'https://play.google.com/store/apps',
        //     'description' => '-'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'ios_app_link',
        //     'input_type' => 'text',
        //     'title' => 'iOS Application Link',
        //     'value' => 'https://www.apple.com/app-store/',
        //     'description' => '-'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'android_app_version',
        //     'input_type' => 'text',
        //     'title' => 'Android Application Version',
        //     'value' => '1.0',
        //     'description' => '-'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'ios_app_version',
        //     'input_type' => 'text',
        //     'title' => 'iOS Application Version',
        //     'value' => '1.0',
        //     'description' => '-'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'home_contact_number',
        //     'input_type' => 'text',
        //     'title' => 'Home Page Contact Number',
        //     'value' => '03000000000',
        //     'description' => '-'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'enable_apple_pay',
        //     'input_type' => 'text',
        //     'title' => 'Enable Apple Pay',
        //     'value' => 'yes',
        //     'description' => '( "yes" => enable, "no" => disable )'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'new_end_date',
        //     'input_type' => 'text',
        //     'title' => 'Grove New End Date',
        //     'value' => '2021-12-31',
        //     'description' => 'End date for new app update - Format (YYYY-MM-DD)'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'is_locale',
        //     'input_type' => 'text',
        //     'title' => 'Enable Arabic Language for App',
        //     'value' => 'yes',
        //     'description' => '( "yes" => enable, "no" => disable )'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'home_location_heading',
        //     'input_type' => 'text',
        //     'title' => 'Home Location heading',
        //     'value' => 'Location',
        //     'description' => '-'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'home_contact_heading',
        //     'input_type' => 'text',
        //     'title' => 'Home Contact heading',
        //     'value' => 'Contact',
        //     'description' => '-'
        // ]);

        // DB::table('settings')->insert([
        //     'key' => 'cancellation_timing',
        //     'input_type' => 'number',
        //     'title' => 'Cancellation Timing',
        //     'value' => '2',
        //     'description' => '-'
        // ]);

    }
}
