<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_projects','create_projects','edit_projects','delete_projects',
            'view_progress','create_progress','edit_progress','delete_progress',
            'view_billing','create_billing','edit_billing','delete_billing',
            'view_dsr','edit_dsr',
            'view_inventory','create_inventory','edit_inventory',
            'view_pv','edit_pv',
            'view_expenses','create_expenses','edit_expenses','delete_expenses',
            'manage_users','manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        $siteManagerRole = Role::firstOrCreate(['name' => 'site_manager', 'guard_name' => 'web']);
        $siteManagerRole->syncPermissions([
            'view_projects',
            'view_progress','create_progress','edit_progress',
            'view_billing','create_billing',
            'view_dsr',
            'view_inventory','create_inventory','edit_inventory',
            'view_pv','edit_pv',
            'view_expenses','create_expenses','edit_expenses',
        ]);

        $fieldEngineerRole = Role::firstOrCreate(['name' => 'field_engineer', 'guard_name' => 'web']);
        $fieldEngineerRole->syncPermissions([
            'view_projects',
            'view_progress','create_progress',
            'view_billing',
            'view_dsr',
            'view_inventory',
            'view_pv',
            'view_expenses','create_expenses',
        ]);
    }
}
