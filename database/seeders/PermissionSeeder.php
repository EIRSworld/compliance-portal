<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionsArray = [
            [
                'name' => 'view Compliance Email',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit Compliance Email',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete Compliance Email',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete Compliance Management',
                'guard_name' => 'web',
            ],
        ];

        foreach ($permissionsArray as $permissionData) {
            Permission::firstOrCreate($permissionData);
        }
    }
}
