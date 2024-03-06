<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\CalendarYear;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Super Admin',
                'email' => 'admin@compliance.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Rakhee(management)',
                'email' => 'admin@management.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Mohan(business)',
                'email' => 'admin@businesshead.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Mark(cluster)',
                'email' => 'admin@clusterhead.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Rajesh(country)',
                'email' => 'admin@countryhead.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'David(compliance)',
                'email' => 'admin@compliancehead.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Miller(FM)',
                'email' => 'admin@compliancefinancemanager.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Suresh(PM)',
                'email' => 'admin@complianceprincipalmanager.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Harrish(FO)',
                'email' => 'admin@compliancefinanceofficer.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Lokesh(PO)',
                'email' => 'admin@complianceprincipleofficer.com',
                'password' => bcrypt('password'),
            ],
        ]);
        Role::insert([
            [
                'name' => 'Super Admin',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Business Head',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Cluster Head',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Country Head',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Compliance Head',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Compliance Finance Manager',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Compliance Principle Manager',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Compliance Finance Officer',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Compliance Principle Officer',
                'guard_name' => 'web',
            ],
        ]);
        Country::insert([
            [
                'name' => 'South Africa',
                'status' => '1',
                'created_by' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dubai',
                'status' => '1',
                'created_by' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kenya',
                'status' => '1',
                'created_by' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        CalendarYear::insert([
            [
                'name' => '2023',
                'country_id' => '["1","2","3"]',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '2024',
                'country_id' => '["1","2","3"]',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::statement("SET foreign_key_checks=0");
        Permission::truncate();
        DB::statement("SET foreign_key_checks=1");
        DB::statement("SET foreign_key_checks=0");
        Role::truncate();
        DB::statement("SET foreign_key_checks=1");

        $roleArray = [
            [
                'name' => 'Super Admin',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Business Head',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Cluster Head',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Country Head',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Compliance Head',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Compliance Finance Manager',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Compliance Principle Manager',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Compliance Finance Officer',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Compliance Principle Officer',
                'guard_name' => 'web',
            ],
        ];

        $permissionsArray = [
            [
                'name' => 'view User',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create User',
                'guard_name' => 'web',
            ],
            [
                'name' => 'update User',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete User',
                'guard_name' => 'web',
            ],
            [
                'name' => 'view Role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create Role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'update Role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete Role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'view Permission',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create Permission',
                'guard_name' => 'web',
            ],
            [
                'name' => 'update Permission',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete Permission',
                'guard_name' => 'web',
            ],
            [
                'name' => 'view Country',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create Country',
                'guard_name' => 'web',
            ],
            [
                'name' => 'update Country',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete Country',
                'guard_name' => 'web',
            ],
            [
                'name' => 'view Year',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create Year',
                'guard_name' => 'web',
            ],
            [
                'name' => 'update Year',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete Year',
                'guard_name' => 'web',
            ],
            [
                'name' => 'view Document',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create Compliance Event',
                'guard_name' => 'web',
            ],
            [
                'name' => 'view Compliance Event Summary',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit Compliance Event Summary',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete Compliance Event Summary',
                'guard_name' => 'web',
            ],
            [
                'name' => 'view Compliance Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'upload Compliance Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'change Status Compliance Management',
                'guard_name' => 'web',
            ],

        ];

        foreach ($permissionsArray as $permissionData) {
            Permission::firstOrCreate($permissionData);
        }
        $permissions = Permission::pluck('name')->toArray();

        foreach ($roleArray as $roleData) {
            $role = Role::create($roleData);
            if ($role->name == 'Super Admin') {
                $role->givePermissionTo($permissions);
            }
        }

        $user = User::find(1);
        $user->assignRole('Super Admin');
        $user = User::find(2);
        $user->assignRole('Management');
        $user = User::find(3);
        $user->assignRole('Business Head');
        $user = User::find(4);
        $user->assignRole('Cluster Head');
        $user = User::find(5);
        $user->assignRole('Country Head');
        $user = User::find(6);
        $user->assignRole('Compliance Head');
        $user = User::find(7);
        $user->assignRole('Compliance Finance Manager');
        $user = User::find(8);
        $user->assignRole('Compliance Principle Manager');
        $user = User::find(9);
        $user->assignRole('Compliance Finance Officer');
        $user = User::find(10);
        $user->assignRole('Compliance Principle Officer');
    }
}
