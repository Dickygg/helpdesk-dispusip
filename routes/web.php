<?php

use App\Http\Controllers\admin\AssigmentController;
use App\Http\Controllers\admin\TicketAdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\PetugasDashboardController;
use App\Http\Controllers\Dashboard\PenggunaDashboardController;
use App\Http\Controllers\Dashboard\SuperAdminDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PiorityController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\pengguna\TicketController;
use App\Http\Controllers\SuperAdmin\ManagePermissionController;
use App\Http\Controllers\SuperAdmin\ManageRoleController;
use App\Http\Controllers\SuperAdmin\ManageUserRoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// -------------------------------------------------------
// Auth
// -------------------------------------------------------
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');



// -------------------------------------------------------
// Super Admin
// -------------------------------------------------------
Route::middleware(['auth', 'role:super admin'])->group(function () {
    Route::get('/dashboard/super-admin', [AdminDashboardController::class, 'index'])->name('dashboard.super-admin');

    // Data Master
    Route::resource('roles', RolesController::class)->names('roles');
    Route::resource('application', ApplicationController::class)->names('application');
    Route::resource('priority', PiorityController::class)->names('piority');
    Route::resource('status', StatusController::class)->names('status');

    // Manajemen Akses
    Route::resource('manage/roles', ManageRoleController::class)
        ->names('manage.roles')
        ->except(['show']);
    Route::resource('manage/permissions', ManagePermissionController::class)
        ->names('manage.permissions')
        ->only(['index', 'create', 'store', 'destroy']);
    Route::resource('manage/user-roles', ManageUserRoleController::class)
        ->names('manage.user-roles')
        ->only(['index', 'edit', 'update'])
        ->parameters(['user-roles' => 'user']);

    //Route Assigment super-admin
    Route::get('super-admin/admin/assigment/', [AssigmentController::class, 'index'])->name('sa.admin.assigment.index');


    // Tiket Admin — URL super-admin/admin/tiket
    Route::get('super-admin/tiket/historyTiket', [TicketAdminController::class, 'historyTiket'])->name('sa.admin.tiket.historyTiket');
    Route::resource('super-admin/admin/tiket', TicketAdminController::class)->names('sa.admin.tiket');
    Route::get('super-admin/admin/tiket/proses/{tiket}', [TicketAdminController::class, 'SiteprosesTiket'])->name('sa.admin.tiket.proses');
    Route::put('super-admin/admin/tiket/rejected/{tiket}', [TicketAdminController::class, 'rejectVerificationAdmin'])->name('sa.admin.tiket.rejected');
    Route::put('super-admin/admin/tiket/verification/{tiket}', [TicketAdminController::class, 'VerificationAdmin'])->name('sa.admin.tiket.verification');
    Route::post('super-admin/admin/tiket/assignment/{tiket}', [AssigmentController::class, 'assignment'])->name('sa.admin.tiket.assignment');
    Route::put('super-admin/admin/tiket/updatePiority/{tiket}', [TicketAdminController::class, 'updatePiorityTiket'])->name('sa.admin.tiket.updatePiority');
    Route::get('super-admin/admin/tiket/closeTiket/{tiket}', [TicketAdminController::class, 'closeTiket'])->name('sa.admin.tiket.closeTiket');



    // Tiket Pengguna — URL super-admin/tiket
    Route::get('super-admin/tiket', [TicketController::class, 'index'])->name('sa.tiket.index');
    Route::get('super-admin/tiket/create', [TicketController::class, 'create'])->name('sa.tiket.create');
    Route::post('super-admin/tiket', [TicketController::class, 'store'])->name('sa.tiket.store');
    Route::get('super-admin/tiket/{tiket}', [TicketController::class, 'show'])->name('sa.tiket.show');
});


// -------------------------------------------------------
// Admin Helpdesk
// -------------------------------------------------------
Route::middleware(['auth', 'role:admin helpdesk'])->group(function () {
    Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])->name('dashboard.admin');

    // Tiket Admin
    Route::get('admin/tiket/historyTiket', [TicketAdminController::class, 'historyTiket'])->name('admin.tiket.historyTiket');
    Route::resource('admin/tiket', TicketAdminController::class)->names('admin.tiket');
    Route::get('admin/tiket/proses/{tiket}', [TicketAdminController::class, 'SiteprosesTiket'])->name('admin.tiket.proses');
    Route::put('admin/tiket/rejected/{tiket}', [TicketAdminController::class, 'rejectVerificationAdmin'])->name('admin.tiket.rejected');
    Route::put('admin/tiket/verification/{tiket}', [TicketAdminController::class, 'VerificationAdmin'])->name('admin.tiket.verification');
    Route::get('admin/tiket/create', [TicketController::class, 'create'])->name('admin.tiket.create');
    Route::put('admin/tiket/updatePiority/{tiket}', [TicketAdminController::class, 'updatePiorityTiket'])->name('admin.tiket.updatePiority');
    Route::get('admin/tiket/closeTiket/{tiket}', [TicketAdminController::class, 'closeTiket'])->name('admin.tiket.closeTiket');

    //assigment tiket
    Route::post('admin/tiket/assignment/{tiket}', [AssigmentController::class, 'assignment'])->name('admin.tiket.assignment');
});

// -------------------------------------------------------
// Petugas Teknis
// -------------------------------------------------------
Route::middleware(['auth', 'role:petugas teknis'])->group(function () {
    Route::get('/dashboard/petugas', [PetugasDashboardController::class, 'index'])->name('dashboard.petugas');

    // Route::get('/tiket', [TicketController::class, 'index'])->name('tiket.index');
    // Route::get('/tiket/{tiket}', [TicketController::class, 'show'])->name('tiket.show');
});

// -------------------------------------------------------
// Pengguna
// -------------------------------------------------------
Route::middleware(['auth', 'role:pengguna'])->group(function () {
    Route::get('/dashboard/pengguna', [PenggunaDashboardController::class, 'index'])->name('dashboard.pengguna');

    Route::get('/tiket', [TicketController::class, 'index'])->name('tiket.index');
    Route::get('/tiket/create', [TicketController::class, 'create'])->name('tiket.create');
    Route::post('/tiket', [TicketController::class, 'store'])->name('tiket.store');
    Route::get('/tiket/{tiket}', [TicketController::class, 'show'])->name('tiket.show');
});

require __DIR__ . '/auth.php';
