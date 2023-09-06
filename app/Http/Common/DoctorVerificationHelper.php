<?php

namespace App\Http\Common;

use App\Models\DoctorVerification;

class DoctorVerificationHelper {

    public static function insertAbout($doctorId, $tab)
    {
        $data = [
            [
                'doctor_id' => $doctorId,
                'tab_name' => $tab,
                'key' => 'phone',
                'status' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'doctor_id' => $doctorId,
                'tab_name' => $tab,
                'key' => 'email',
                'status' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'doctor_id' => $doctorId,
                'tab_name' => $tab,
                'key' => 'city',
                'status' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'doctor_id' => $doctorId,
                'tab_name' => $tab,
                'key' => 'image',
                'status' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'doctor_id' => $doctorId,
                'tab_name' => $tab,
                'key' => 'pmc_no',
                'status' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'doctor_id' => $doctorId,
                'tab_name' => $tab,
                'key' => 'speciality',
                'status' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'doctor_id' => $doctorId,
                'tab_name' => $tab,
                'key' => 'service',
                'status' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'doctor_id' => $doctorId,
                'tab_name' => $tab,
                'key' => 'account_number',
                'status' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'doctor_id' => $doctorId,
                'tab_name' => $tab,
                'key' => 'cnic',
                'status' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DoctorVerification::insert($data);
    }
}