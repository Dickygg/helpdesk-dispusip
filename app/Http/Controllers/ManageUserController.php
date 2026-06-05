<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class ManageUserController extends Controller
{
    public function index()
    {
        abort_if(Auth::user()->cannot('users.index'), 403);
        $users = User::with('roles')
            ->latest()
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        abort_if(Auth::user()->cannot('users.create'), 403);
        $roles = Role::pluck('name');

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        abort_if(Auth::user()->cannot('users.store'), 403);
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'nrk'      => ['max:255', 'unique:users,nrk'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'role'     => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name'     => $request->name,
                'username' => $request->username,
                'nrk'      => $request->nrk,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($request->role);

            DB::commit();

            return redirect()
                ->route('manage.user-roles.index')
                ->with('success', 'Pengguna berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        abort_if(Auth::user()->cannot('users.edit'), 403);
        $roles = Role::pluck('name');

        return view('users.edit', compact(
            'user',
            'roles'
        ));
    }

    public function update(Request $request, User $user)
    {
        abort_if(Auth::user()->cannot('users.update'), 403);
        $request->validate([
            'name' => ['required'],
            'username' => [
                'required',
                'unique:users,username,' . $user->id
            ],
            'nrk' => [
                'required',
                'unique:users,nrk,' . $user->id
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email,' . $user->id
            ],
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'nrk' => $request->nrk,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }


        return redirect()
            ->route('manage.user-roles.index')
            ->with('success', 'Pengguna berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        abort_if(Auth::user()->cannot('users.destory'), 403);
        $user->delete();

        return redirect()
            ->route('manage.user-roles.index')
            ->with('success', 'Pengguna berhasil dihapus');
    }
}
