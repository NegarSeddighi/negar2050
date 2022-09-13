<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manager = Role::updateOrCreate(['name' => 'manager'], ['name' => 'manager']);
        $admin = Role::updateOrCreate(['name' => 'admin'], ['name' => 'admin']);

        $indexPermission = Permission::updateOrCreate(['name' => 'index'], ['name' => 'index']);
        $storePermission = Permission::updateOrCreate(['name' => 'store'], ['name' => 'store']);
        $storeAdminPermission = Permission::updateOrCreate(['name' => 'store admin'], ['name' => 'store admin']);
        $showPermission = Permission::updateOrCreate(['name' => 'show'], ['name' => 'show']);
        $updatePermission = Permission::updateOrCreate(['name' => 'update'], ['name' => 'update']);
        $updateAdminPermission = Permission::updateOrCreate(['name' => 'update admin'], ['name' => 'update admin']);
        $destroyPermission = Permission::updateOrCreate(['name' => 'destroy'], ['name' => 'destroy']);
        $destroyAdminPermission = Permission::updateOrCreate(['name' => 'destroy admin'], ['name' => 'destroy admin']);

        $manager->givePermissionTo($indexPermission, $storePermission, $showPermission, $updatePermission, $destroyPermission, $storeAdminPermission, $updateAdminPermission, $destroyAdminPermission);
        $admin->givePermissionTo($indexPermission, $storePermission, $showPermission, $updatePermission, $destroyPermission);

    }
}
