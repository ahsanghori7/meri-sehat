<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, RolePermission};

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        User::create([
            'name' => 'SuperAdmin',
            'email' => 'admin@admin.com',
            'role_id' => '1',
            'password' => Hash::make('Admin@123'),
            'role_id' => '1',
        ]);
        
        RolePermission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
