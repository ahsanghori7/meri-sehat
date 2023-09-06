<?php

namespace Database\Seeders;

use App\Models\Certification;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Certification Test 1',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Certification Test 2',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        Certification::insert($data);
    }
}
