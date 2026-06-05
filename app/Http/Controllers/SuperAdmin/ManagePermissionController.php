<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class ManagePermissionController extends Controller
{


    public function index()
    {

        $permissions = Permission::orderBy('name')->get();
        return view('manage.permissions.index', compact('permissions'));
    }

    public function create()
    {
        abort_if(Auth::user()->cannot('manage.permissions.create'), 403);
        return view('manage.permissions.create');
    }

    public function store(Request $request)
    {
        abort_if(Auth::user()->cannot('manage.permissions.store'), 403);
        $request->validate([
            'name' => 'required|string|unique:permissions,name'
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);
        // Reset cache Spatie setelah tambah permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        activity()->causedBy(auth()->user())
            ->log("Membuat permission baru: {$permission->name}");

        return redirect()->route('manage.permissions.index')
            ->with('success', "Permission berhasil dibuat.");
    }

    public function destroy(Permission $permission)
    {
        abort_if(Auth::user()->cannot('manage.permissions.destroy'), 403);
        activity()->causedBy(auth()->user())
            ->log("Menghapus permission: {$permission->name}");

        $permission->delete();

        return redirect()->route('manage.permissions.index')
            ->with('success', "Permission berhasil dihapus.");
    }
}
