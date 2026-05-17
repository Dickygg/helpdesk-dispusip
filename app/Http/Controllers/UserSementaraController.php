<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserSementaraController extends Controller
{
    public function getUser()
    {
        $user = User::whereHas('role', function ($query) {
            $query->where('roles_name', 'Super Admin');
        })->first();

        return [
            'user' => $user
        ];
    }

    public function getpengguna()
    {
        $pengguna = User::whereHas('role', function ($query) {
            $query->where('roles_name', 'Pengguna');
        })->first();

        return [
            'pengguna' => $pengguna
        ];
    }
}
