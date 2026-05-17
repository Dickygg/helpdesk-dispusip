<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestSBadmin extends Controller
{
    public function index()
    {
        return view('testsbadmin');
    }


    public function testlayout()
    {
        return view('testlayout');
    }
}
