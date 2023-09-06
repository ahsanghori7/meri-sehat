<?php

namespace Database\Seeders;

use App\Models\Degree;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Degree::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                "name" => "B.D Sc",
                "full_name" => "Bachelor of Dental Science",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "BMedSc",
                "full_name" => "Bachelor of Medical Science",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "BN",
                "full_name" => "Bachelor of Nursing",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "BSc",
                "full_name" => "Bachelor of Science",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "CGO",
                "full_name" => "Certificate of Gynaecological Oncology",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "DPhil",
                "full_name" => "Doctor of Philosophy (Oxon)",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "DRACOG",
                "full_name" => "Diploma of the Royal Australian College of Obstetricians and Gynaecologists",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "DRCOG",
                "full_name" => "Diploma of the Royal College of Obstetricians and Gynaecologists (UK)",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FACP",
                "full_name" => "Fellow of American College of Physicians",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FAICD",
                "full_name" => "Fellow of the Australian Institute of Company Directors",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FASSA",
                "full_name" => "Fellow of Academy of the Social Sciences in Australia",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FCHSE",
                "full_name" => "Fellow of Chemical Science and Engineering",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FRACDS (OMS)",
                "full_name" => "Fellow of Royal Australian College of Dental Surgeons – Oral and Maxillofacial Surgery",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FRACGP",
                "full_name" => "Fellow of the Royal Australian College of General Practitioners",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FRACP",
                "full_name" => "Fellow of Royal Australasian College of Physicians",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FRACS",
                "full_name" => "Fellow Royal Australasian College of Surgeons",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FRANZCOG",
                "full_name" => "Fellow of Royal Australian and New Zealand College of Obstetricians and Gynaecologists",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FRANZCR",
                "full_name" => "Fellow of Royal Australian and New Zealand College of Radiologists",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FRCOG",
                "full_name" => "Fellow of the Royal College of Obstetricians and Gynaecologists",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FRCP",
                "full_name" => "Fellow of the Royal College of Physicians",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FRCPA",
                "full_name" => "Fellow of the Royal College of Pathologists of Australasia",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FRCPath",
                "full_name" => "Fellow of the Royal College of Pathologists",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FRCPI",
                "full_name" => "Fellow of the Royal College of Physicians of Ireland",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FRCS (UK)",
                "full_name" => "Royal College of Surgeons (UK)",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "FRS",
                "full_name" => "Fellow of the Royal Society",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ]
        ];
        Degree::insert($data);
    }
}
