<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = Role::query()->firstOrCreate([
            'name' => 'Owner',
            'guard_name' => 'api'
        ]);
        $owner->syncPermissions(Permission::all());


        $admin = Role::query()->firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'api'
        ]);
        $admin->syncPermissions([
            'read-users',
            'store-users',
            'update-users',
            'delete-users',
            'read-categories',
            'store-categories',
            'update-categories',
            'delete-categories',
            'restore-categories',
            'read-items',
            'store-items',
            'update-items',
            'delete-items',
            'restore-items',
            'read-orders',
            'read-order-items',
            'read-payments',
            'access-dashboard',
        ]);

        $barista = Role::query()->firstOrCreate([
            'name' => 'Barista',
            'guard_name' => 'api'
        ]);
        $barista->syncPermissions([
            'read-items',
            'read-categories',
            'read-orders',
            'change-order-status',
            'read-order-items',
            'update-orders',
            'access-dashboard',
        ]);

        $accountant = Role::query()->firstOrCreate([
            'name' => 'Accountant',
            'guard_name' => 'api'
        ]);
        $accountant->syncPermissions([
            'read-items',
            'read-categories',
            'read-orders',
            'read-order-items',
            'update-orders',
            'access-dashboard',
        ]);

        $customer = Role::query()->firstOrCreate([
            'name' => 'Customer',
            'guard_name' => 'api'
        ]);
        $customer->syncPermissions([
            'read-items',
            'read-categories',
            'store-orders',
        ]);
    }
}
