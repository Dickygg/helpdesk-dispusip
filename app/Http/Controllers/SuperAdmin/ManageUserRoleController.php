<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ManageUserRoleController extends Controller
{

    public function index()
    {
        $users = User::with('roles')->orderBy('name')->get();
        return view('manage.user-roles.index', compact('users'));
    }

    public function edit(User $user)
    {
        abort_if(Auth::user()->cannot('manage.user-roles.edit'), 403);
        $roles = Role::orderBy('name')->get();
        $userRoles = $user->roles->pluck('name')->toArray();
        return view('manage.user-roles.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        abort_if(Auth::user()->cannot('manage.user-roles.update'), 403);
        $request->validate([
            'roles'   => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $oldRoles = $user->getRoleNames()->implode(', ');
        $user->syncRoles($request->roles);
        $newRoles = $user->getRoleNames()->implode(', ');

        activity()->causedBy(auth()->user())
            ->performedOn($user)
            ->log("Update role user {$user->name}: [{$oldRoles}] → [{$newRoles}]");

        return redirect()->route('manage.user-roles.index')
            ->with('success', "Role '{$user->name}' berhasil diperbarui.");
    }
}
