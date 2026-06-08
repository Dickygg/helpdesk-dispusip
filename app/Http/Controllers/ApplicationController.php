<?php

namespace App\Http\Controllers;

use App\Exports\GenericExport;
use App\Models\ApplicationModels;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ApplicationModels::select('id', 'name', 'description')->get();

        return view('application.index', [
            'data' => $data
        ]);
    }

    public function export()
    {
        $application = ApplicationModels::select('id', 'name', 'description')->get();
        $data = $application->map(function ($application) {
            return [
                'Daftar Aplikasi' => $application->name,
                'Deskripsi' => $application->description,
            ];
        });

        return Excel::download(
            new GenericExport($data),
            'Daftar Daftar Aplikasi.xlsx'
        );
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
        abort_if(Auth::user()->cannot('application.store'), 403);

        $rules = [
            'name' => 'required|max:25|min:3',
            'description' => 'required'
        ];

        $messages = [
            'name.required' => 'Nama Role Tidak Boleh Kosong!',
            'name.max' => 'Maksimal Kata Hanya 25 Huruf!',
            'name.min' => 'Manimal Kata 3 Huruf!',
            'description.required' => 'Deskripsi Aplikasi Tidak Boleh Kosong!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        ///sementara
        $users = Auth::user();


        DB::beginTransaction();
        try {
            ApplicationModels::create([
                'name' => $request->name,
                'description' => $request->description,
                'created_by' => $users->id
            ]);
            DB::commit();
            return redirect()->route('application.index')->with('success', 'Data Berhasil Disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('application.index')->with('error', 'Oops, Data Tidak Berhasil Disimpan!');
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
        abort_if(Auth::user()->cannot('application.edit'), 403);

        $data = ApplicationModels::findOrFail($id);
        if (!$data) {
            abort(404);
        }

        return view('application.edit', [
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(Auth::user()->cannot('application.update'), 403);

        $data = ApplicationModels::findOrFail($id);
        if (!$data) {
            abort(404);
        }

        $rules = [
            'name' => 'required|max:25|min:3',
            'description' => 'required'
        ];

        $messages = [
            'name.required' => 'Nama Role Tidak Boleh Kosong!',
            'name.max' => 'Maksimal Kata Hanya 25 Huruf!',
            'name.min' => 'Manimal Kata 3 Huruf!',
            'description.required' => 'Deskripsi Aplikasi Tidak Boleh Kosong!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $data->name = $request->name;
            $data->description = $request->description;
            $data->update();
            DB::commit();
            return redirect()->route('application.index')->with('success', 'Data Berhasil Di Update!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        abort_if(Auth::user()->cannot('application.destroy'), 403);

        DB::beginTransaction();
        try {
            $data = ApplicationModels::findOrFail($id);
            if (!$data) {
                abort(404);
            }

            $data->delete();
            $msg = "Data Berhasil DiHapus!";
            DB::commit();
            return redirect()->route('application.index')->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('application.index')->with('error', 'Oops, Data Tidak Berhasil Dihapus!');
        }
    }
}
