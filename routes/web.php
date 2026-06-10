<?php

use App\Http\Controllers\admin\AssigmentController;
use App\Http\Controllers\admin\TicketAdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\PetugasDashboardController;
use App\Http\Controllers\Dashboard\PenggunaDashboardController;
use App\Http\Controllers\Dashboard\SuperAdminDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\PiorityController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\pengguna\TicketController;
use App\Http\Controllers\petugas\HandlingTiketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\ManagePermissionController;
use App\Http\Controllers\SuperAdmin\ManageRoleController;
use App\Http\Controllers\SuperAdmin\ManageUserRoleController;
use App\Http\Controllers\TicketTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// -------------------------------------------------------
// Profile
// -------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');

    Route::put('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])
        ->name('profile.update-password');
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
    Route::get('/dashboard/super-admin', [AdminDashboardController::class, 'index'])->name('sa.admin.dashboard');

    Route::get('ticket-type/export', [TicketTypeController::class, 'export'])->name('ticket-type.export');
    Route::get('priority/export', [PiorityController::class, 'export'])->name('priority.export');
    Route::get('application/export', [ApplicationController::class, 'export'])->name('application.export');

    // Data Master
    Route::resource('roles', RolesController::class)->names('roles');
    Route::resource('application', ApplicationController::class)->names('application');
    Route::resource('priority', PiorityController::class)->names('piority');
    Route::resource('status', StatusController::class)->names('status');
    Route::resource('ticket-type', TicketTypeController::class)->names('ticket-type');
    Route::resource('users', ManageUserController::class)->names('users');

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
    Route::get('super-admin/admin/assigment/history', [AssigmentController::class, 'historyAssignment'])->name('sa.admin.assigment.history');
    Route::get('super-admin/admin/assignment/export', [AssigmentController::class, 'export'])
        ->name('sa.admin.assignment.export');
    Route::get('super-admin/admin/assignment/exporthistory', [AssigmentController::class, 'exporthistory'])
        ->name('sa.admin.assignment.exporthistory');
    Route::get('super-admin/assignment/show/{assignment}', [AssigmentController::class, 'show'])->name('sa.admin.assignment.show');



    // Tiket Admin — URL super-admin/admin/tiket
    Route::get('super-admin/tiket/historyTiket', [TicketAdminController::class, 'historyTiket'])->name('sa.admin.tiket.historyTiket');
    Route::get('super-admin/admin/tiket/export', [TicketAdminController::class, 'export'])
        ->name('sa.admin.tiket.export');
    Route::get('super-admin/admin/tiket/exporthistory', [TicketAdminController::class, 'exporthistory'])
        ->name('sa.admin.tiket.exporthistory');
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
    Route::get('super-admin/tiket/history', [TicketController::class, 'historyTicket'])->name('sa.tiket.history');
    Route::get('super-admin/tiket/{tiket}', [TicketController::class, 'show'])->name('sa.tiket.show');
    Route::post('super-admin/tiket/konfirmasi/{tiket}', [TicketController::class, 'konfirmasi'])->name('sa.tiket.konfirmasi');
    Route::post('super-admin/tiket/rejectedKonfirmasi/{tiket}', [TicketController::class, 'rejectedKonfirmasi'])->name('sa.tiket.rejectedKonfirmasi');
    Route::get('super-admin/tiket/canceltiket/{tiket}', [TicketController::class, 'cancelTicket'])->name('sa.tiket.cancelTicket');
});


// -------------------------------------------------------
// Admin Helpdesk
// -------------------------------------------------------
Route::middleware(['auth', 'role:admin helpdesk'])->group(function () {
    Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('admin/tiket/data', [TicketController::class, 'index'])->name('admin.tiket.data');
    Route::post('admin/tiket', [TicketController::class, 'store'])->name('admin.tiket.store');
    Route::get('admin/tiket/history', [TicketController::class, 'historyTicket'])->name('admin.tiket.history');
    Route::get('admin/tiket/canceltiket/{tiket}', [TicketController::class, 'cancelTicket'])->name('admin.tiket.cancelTicket');
    Route::get('admin/tiket/create', [TicketController::class, 'create'])->name('admin.tiket.create');
    Route::post('admin/tiket/konfirmasi/{tiket}', [TicketController::class, 'konfirmasi'])->name('admin.tiket.konfirmasi');
    Route::post('admin/tiket/rejectedKonfirmasi/{tiket}', [TicketController::class, 'rejectedKonfirmasi'])->name('admin.tiket.rejectedKonfirmasi');
    Route::get('admin/tiket/canceltiket/{tiket}', [TicketController::class, 'cancelTicket'])->name('admin.tiket.cancelTicket');

    // Tiket Admin
    Route::get('admin/tiket/historyTiket', [TicketAdminController::class, 'historyTiket'])->name('admin.tiket.historyTiket');
    Route::get('admin/tiket/export', [TicketAdminController::class, 'export'])
        ->name('admin.tiket.export');
    Route::get('admin/tiket/exporthistory', [TicketAdminController::class, 'exporthistory'])
        ->name('admin.tiket.exporthistory');
    Route::resource('admin/tiket', TicketAdminController::class)->names('admin.tiket')->except(['create', 'store']);
    Route::get('admin/tiket/proses/{tiket}', [TicketAdminController::class, 'SiteprosesTiket'])->name('admin.tiket.proses');
    Route::put('admin/tiket/rejected/{tiket}', [TicketAdminController::class, 'rejectVerificationAdmin'])->name('admin.tiket.rejected');
    Route::put('admin/tiket/verification/{tiket}', [TicketAdminController::class, 'VerificationAdmin'])->name('admin.tiket.verification');
    Route::post('admin/tiket/assignment/{tiket}', [AssigmentController::class, 'assignment'])->name('admin.tiket.assignment');
    Route::put('admin/tiket/updatePiority/{tiket}', [TicketAdminController::class, 'updatePiorityTiket'])->name('admin.tiket.updatePiority');
    Route::get('admin/tiket/closeTiket/{tiket}', [TicketAdminController::class, 'closeTiket'])->name('admin.tiket.closeTiket');

    Route::get('admin/tiket/{tiket}', [TicketController::class, 'show'])->name('admin.tiket.show');



    //Route Assigment admin
    Route::get('admin/admin/assigment/', [AssigmentController::class, 'index'])->name('admin.assigment.index');
    Route::get('admin/assigment/history', [AssigmentController::class, 'historyAssignment'])->name('admin.assigment.history');
    Route::get('admin/assignment/export', [AssigmentController::class, 'export'])
        ->name('admin.assignment.export');
    Route::get('admin/assignment/exporthistory', [AssigmentController::class, 'exporthistory'])
        ->name('admin.assignment.exporthistory');
    Route::get('assignment/show/{assignment}', [AssigmentController::class, 'show'])->name('admin.assignment.show');
});

// -------------------------------------------------------
// Petugas Teknis
// -------------------------------------------------------
Route::middleware(['auth', 'role:petugas teknis'])->group(function () {
    Route::get('/dashboard/petugas', [PetugasDashboardController::class, 'index'])->name('dashboard.petugas');

    // handling petugas route
    Route::get('/assignment/petugas/', [HandlingTiketController::class, 'index'])->name('assignment.petugas.index');
    Route::get('/assignment/petugas/export', [HandlingTiketController::class, 'export'])->name('assignment.petugas.export');
    Route::get('/assignment/petugas/exporthistory', [HandlingTiketController::class, 'exporthistory'])->name('assignment.petugas.exporthistory');
    Route::get('/assignment/petugas/show/{assignment}', [HandlingTiketController::class, 'show'])->name('assignment.petugas.show');
    Route::get('/assignment/petugas/prosesAssignment/{assignment}', [HandlingTiketController::class, 'prosesAssignment'])->name('assignment.petugas.prosesAssignment');
    Route::get('/assignment/petugas/histroyAssignment', [HandlingTiketController::class, 'historyAssignment'])->name('assignment.petugas.history');
    Route::post('/assignment/petugas/startWork/{assignment}', [HandlingTiketController::class, 'startWork'])->name('assignment.petugas.startWork');
    Route::post('/assignment/petugas/finishWork/{assignment}', [HandlingTiketController::class, 'finishWork'])->name('assignment.petugas.finishtWork');
});

// -------------------------------------------------------
// Pengguna
// -------------------------------------------------------
Route::middleware(['auth', 'role:pengguna'])->group(function () {
    Route::get('/dashboard/pengguna', [PenggunaDashboardController::class, 'index'])->name('dashboard.pengguna');

    Route::get('/tiket', [TicketController::class, 'index'])->name('tiket.index');
    Route::get('/tiket/create', [TicketController::class, 'create'])->name('tiket.create');
    Route::get('/tiket/history', [TicketController::class, 'historyTicket'])->name('tiket.history');
    Route::get('/tiket/{tiket}', [TicketController::class, 'show'])->name('tiket.show');
    Route::post('/tiket', [TicketController::class, 'store'])->name('tiket.store');
    Route::post('/tiket/konfirmasi/{tiket}', [TicketController::class, 'konfirmasi'])->name('tiket.konfirmasi');
    Route::post('/tiket/rejectedKonfirmasi/{tiket}', [TicketController::class, 'rejectedKonfirmasi'])->name('tiket.rejectedKonfirmasi');
    Route::get('/tiket/canceltiket/{tiket}', [TicketController::class, 'cancelTicket'])->name('tiket.cancelTicket');
});

require __DIR__ . '/auth.php';
