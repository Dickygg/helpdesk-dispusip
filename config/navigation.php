<?php
// navigation menu bar
return [
    'Super Admin' => [
        [
            'heading' => 'Dashboard',
        ],
        [
            'title' => 'Dashboard',
            'route' => 'sa.admin.dashboard',
            'icon' => 'fas fa-fw fa-home'
        ],
        [
            'title' => 'Divider',
            'divider' => true,
        ],
        [
            'heading' => 'Manajemen Akses'
        ],
        [
            'title' => 'Roles',
            'route' => 'manage.roles.index',
            'icon'  => 'fas fa-user-shield'
        ],
        [
            'title' => 'Permissions',
            'route' => 'manage.permissions.index',
            'icon'  => 'fas fa-key'
        ],
        [
            'title' => 'Assign Role User',
            'route' => 'manage.user-roles.index',
            'icon'  => 'fas fa-users-cog'
        ],
        [
            'title' => 'Divider',
            'divider' => true,
        ],
        [
            'heading' => 'Data Master'
        ],
        [
            'title' => 'Data Daftar Aplikasi',
            'route' => 'application.index',
            'icon' => 'bi bi-menu-app'
        ],
        [
            'title' => 'Data Daftar Prioritas',
            'route' => 'piority.index',
            'icon' => 'bi bi-exclamation-square'
        ],
        [
            'title' => 'Data Daftar Tipe Tiket',
            'route' => 'ticket-type.index',
            'icon' => 'bi bi-collection'
        ],
        [
            'title' => 'Divider',
            'divider' => true,
        ],
        [
            'heading' => 'Data Tiket'
        ],
        [
            'title' => 'Buat Tiket',
            'route' => 'sa.tiket.create',  // ← diubah
            'icon' => 'bi-ticket-detailed'
        ],
        [
            'title' => 'Tiket Saya',
            'route' => 'sa.tiket.index',   // ← diubah
            'icon' => 'bi bi-list-task'
        ],
        [
            'title' => 'Data Riwayat Tiket Saya',
            'route' => 'sa.tiket.history',
            'icon' => 'bi bi-clock'
        ],
        [
            'title' => 'Divider',
            'divider' => true,
        ],
        [
            'heading' => 'Data Tiket (Admin)'
        ],
        [
            'title' => 'Daftar Tiket Berjalan',
            'route' => 'sa.admin.tiket.index',  // ← diubah
            'icon' => 'bi bi-list-task'
        ],
        [
            'title' => 'Daftar Riwayat Tiket',
            'route' => 'sa.admin.tiket.historyTiket',
            'icon' => 'bi bi-clock'
        ],
        [
            'title' => 'Divider',
            'divider' => true,
        ],
        [
            'heading' => 'Data Assigment (Admin)'
        ],
        [
            'title' => 'Daftar Assigment',
            'route' => 'sa.admin.assigment.index',
            'icon' => 'bi-ticket-detailed'
        ],
        [
            'title' => 'Daftar Riwayat Assigment',
            'route' => 'sa.admin.assigment.history',
            'icon' => 'bi-ticket-detailed'
        ],
        [
            'title' => 'Divider',
            'divider' => true,
        ],
        [
            'heading' => 'Setting'
        ],
        [
            'title' => 'Profile Saya',
            'route' => 'profile.index',
            'icon' => 'bi bi-person-badge'
        ],

    ],

    'Admin Helpdesk' => [
        [
            'heading' => 'Dashboard',
        ],
        [
            'title' => 'Dashboard',
            'route' => 'admin.dashboard',  // ← diubah, sebelumnya salah pakai dashboard.super-admin
            'icon' => 'fas fa-fw fa-home'
        ],
        [
            'title' => 'Divider',
            'divider' => true,
        ],
        [
            'heading' => 'Data Tiket (Admin)'
        ],
        [
            'title' => 'Buat Tiket',
            'route' => 'admin.tiket.create',
            'icon' => 'bi-ticket-detailed'
        ],
        [
            'title' => 'Data Riwayat Tiket Saya',
            'route' => 'sa.tiket.history',
            'icon' => 'bi bi-clock'
        ],
        [
            'title' => 'Daftar Tiket Berjalan',
            'route' => 'admin.tiket.index',
            'icon' => 'bi bi-list-task'
        ],
        [
            'title' => 'Daftar Riwayat Tiket',
            'route' => 'admin.tiket.historyTiket',
            'icon' => 'bi bi-clock'
        ],
        [
            'title' => 'Divider',
            'divider' => true,
        ],
        [
            'heading' => 'Data Assigment (Admin)'
        ],
        [
            'title' => 'Daftar Assignment',
            'route' => 'admin.assigment.index',
            'icon' => 'bi-ticket-detailed'
        ],
        [
            'title' => 'Daftar Riwayat Assigment',
            'route' => 'sa.admin.assigment.history',
            'icon' => 'bi-ticket-detailed'
        ],
        [
            'title' => 'Divider',
            'divider' => true,
        ],
        [
            'heading' => 'Setting'
        ],
        [
            'title' => 'Profile Saya',
            'route' => 'profile.index',
            'icon' => 'bi bi-person-badge'
        ],

    ],

    'Petugas Teknis' => [
        [
            'heading' => 'Dashboard',
        ],
        [
            'title' => 'Dashboard',
            'route' => 'dashboard.petugas',  // ← diubah, sebelumnya pakai testlayout
            'icon' => 'fas fa-fw fa-home'
        ],
        [
            'title' => 'Divider',
            'divider' => true,
        ],
        [
            'heading' => 'Data Tiket'
        ],
        [
            'title' => 'Data Assigment Masuk',
            'route' => 'assignment.petugas.index',
            'icon' => 'bi bi-send'
        ],
        [
            'title' => 'Data Riwayat Assigment',
            'route' => 'assignment.petugas.history',
            'icon' => 'bi bi-clock'
        ],
        [
            'title' => 'Divider',
            'divider' => true,
        ],
        [
            'heading' => 'Setting'
        ],
        [
            'title' => 'Profile Saya',
            'route' => 'profile.index',
            'icon' => 'bi bi-person-badge'
        ],
    ],

    'Pengguna' => [
        [
            'heading' => 'Dashboard',
        ],
        [
            'title' => 'Dashboard',
            'route' => 'dashboard.pengguna',  // ← diubah, sebelumnya pakai dashboard.petugas
            'icon' => 'fas fa-fw fa-home'
        ],
        [
            'title' => 'Divider',
            'divider' => true,
        ],
        [
            'heading' => 'Data Tiket'
        ],
        [
            'title' => 'Buat Tiket',
            'route' => 'tiket.create',
            'icon' => 'bi-ticket-detailed'
        ],
        [
            'title' => 'Tiket Saya',
            'route' => 'tiket.index',
            'icon' => 'bi bi-list-task'
        ],
        [
            'title' => 'Data Riwayat Tiket',
            'route' => 'tiket.history',
            'icon' => 'bi bi-clock'
        ],
        [
            'title' => 'Divider',
            'divider' => true,
        ],
        [
            'heading' => 'Setting'
        ],
        [
            'title' => 'Profile Saya',
            'route' => 'profile.index',
            'icon' => 'bi bi-person-badge'
        ],
    ],
];
