<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // -------------------------------------------------------
        // DEFINE PERMISSIONS
        // Sesuaikan dengan route yang ada
        // -------------------------------------------------------

        $permissions = [
            // Data Master
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'view application',
            'create application',
            'edit application',
            'delete application',
            'view priority',
            'create priority',
            'edit priority',
            'delete priority',
            'view status',
            'create status',
            'edit status',
            'delete status',

            // Tiket - Admin
            'view admin tiket',
            'process admin tiket',
            'verify admin tiket',
            'reject admin tiket',
            'assign admin tiket',

            // Tiket - Pengguna
            'view tiket',
            'create tiket',
            'show tiket',

            // Dashboard
            'access dashboard super-admin',
            'access dashboard admin',
            'access dashboard petugas',
            'access dashboard pengguna',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // -------------------------------------------------------
        // DEFINE ROLES & ASSIGN PERMISSIONS
        // -------------------------------------------------------

        // Super Admin — akses semua
        $superAdmin = Role::firstOrCreate(['name' => 'super admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin Helpdesk — akses tiket admin + dashboard admin
        $adminHelpdesk = Role::firstOrCreate(['name' => 'admin helpdesk']);
        $adminHelpdesk->syncPermissions([
            'access dashboard admin',
            'view admin tiket',
            'process admin tiket',
            'verify admin tiket',
            'reject admin tiket',
            'assign admin tiket',
        ]);

        // Petugas Teknis — akses dashboard petugas saja
        $petugasTeknis = Role::firstOrCreate(['name' => 'petugas teknis']);
        $petugasTeknis->syncPermissions([
            'access dashboard petugas',
            'view tiket',
            'show tiket',
        ]);

        // Pengguna — akses tiket pengguna
        $pengguna = Role::firstOrCreate(['name' => 'pengguna']);
        $pengguna->syncPermissions([
            'access dashboard pengguna',
            'view tiket',
            'create tiket',
            'show tiket',
        ]);

        $this->command->info('Roles & Permissions selesai di-seed!');
    }
}
