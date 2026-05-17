<?php

namespace App\Http\Controllers;

use App\Models\TicketStatusModels;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $data = TicketStatusModels::select('id', 'name', 'description')->orderBy('created_at', 'ASC')->get();

        return view('status.index', [
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
        abort_if(Auth::user()->cannot('status.store'), 403);
        $rules = [
            'name' => 'required|max:25|min:3',
            'description' => 'required'
        ];

        $messages = [
            'name.required' => 'Nama Status Tidak Boleh Kosong!',
            'name.max' => 'Maksimal Kata Hanya 25 Huruf!',
            'name.min' => 'Manimal Kata 3 Huruf!',
            'description.required' => 'Deskripsi Status Tidak Boleh Kosong!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //sementara
        $users = Auth::user();

        DB::beginTransaction();
        try {
            TicketStatusModels::create([
                'name' => $request->name,
                'description' => $request->description,
                'created_by' => $users->id
            ]);
            DB::commit();
            return redirect()->route('status.index')->with('success', 'Data Berhasil Disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('status.index')->with('error', 'Oops, Data Tidak Berhasil DiSimpan!.');
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
        abort_if(Auth::user()->cannot('status.edit'), 403);

        $data = TicketStatusModels::findOrFail($id);
        if (!$data) {
            abort(404);
        }

        return view('status.edit', [
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(Auth::user()->cannot('status.update'), 403);

        $data = TicketStatusModels::findOrFail($id);
        if (!$data) {
            abort(404);
        }

        $rules = [
            'name' => 'required|max:25|min:3',
            'description' => 'required'
        ];

        $messages = [
            'name.required' => 'Nama Status Tidak Boleh Kosong!',
            'name.max' => 'Maksimal Kata Hanya 25 Huruf!',
            'name.min' => 'Manimal Kata 3 Huruf!',
            'description.required' => 'Deskripsi Status Tidak Boleh Kosong!'
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
            return redirect()->route('status.index')->with('success', 'Data Berhasil DiUpdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('status.index')->with('error', 'Oops, Data gagal Diupdate!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(Auth::user()->cannot('status.destroy'), 403);

        $data = TicketStatusModels::findOrfail($id);

        if (!$data) {
            abort(404);
        }
        DB::beginTransaction();
        try {
            $data->delete();
            DB::commit();
            return redirect()->route('status.index')->with('success', 'Data Berhasil DiHapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('status.index')->with('error', 'Oops, Data gagal DiHapus');
        }
    }
}
