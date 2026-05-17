<?php

namespace App\Http\Controllers;

use App\Models\RoleModels;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = RoleModels::OrderBy('created_at', 'ASC')->get();


        return view('roles.index', [
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // abort_if(Auth::user()->cannot('status.store'), 403);

        $rules = [
            'name' => 'required|max:25|min:3',
        ];

        $messages = [
            'name.required' => 'Nama Role Tidak Boleh Kosong!',
            'name.max' => 'Maksimal Kata Hanya 25 Huruf!',
            'name.min' => 'Manimal Kata 3 Huruf!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        try {
            RoleModels::create([
                'roles_name' => $request->name
            ]);

            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Data Berhasil Disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('roles.index')->with('error', 'Oops,Gagal Menyimpan Data!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = RoleModels::findOrFail($id);
        if (!$data) {
            abort(404);
        }

        return view('roles.edit', [
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = RoleModels::findOrFail($id);
        if (!$data) {
            abort(404);
        }

        $rules = [
            'name' => 'required|max:25|min:3',
        ];

        $messages = [
            'name.required' => 'Nama Role Tidak Boleh Kosong!',
            'name.max' => 'Maksimal Kata Hanya 25 Huruf!',
            'name.min' => 'Manimal Kata 3 Huruf!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        try {
            $data->roles_name = $request->name;
            $data->update();
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Data Berhasil Di Update!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('roles.index')->with('error', 'Oops, Data Tidak Berhasil Disimpan!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        DB::beginTransaction();
        try {
            $data = RoleModels::find($id);
            if (!$data) {
                abort(404);
            }
            $data->delete();
            $msg = "Data Berhasil DiHapus";
            DB::commit();
            return redirect()->route('roles.index')->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            dd($e);
            return redirect()->route('roles.index')->with('error', 'Oops, Data Tidak Berhasil Dihapus!.');
        }
    }
}
