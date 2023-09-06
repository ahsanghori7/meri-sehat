<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserSubscription;

class ZohairShoaibSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = 6886;
        if(env('APP_ENV') == 'staging' || env('APP_ENV') == 'local'){
            $user_id = 442;
        }
        
        UserSubscription::create([
            'refill_date' => '2023-05-27 11:03:38',
            'is_yearly' => 0,
            'user_id'=> $user_id,
            'subscription_id'=> 3,
            'receipt_data'=> '{"scan_limit": "unlimited", "sehat_score": "unlimited", "health_vitals": "unlimited", "detail_history": "unlimited", "title_scan_limit": "SCAN LIMIT", "title_sehat_score": "SEHAT SCORE", "consume_scan_limit": "unlimited", "consume_sehat_score": "unlimited", "free_video_consults": "6", "online_prescription": "unlimited", "title_health_vitals": "VITALS (Sehat Scan)", "title_detail_history": "HISTORY", "consume_health_vitals": "unlimited", "value_text_scan_limit": "Unlimited Scans in a day", "consume_detail_history": "unlimited", "description_scan_limit": "Some packages are limited to 1 scan a day while others come with unlimited scans daily", "value_text_sehat_score": "Yes", "description_sehat_score": "Unique health score generated on your health measurements and vital signs", "value_text_health_vitals": "Blood Pressure, Oxygen Saturation (SpO2), Heart Rate, Respiration Rate and Stress Levels", "description_health_vitals": "Track your health through our Artificial Intelligence based mobile app - No sensors needed", "title_free_video_consults": "DOCTOR CONSULT - VIDEO CALL", "title_online_prescription": "ONLINE PRESCRIPTION", "value_text_detail_history": "Yes", "dedicated_customer_support": "unlimited", "description_detail_history": "Save your Blood Pressure, Oxygen Saturation, Heart Rate history and more", "consume_free_video_consults": 0, "consume_online_prescription": "unlimited", "value_text_free_video_consults": "6 sessions  per month", "value_text_online_prescription": "Yes", "description_free_video_consults": "Video call our friendly and qualified staff doctors and let them help you with your medicial issues", "description_online_prescription": "After consultation, our Video Doctors will send you a customized prescription", "title_dedicated_customer_support": "DEDICATED CUSTOMER SUPPORT", "consume_dedicated_customer_support": "unlimited", "value_text_dedicated_customer_support": "Yes", "description_dedicated_customer_support": "Friendly, caring and respectful customer support to help you with all your needs"}',
            'start_date'=> '2023-04-27 11:03:38',
            'end_date'=> '2025-04-27 11:03:38',
            'is_paid'=> 1,
            'status'=> 1,
            'created_at'=> '2023-04-27 11:03:38',
            'updated_at'=> '2023-04-27 11:03:38',
            'is_three_day_expire_message'=> 0,
            'is_three_day_expire_email'=> 0,
            'is_three_day_expire_push_notification'=> 0,
            'is_day_expire_message'=> 0,
            'is_day_expire_push_notification'=> 0,
            'is_expired' => 0
        ]);
    }
}
