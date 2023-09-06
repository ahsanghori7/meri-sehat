<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Permission};
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        Permission::create([
            'id' => 1,
            'parent_id' => null,
            'name' => 'Booking Management',
            'slug' => \Str::slug('Booking Management'),
        ]);
            Permission::create([
                'id' => 2,
                'parent_id' => 1,
                'name' => 'Edit',
                'slug' => \Str::slug('Booking Management')."-edit",
            ]);
            Permission::create([
                'id' => 3,
                'parent_id' => 1,
                'name' => 'Update Status',
                'slug' => \Str::slug('Booking Management')."-update-status",
            ]);
            Permission::create([
                'id' => 4,
                'parent_id' => 1,
                'name' => 'Detail',
                'slug' => \Str::slug('Booking Management')."-detail",
            ]);
            Permission::create([
                'id' => 5,
                'parent_id' => 1,
                'name' => 'Questionaire Form',
                'slug' => \Str::slug('Booking Management')."-questionaire-form",
            ]);

            
        Permission::create([
            'id' => 6,
            'parent_id' => null,
            'name' => 'Scan Management',
            'slug' => \Str::slug('Scan Management'),
        ]);
            Permission::create([
                'id' => 7,
                'parent_id' => 6,
                'name' => 'View',
                'slug' => \Str::slug('Scan Management')."-view",
            ]);
            Permission::create([
                'id' => 8,
                'parent_id' => 6,
                'name' => 'Update',
                'slug' => \Str::slug('Scan Management')."-update",
            ]);

            Permission::create([
                'id' => 9,
                'parent_id' => null,
                'name' => 'Menu Management',
                'slug' => \Str::slug('Menu Management'),
            ]);
                Permission::create([
                    'id' => 10,
                    'parent_id' => 9,
                    'name' => 'Add',
                    'slug' => \Str::slug('Menu Management')."-add",
                ]);
                
                Permission::create([
                    'id' => 11,
                    'parent_id' => 9,
                    'name' => 'View',
                    'slug' => \Str::slug('Menu Management')."-view",
                ]);
                Permission::create([
                    'id' => 12,
                    'parent_id' => 9,
                    'name' => 'Update',
                    'slug' => \Str::slug('Menu Management')."-update",
                ]);
                
            Permission::create([
                'id' => 17,
                'parent_id' => null,
                'name' => 'Article Management',
                'slug' => \Str::slug('Article Management'),
            ]);
                Permission::create([
                    'id' => 18,
                    'parent_id' => 17,
                    'name' => 'Add',
                    'slug' => \Str::slug('Article Management')."-add",
                ]);
                
                Permission::create([
                    'id' => 19,
                    'parent_id' => 17,
                    'name' => 'View',
                    'slug' => \Str::slug('Article Management')."-edit",
                ]);

                Permission::create([
                    'id' => 21,
                    'parent_id' => null,
                    'name' => 'Disease Page',
                    'slug' => \Str::slug('Disease Page'),
                ]);
                    Permission::create([
                        'id' => 22,
                        'parent_id' => 21,
                        'name' => 'Add',
                        'slug' => \Str::slug('Disease Page')."-add",
                    ]);
                    Permission::create([
                        'id' => 23,
                        'parent_id' => 21,
                        'name' => 'Change Page Layout',
                        'slug' => \Str::slug('Disease Page')."-page-layout",
                    ]);
                    Permission::create([
                        'id' => 24,
                        'parent_id' => 21,
                        'name' => 'View',
                        'slug' => \Str::slug('Disease Page')."-view",
                    ]);
                    Permission::create([
                        'id' => 25,
                        'parent_id' => 21,
                        'name' => 'Update',
                        'slug' => \Str::slug('Disease Page')."-update",
                    ]);

                Permission::create([
                    'id' => 26,
                    'parent_id' => null,
                    'name' => 'Drug Page',
                    'slug' => \Str::slug('Drug Page'),
                ]);
                    Permission::create([
                        'id' => 27,
                        'parent_id' => 26,
                        'name' => 'Add',
                        'slug' => \Str::slug('Drug Page')."-add",
                    ]);
                    Permission::create([
                        'id' => 28,
                        'parent_id' => 26,
                        'name' => 'Change Page Layout',
                        'slug' => \Str::slug('Drug Page')."-page-layout",
                    ]);
                    Permission::create([
                        'id' => 29,
                        'parent_id' => 26,
                        'name' => 'View',
                        'slug' => \Str::slug('Drug Page')."-view",
                    ]);
                    Permission::create([
                        'id' => 30,
                        'parent_id' => 26,
                        'name' => 'Update',
                        'slug' => \Str::slug('Drug Page')."-update",
                    ]);

                Permission::create([
                    'id' => 31,
                    'parent_id' => null,
                    'name' => 'Category Management',
                    'slug' => \Str::slug('Category Management'),
                ]);
                    Permission::create([
                        'id' => 32,
                        'parent_id' => 31,
                        'name' => 'Add',
                        'slug' => \Str::slug('Category Management')."-add",
                    ]);
                    Permission::create([
                        'id' => 33,
                        'parent_id' => 31,
                        'name' => 'Change Page Layout',
                        'slug' => \Str::slug('Category Management')."-page-layout",
                    ]);
                    Permission::create([
                        'id' => 34,
                        'parent_id' => 31,
                        'name' => 'View',
                        'slug' => \Str::slug('Category Management')."-view",
                    ]);
                    Permission::create([
                        'id' => 35,
                        'parent_id' => 31,
                        'name' => 'Update',
                        'slug' => \Str::slug('Category Management')."-update",
                    ]);
                
                Permission::create([
                    'id' => 36,
                    'parent_id' => null,
                    'name' => 'Default Page',
                    'slug' => \Str::slug('Default Page'),
                ]);
                    Permission::create([
                        'id' => 37,
                        'parent_id' => 36,
                        'name' => 'Add',
                        'slug' => \Str::slug('Default Page')."-add",
                    ]);
                    Permission::create([
                        'id' => 38,
                        'parent_id' => 36,
                        'name' => 'View',
                        'slug' => \Str::slug('Default Page')."-view",
                    ]);
                    Permission::create([
                        'id' => 39,
                        'parent_id' => 36,
                        'name' => 'Update',
                        'slug' => \Str::slug('Default Page')."-update",
                    ]);
                Permission::create([
                    'id' => 40,
                    'parent_id' => null,
                    'name' => 'FAQ Category',
                    'slug' => \Str::slug('FAQ Category'),
                ]);
                    Permission::create([
                        'id' => 41,
                        'parent_id' => 40,
                        'name' => 'Add',
                        'slug' => \Str::slug('FAQ Category')."-add",
                    ]);
                    Permission::create([
                        'id' => 42,
                        'parent_id' => 40,
                        'name' => 'View',
                        'slug' => \Str::slug('FAQ Category')."-view",
                    ]);
                    Permission::create([
                        'id' => 43,
                        'parent_id' => 40,
                        'name' => 'Update',
                        'slug' => \Str::slug('FAQ Category')."-update",
                    ]);   
                Permission::create([
                    'id' => 44,
                    'parent_id' => null,
                    'name' => 'FAQ',
                    'slug' => \Str::slug('FAQ'),
                ]);
                    Permission::create([
                        'id' => 45,
                        'parent_id' => 44,
                        'name' => 'Add',
                        'slug' => \Str::slug('FAQ')."-add",
                    ]);
                    Permission::create([
                        'id' => 46,
                        'parent_id' => 44,
                        'name' => 'View',
                        'slug' => \Str::slug('FAQ')."-view",
                    ]);
                    Permission::create([
                        'id' => 47,
                        'parent_id' => 44,
                        'name' => 'Update',
                        'slug' => \Str::slug('FAQ')."-update",
                    ]);    
                
                Permission::create([
                    'id' => 48,
                    'parent_id' => null,
                    'name' => 'Tags',
                    'slug' => \Str::slug('Tags'),
                ]);
                    Permission::create([
                        'id' => 49,
                        'parent_id' => 48,
                        'name' => 'Add',
                        'slug' => \Str::slug('Tags')."-add",
                    ]);
                    Permission::create([
                        'id' => 51,
                        'parent_id' => 48,
                        'name' => 'View',
                        'slug' => \Str::slug('Tags')."-view",
                    ]);
                    Permission::create([
                        'id' => 52,
                        'parent_id' => 48,
                        'name' => 'Update',
                        'slug' => \Str::slug('Tags')."-update",
                    ]);    
                    
                Permission::create([
                    'id' => 53,
                    'parent_id' => null,
                    'name' => 'Admin Roles',
                    'slug' => \Str::slug('Admin Roles'),
                ]);
                    Permission::create([
                        'id' => 54,
                        'parent_id' => 53,
                        'name' => 'Add',
                        'slug' => \Str::slug('Admin Roles')."-add",
                    ]);
                    Permission::create([
                        'id' => 55,
                        'parent_id' => 53,
                        'name' => 'View',
                        'slug' => \Str::slug('Admin Roles')."-view",
                    ]);
                    Permission::create([
                        'id' => 56,
                        'parent_id' => 53,
                        'name' => 'Update',
                        'slug' => \Str::slug('Admin Roles')."-update",
                    ]);  

                Permission::create([
                    'id' => 57,
                    'parent_id' => null,
                    'name' => 'Rights Management',
                    'slug' => \Str::slug('Rights Management'),
                ]);
                    Permission::create([
                        'id' => 58,
                        'parent_id' => 57,
                        'name' => 'Add',
                        'slug' => \Str::slug('Rights Management')."-add",
                    ]);
                    Permission::create([
                        'id' => 59,
                        'parent_id' => 57,
                        'name' => 'View',
                        'slug' => \Str::slug('Rights Management')."-view",
                    ]);
                    Permission::create([
                        'id' => 60,
                        'parent_id' => 57,
                        'name' => 'Update',
                        'slug' => \Str::slug('Rights Management')."-update",
                    ]);  
                        
                    Permission::create([
                        'id' => 61,
                        'parent_id' => null,
                        'name' => 'User Management',
                        'slug' => \Str::slug('User Management'),
                    ]);

                    Permission::create([
                        'id' => 62,
                        'parent_id' => null,
                        'name' => 'Doctor Management',
                        'slug' => \Str::slug('Doctor Management'),
                    ]);
                        Permission::create([
                            'id' => 63,
                            'parent_id' => 62,
                            'name' => 'Add',
                            'slug' => \Str::slug('Doctor Management')."-add",
                        ]);
                        Permission::create([
                            'id' => 64,
                            'parent_id' => 62,
                            'name' => 'View',
                            'slug' => \Str::slug('Doctor Management')."-view",
                        ]);
                        Permission::create([
                            'id' => 65,
                            'parent_id' => 62,
                            'name' => 'Update',
                            'slug' => \Str::slug('Doctor Management')."-update",
                        ]);  

                    Permission::create([
                        'id' => 66,
                        'parent_id' => null,
                        'name' => 'Ads Management',
                        'slug' => \Str::slug('Ads Management'),
                    ]);
                        Permission::create([
                            'id' => 67,
                            'parent_id' => 66,
                            'name' => 'Add',
                            'slug' => \Str::slug('Ads Management')."-add",
                        ]);
                        Permission::create([
                            'id' => 68,
                            'parent_id' => 66,
                            'name' => 'View',
                            'slug' => \Str::slug('Ads Management')."-view",
                        ]);
                        Permission::create([
                            'id' => 69,
                            'parent_id' => 66,
                            'name' => 'Update',
                            'slug' => \Str::slug('Ads Management')."-update",
                        ]);  

                    Permission::create([
                        'id' => 70,
                        'parent_id' => null,
                        'name' => 'Subscription',
                        'slug' => \Str::slug('Subscription'),
                    ]);
                        Permission::create([
                            'id' => 71,
                            'parent_id' => 70,
                            'name' => 'Add',
                            'slug' => \Str::slug('Subscription')."-add",
                        ]);
                        Permission::create([
                            'id' => 72,
                            'parent_id' => 70,
                            'name' => 'View',
                            'slug' => \Str::slug('Subscription')."-view",
                        ]);
                        Permission::create([
                            'id' => 73,
                            'parent_id' => 70,
                            'name' => 'Update',
                            'slug' => \Str::slug('Subscription')."-update",
                        ]);  


                    Permission::create([
                        'id' => 74,
                        'parent_id' => null,
                        'name' => 'Language Management',
                        'slug' => \Str::slug('Language Management'),
                    ]);
                        Permission::create([
                            'id' => 75,
                            'parent_id' => 74,
                            'name' => 'Add',
                            'slug' => \Str::slug('Language Management')."-add",
                        ]);
                        Permission::create([
                            'id' => 76,
                            'parent_id' => 74,
                            'name' => 'View',
                            'slug' => \Str::slug('Language Management')."-view",
                        ]);
                        Permission::create([
                            'id' => 77,
                            'parent_id' => 74,
                            'name' => 'Update',
                            'slug' => \Str::slug('Language Management')."-update",
                        ]);  

                        
                        Permission::create([
                            'id' => 78,
                            'parent_id' => null,
                            'name' => 'Widgets Management',
                            'slug' => \Str::slug('Widgets Management'),
                        ]);
                            Permission::create([
                                'id' => 79,
                                'parent_id' => 78,
                                'name' => 'Add',
                                'slug' => \Str::slug('Widgets Management')."-add",
                            ]);
                            Permission::create([
                                'id' => 80,
                                'parent_id' => 78,
                                'name' => 'View',
                                'slug' => \Str::slug('Widgets Management')."-view",
                            ]);
                            Permission::create([
                                'id' => 81,
                                'parent_id' => 78,
                                'name' => 'Update',
                                'slug' => \Str::slug('Widgets Management')."-update",
                            ]); 

                        Permission::create([
                            'id' => 82,
                            'parent_id' => null,
                            'name' => 'Speciality Management',
                            'slug' => \Str::slug('Speciality Management'),
                        ]);
                            Permission::create([
                                'id' => 83,
                                'parent_id' => 82,
                                'name' => 'Add',
                                'slug' => \Str::slug('Speciality Management')."-add",
                            ]);
                            Permission::create([
                                'id' => 84,
                                'parent_id' => 82,
                                'name' => 'View',
                                'slug' => \Str::slug('Speciality Management')."-view",
                            ]);
                            Permission::create([
                                'id' => 85,
                                'parent_id' => 82,
                                'name' => 'Update',
                                'slug' => \Str::slug('Speciality Management')."-update",
                            ]);  


                        Permission::create([
                            'id' => 86,
                            'parent_id' => null,
                            'name' => 'Services Management',
                            'slug' => \Str::slug('Services Management'),
                        ]);
                            Permission::create([
                                'id' => 87,
                                'parent_id' => 86,
                                'name' => 'Add',
                                'slug' => \Str::slug('Services Management')."-add",
                            ]);
                            Permission::create([
                                'id' => 88,
                                'parent_id' => 86,
                                'name' => 'View',
                                'slug' => \Str::slug('Services Management')."-view",
                            ]);
                            Permission::create([
                                'id' => 89,
                                'parent_id' => 86,
                                'name' => 'Update',
                                'slug' => \Str::slug('Services Management')."-update",
                            ]);  
                        
                        Permission::create([
                            'id' => 90,
                            'parent_id' => null,
                            'name' => 'Cities Management',
                            'slug' => \Str::slug('Cities Management'),
                        ]);
                            Permission::create([
                                'id' => 91,
                                'parent_id' => 90,
                                'name' => 'Add',
                                'slug' => \Str::slug('Cities Management')."-add",
                            ]);
                            Permission::create([
                                'id' => 92,
                                'parent_id' => 90,
                                'name' => 'View',
                                'slug' => \Str::slug('Cities Management')."-view",
                            ]);
                            Permission::create([
                                'id' => 93,
                                'parent_id' => 90,
                                'name' => 'Update',
                                'slug' => \Str::slug('Cities Management')."-update",
                            ]);  

                        Permission::create([
                            'id' => 94,
                            'parent_id' => null,
                            'name' => 'Questionaire Form',
                            'slug' => \Str::slug('Questionaire Form'),
                        ]);
                            Permission::create([
                                'id' => 95,
                                'parent_id' => 94,
                                'name' => 'Add',
                                'slug' => \Str::slug('Questionaire Form')."-add",
                            ]);
                            Permission::create([
                                'id' => 96,
                                'parent_id' => 94,
                                'name' => 'View',
                                'slug' => \Str::slug('Questionaire Form')."-view",
                            ]);
                            Permission::create([
                                'id' => 97,
                                'parent_id' => 94,
                                'name' => 'Update',
                                'slug' => \Str::slug('Questionaire Form')."-update",
                            ]);  

                        Permission::create([
                            'id' => 98,
                            'parent_id' => null,
                            'name' => 'Info Modal Management',
                            'slug' => \Str::slug('Info Modal Management'),
                        ]);
                        Permission::create([
                            'id' => 99,
                            'parent_id' => null,
                            'name' => 'General Settings',
                            'slug' => \Str::slug('General Settings'),
                        ]);
                            Permission::create([
                                'id' => 100,
                                'parent_id' => null,
                                'name' => 'Reporting',
                                'slug' => \Str::slug('Reporting'),
                            ]);
                            Permission::create([
                                'id' => 101,
                                'parent_id' => 100,
                                'name' => 'Sehat Scan Report',
                                'slug' => \Str::slug('Reporting Sehat Scan Report')."",
                            ]);  

                    Permission::create([
                        'id' => 102,
                        'parent_id' => null,
                        'name' => 'Newsletter subscribers',
                        'slug' => \Str::slug('Newsletter subscribers'),
                    ]);



        // Permission::create([
        //     'name' => 'Cities Management',
        //     'slug' => \Str::slug('Cities Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Specialities Management',
        //     'slug' => \Str::slug('Specialities Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Services Management',
        //     'slug' => \Str::slug('Services Management'),
        // ]);
        // Permission::create([
        //     'name' => 'FAQ Management',
        //     'slug' => \Str::slug('FAQ Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Subscription Management',
        //     'slug' => \Str::slug('Subscription Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Widgets Management',
        //     'slug' => \Str::slug('widgets-management'),
        // ]);
        // Permission::create([
        //     'name' => 'Topics Management',
        //     'slug' => \Str::slug('Topics Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Articles Management',
        //     'slug' => \Str::slug('Articles Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Pages Management',
        //     'slug' => \Str::slug('Pages Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Diseases Management',
        //     'slug' => \Str::slug('Diseases Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Tags Management',
        //     'slug' => \Str::slug('Tags Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Language Management',
        //     'slug' => \Str::slug('Language Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Info Modal Management',
        //     'slug' => \Str::slug('Info Modal Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Settings',
        //     'slug' => \Str::slug('Settings'),
        // ]);
        // Permission::create([
        //     'name' => 'Rights Management',
        //     'slug' => \Str::slug('rights-management'),
        // ]);
        // Permission::create([
        //     'name' => 'Administrator Management',
        //     'slug' => \Str::slug('Administrator Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Footer Management',
        //     'slug' => \Str::slug('Footer Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Health Scan Management',
        //     'slug' => \Str::slug('Health Scan Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Doctor Management',
        //     'slug' => \Str::slug('Doctor Management'),
        // ]);
        // Permission::create([
        //     'name' => 'User Management',
        //     'slug' => \Str::slug('User Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Questionaire Form',
        //     'slug' => \Str::slug('Questionaire Form'),
        // ]);
        // Permission::create([
        //     'name' => 'Ads Management',
        //     'slug' => \Str::slug('Ads Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Finance',
        //     'slug' => \Str::slug('Finance'),
        // ]);
        // Permission::create([
        //     'name' => 'Booking Management',
        //     'slug' => \Str::slug('Booking Management'),
        // ]);
        // Permission::create([
        //     'name' => 'Reporting',
        //     'slug' => \Str::slug('Reporting'),
        // ]);
        // Permission::create([
        //     'name' => 'Drug Management',
        //     'slug' => \Str::slug('Drug Management'),
        // ]);
    }
}
