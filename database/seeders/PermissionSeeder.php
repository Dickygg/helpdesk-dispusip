<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            'dashboard.super-admin',
            'dashboard.admin',
            'dashboard.petugas',
            'dashboard.pengguna',

            // Data Master (Super Admin)
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'application.view',
            'application.create',
            'application.edit',
            'application.delete',
            'priority.view',
            'priority.create',
            'priority.edit',
            'priority.delete',
            'status.view',
            'status.create',
            'status.edit',
            'status.delete',

            // Manajemen Akses (Super Admin)
            'manage.roles.view',
            'manage.roles.create',
            'manage.roles.edit',
            'manage.roles.delete',
            'manage.permissions.view',
            'manage.permissions.create',
            'manage.permissions.delete',
            'manage.user-roles.view',
            'manage.user-roles.edit',

            // Tiket Admin
            'tiket.admin.view',
            'tiket.admin.create',
            'tiket.admin.proses',
            'tiket.admin.verification',
            'tiket.admin.rejected',
            'tiket.admin.assignment',

            // Tiket Pengguna
            'tiket.view',
            'tiket.create',
            'tiket.show',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name'       => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
