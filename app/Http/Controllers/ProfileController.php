<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'     => 'required|max:255',
            'nrk'      => 'nullable|max:30',
            'username' => 'required|max:200|unique:users,username,' . Auth::id(),
            'email'    => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        DB::beginTransaction();

        try {

            $user = Auth::user();

            $user->update([
                'name'     => $request->name,
                'nrk'      => $request->nrk,
                'username' => $request->username,
                'email'    => $request->email,
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Profile berhasil diperbarui');
        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Gagal update profile', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat update profile');
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        DB::beginTransaction();

        try {

            $user = Auth::user();

            if (!Hash::check(
                $request->current_password,
                $user->password
            )) {
                return back()->with(
                    'error',
                    'Password lama tidak sesuai'
                );
            }
            // Cek password baru sama dengan password sekarang
            if (Hash::check($request->password, $user->password)) {

                DB::rollBack();

                return back()->with(
                    'error',
                    'Password baru tidak boleh sama dengan password saat ini'
                );
            }

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Password berhasil diubah');
        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Gagal update password', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat update password');
        }
    }
}
