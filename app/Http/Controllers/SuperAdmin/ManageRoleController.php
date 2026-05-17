<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ManageRoleController extends Controller
{


    public function index()
    {

        $roles = Role::with('permissions')->get();
        return view('manage.roles.index', compact('roles'));
    }

    public function create()
    {
        abort_if(Auth::user()->cannot('manage.roles.create'), 403);
        $permissions = Permission::orderBy('name')->get();
        return view('manage.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        abort_if(Auth::user()->cannot('manage.roles.store'), 403);
        $request->validate([
            'name' => 'required|string|unique:roles,name'
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        activity()->causedBy(auth()->user())
            ->log("Membuat role baru: {$role->name}");

        return redirect()->route('manage.roles.index')
            ->with('success', "Role '{$role->name}' berhasil dibuat.");
    }

    public function edit(Role $role)
    {
        abort_if(Auth::user()->cannot('manage.roles.edit'), 403);
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('manage.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        abort_if(Auth::user()->cannot('manage.roles.update'), 403);
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
        ]);

        $oldName = $role->name;
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        activity()->causedBy(auth()->user())
            ->log("Update role: {$oldName} → {$role->name}");

        return redirect()->route('manage.roles.index')
            ->with('success', "Role berhasil diperbarui.");
    }

    public function destroy(Role $role)
    {
        abort_if(Auth::user()->cannot('manage.roles.create'), 403);
        $protected = ['Super Admin', 'Admin Helpdesk', 'Petugas Teknis', 'Pengguna'];

        if (in_array($role->name, $protected)) {
            return redirect()->route('manage.roles.index')
                ->with('error', "Role default tidak bisa dihapus.");
        }

        activity()->causedBy(auth()->user())
            ->log("Menghapus role: {$role->name}");

        $role->delete();

        return redirect()->route('manage.roles.index')
            ->with('success', "Role berhasil dihapus.");
    }
}
