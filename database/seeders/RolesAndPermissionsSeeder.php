<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Create Permissions
        $permissions = [
            'user'      => ['create', 'view', 'edit', 'delete'],
            'role'      => ['create', 'view', 'edit', 'delete'],
            'parcel'    => ['create', 'view', 'edit', 'delete'],
            'order'     => ['create', 'view', 'edit', 'delete'],
            'tracking'  => ['create', 'view', 'edit', 'delete'],
            'profile'   => ['create', 'view', 'edit', 'delete'],
            'setting'   => ['create', 'view', 'edit', 'delete'],
            'address'   => ['create', 'view', 'edit', 'delete'],
            'balance'   => ['create', 'view', 'edit', 'delete'],
            'invoice'   => ['create', 'view', 'edit', 'delete'],
            'rate'      => ['create', 'view', 'edit', 'delete'],
            'product'   => ['create', 'view', 'edit', 'delete'],
            'transaction'   => ['create', 'view', 'edit', 'delete'],
            'storage_invoice'   => ['create', 'view', 'edit', 'delete'],

        ];

        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {
                $permissionName = "{$module}.{$action}";
                Permission::updateOrCreate(['name' => $permissionName]);
                $adminRole->givePermissionTo($permissionName);
                // Assign the "profile" and "parcel" permissions to the "user" role
                if ($module === 'profile' || $module === 'product') {
                    $userRole->givePermissionTo($permissionName);
                }
            }
        }
    }
}
