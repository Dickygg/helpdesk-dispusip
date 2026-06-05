<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PenggunaDashboardController extends Controller
{


    public function index()
    {
        abort_if(Auth::user()->cannot('dashboard.pengguna'), 403);

        return view('dashboard.pengguna.index');
    }
}
