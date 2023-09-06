<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ReasonForVisit extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('reason_for_visit')->delete();

        $reasons = array(
            array('reason' => 'Allergies'),
            array('reason' => 'Alopecia'),
            array('reason' => 'Anemia'),
            array('reason' => 'Others'),
        );

        \DB::table('reason_for_visit')->insert($reasons);
    }
}
