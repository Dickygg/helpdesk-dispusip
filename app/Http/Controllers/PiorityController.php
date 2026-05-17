<?php

namespace App\Http\Controllers;

use App\Models\TicketPriorityModels;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PiorityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = TicketPriorityModels::select('id', 'name', 'estimated_hours')->orderBy('estimated_hours', 'ASC')->get();

        return view('piority.index', [
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
        abort_if(Auth::user()->cannot('priority.store'), 403);

        $rules = [
            'name' => 'required|max:25|min:3',
            'estimated_hours' => 'required|max:3',
        ];

        $messages = [
            'name.required' => 'Nama Pioritas Tidak Boleh Kosong!',
            'name.max' => 'Maksimal Kata Hanya 25 Huruf!',
            'name.min' => 'Manimal Kata 3 Huruf!',
            'estimated_hours.required' => 'Estimasi Jam Pekerjaan Harus Diisi!.',
            'estimated_hours.max' => 'Batas Pengerjaan Jam Hanya Dibawah 100 Jam',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $users = Auth::user();
        DB::beginTransaction();
        try {
            TicketPriorityModels::create([
                'name' => $request->name,
                'estimated_hours' => $request->estimated_hours,
                'created_by' => $users->id
            ]);
            DB::commit();
            return redirect()->route('piority.index')->with('success', 'Data Berhasil Disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('piority.index')->with('error', 'Oops,Gagal Menyimpan Data!');
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
        abort_if(Auth::user()->cannot('priority.edit'), 403);

        $data = TicketPriorityModels::findOrFail($id);

        if (!$data) {
            abort(404);
        }

        return view('piority.edit', [
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        abort_if(Auth::user()->cannot('priority.update'), 403);

        $data = TicketPriorityModels::findOrFail($id);
        if (!$data) {
            abort(404);
        }

        $rules = [
            'name' => 'required|max:25|min:3',
            'estimated_hours' => 'required|max:3',
        ];

        $messages = [
            'name.required' => 'Nama Pioritas Tidak Boleh Kosong!',
            'name.max' => 'Maksimal Kata Hanya 25 Huruf!',
            'name.min' => 'Manimal Kata 3 Huruf!',
            'estimated_hours.required' => 'Estimasi Jam Pekerjaan Harus Diisi!.',
            'estimated_hours.max' => 'Batas Pengerjaan Jam Hanya Dibawah 100 Jam',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $data->name = $request->name;
            $data->estimated_hours = $request->estimated_hours;
            $data->update();
            DB::commit();
            return redirect()->route('piority.index')->with('success', 'Data Berhasil DiUpdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('piority.index')->with('error', 'Oops,Gagal Mengupdate Data!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(Auth::user()->cannot('priority.destroy'), 403);

        $data = TicketPriorityModels::findOrFail($id);

        if (!$data) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $data->delete();
            DB::commit();
            return redirect()->route('piority.index')->with('success', 'Data Berhasil DiHapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('piority.index')->with('error', 'Oops, Data Gagal Dihapus');
        }
    }
}
