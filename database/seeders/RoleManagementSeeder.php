<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Role, Permission};
use Illuminate\Support\Facades\DB;

class RoleManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();

        Role::create([
            'slug' => \Str::slug('SuperAdmin'),
            'translation' => 'en',
            'name' => 'SuperAdmin',
        ]);
        Role::create([
            'slug' => \Str::slug('User'),
            'translation' => 'en',
            'name' => 'User',
        ]);
        Role::create([
            'slug' => \Str::slug('Doctor'),
            'translation' => 'en',
            'name' => 'Doctor',
        ]);

        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
    }
}
