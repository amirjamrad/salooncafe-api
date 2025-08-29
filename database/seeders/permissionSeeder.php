<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class permissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $permissions = [

            // Users
            'read-users',
            'store-users',
            'update-users',
            'delete-users',
            'restore-users',

            // Roles
            'manage-roles',


            // Permissions
            'manage-permissions',

            // Categories
            'read-categories',
            'store-categories',
            'update-categories',
            'delete-categories',
            'restore-categories',

            // Items
            'read-items',
            'store-items',
            'update-items',
            'delete-items',
            'restore-items',

            // Orders
            'read-orders',
            'store-orders',
            'update-orders',
            'delete-orders',
            'change-order-status',

            // Order Items
            'read-order-items',
            'store-order-items',
            'update-order-items',
            'delete-order-items',

            // Payments
            'read-payments',
            'store-payments',
            'update-payments',
            'delete-payments',

            // Payment Logs (only-read)
            'read-payment-logs',

            //dashboard access
            'access-dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api'
            ]);
        }

    }
}
