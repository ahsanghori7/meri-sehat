<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\{Role, User, RolePermission};

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Role::create([
            'name' => 'Sales',
            'slug' => 'sale',
            'translation' => 'en',
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
