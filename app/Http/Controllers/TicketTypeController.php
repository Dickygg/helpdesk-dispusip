<?php

namespace App\Http\Controllers;

use App\Exports\GenericExport;
use App\Models\TicketsTypeModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class TicketTypeController extends Controller
{
    public function index()
    {
        $data = TicketsTypeModels::select('id', 'name', 'description')->orderBy('created_at', 'ASC')->get();

        return view('tiket_type.index', [
            'data' => $data
        ]);
    }

    public function export()
    {
        $tikettipe = TicketsTypeModels::select('id', 'name', 'description')->orderBy('created_at', 'ASC')->get();
        $data = $tikettipe->map(function ($tikettipe) {
            return [
                'Tipe TIket' => $tikettipe->name,
                'Deskripsi' => $tikettipe->description,
            ];
        });

        return Excel::download(
            new GenericExport($data),
            'Daftar Tipe Tiket.xlsx'
        );
    }

    public function store(Request $request)
    {
        abort_if(Auth::user()->cannot('ticket-type.store'), 403);
        $rules = [
            'name' => 'required|max:25|min:3',
            'description' => 'required'
        ];

        $messages = [
            'name.required' => 'Tipe Tiket Tidak Boleh Kosong!',
            'name.max' => 'Maksimal Kata Hanya 25 Huruf!',
            'name.min' => 'Manimal Kata 3 Huruf!',
            'description.required' => 'Deskripsi Tipe Tiket Tidak Boleh Kosong!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //sementara
        $users = Auth::user();

        DB::beginTransaction();
        try {
            TicketsTypeModels::create([
                'name' => $request->name,
                'description' => $request->description,
                'created_by' => $users->id
            ]);
            DB::commit();
            return redirect()->route('ticket-type.index')->with('success', 'Data Berhasil Disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('ticket-type.index')->with('error', 'Oops, Data Tidak Berhasil DiSimpan!.');
        }
    }

    public function edit(string $id)
    {
        // abort_if(Auth::user()->cannot('ticket-type.edit'), 403);

        $data = TicketsTypeModels::findOrFail($id);
        if (!$data) {
            abort(404);
        }

        return view('tiket_type.edit', [
            'data' => $data
        ]);
    }

    public function update(Request $request, string $id)
    {
        abort_if(Auth::user()->cannot('ticket-type.update'), 403);

        $data = TicketsTypeModels::findOrFail($id);
        if (!$data) {
            abort(404);
        }

        $rules = [
            'name' => 'required|max:25|min:3',
            'description' => 'required'
        ];

        $messages = [
            'name.required' => 'Tipe Tiket Tidak Boleh Kosong!',
            'name.max' => 'Maksimal Kata Hanya 25 Huruf!',
            'name.min' => 'Manimal Kata 3 Huruf!',
            'description.required' => 'Deskripsi Tipe Tiket Tidak Boleh Kosong!'
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
            return redirect()->route('ticket-type.index')->with('success', 'Data Berhasil DiUpdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('ticket-type.index')->with('error', 'Oops, Data gagal Diupdate!');
        }
    }

    public function destroy(string $id)
    {
        abort_if(Auth::user()->cannot('ticket-type.destroy'), 403);

        $data = TicketsTypeModels::findOrfail($id);

        if (!$data) {
            abort(404);
        }
        DB::beginTransaction();
        try {
            $data->delete();
            DB::commit();
            return redirect()->route('ticket-type.index')->with('success', 'Data Berhasil DiHapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('ticket-type.index')->with('error', 'Oops, Data gagal DiHapus');
        }
    }
}
