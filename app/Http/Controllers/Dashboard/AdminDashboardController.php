<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{


    public function index()
    {
        abort_if(Auth::user()->cannot('dashboard.admin'), 403);
        return view('testlayout');
    }
}
