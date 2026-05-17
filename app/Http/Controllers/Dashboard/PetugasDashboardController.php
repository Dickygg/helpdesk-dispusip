<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PetugasDashboardController extends Controller
{


    public function index()
    {
        abort_if(Auth::user()->cannot('dashboard.petugas'), 403);
        return view('testlayout');
    }
}
