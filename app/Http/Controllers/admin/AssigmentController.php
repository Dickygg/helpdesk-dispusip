<?php

namespace App\Http\Controllers\admin;

use App\Helpres\ActivityHelper;
use App\Http\Controllers\Controller;
use App\Models\ApplicationModels;
use App\Models\TicketAssignmentModels;
use App\Models\TicketModels;
use App\Models\TicketPriorityModels;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AssigmentController extends Controller
{

    public function index(Request $request)
    {
        $assignment = TicketAssignmentModels::with(['ticket', 'technician', 'admin'])
            ->whereHas('ticket', function ($q) {
                $q->whereNotIn('status', ['Closed', 'Rejected']);
            })
            ->when($request->start_date, function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->end_date);
            })
            ->when($request->id_aplikasi, function ($q) use ($request) {
                $q->whereHas('ticket', function ($q) use ($request) {
                    $q->where('application_id', $request->id_aplikasi);
                });
            })
            ->when($request->id_petugas, function ($q) use ($request) {
                $q->where('user_id', $request->id_petugas);
            })
            ->when($request->id_priority, function ($q) use ($request) {
                $q->whereHas('ticket', function ($q) use ($request) {
                    $q->where('priority_id', $request->id_priority);
                });
            })
            ->orderBy('created_at', 'DESC')
            ->get();


        $aplikasi = ApplicationModels::select('id', 'name')->get();
        $petugas = User::select('id', 'username')
            ->role('petugas teknis')
            ->get();
        $prioritas = TicketPriorityModels::select('id', 'name')->get();

        return view('assignment.admin.index', [
            'data' => $assignment,
            'aplikasi' => $aplikasi,
            'petugas' => $petugas,
            'prioritas' => $prioritas
        ]);
    }

    public function assignment(Request $request, string $id)
    {
        abort_if(Auth::user()->cannot('tiket.admin.assignment'), 403);


        $rules = [
            'user_id' => 'required'
        ];

        $messages = [
            'user_id.required' => 'Petugas Teknis Belum Dipilih!.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tiket   = TicketModels::findOrFail($id);
        if (in_array($tiket->status, ['Rejected', 'Closed'])) {
            return redirect()->back()->with('error', 'Tiket ini sudah ditutup atau Ditolak dan tidak dapat diubah.');
        }

        $oldStatus = $tiket->status;
        DB::beginTransaction();
        try {
            $assignment = TicketAssignmentModels::create([
                'ticket_id' => $id,
                'user_id' => $request->user_id,
                'assigned_by' => Auth::user()->id,
                'assigned_at' => now()
            ]);
            $tiket->update([
                'status' => 'Assigned',
                'due_date' => $assignment->assigned_at
                    ->copy()
                    ->addHours($tiket->priority->estimated_hours)
            ]);

            ActivityHelper::logAssign(
                $tiket,
                before: ['status' => $oldStatus],
                after: ['status' => $tiket->status],
            );
            DB::commit();
            return redirect()->back()->with('success', 'Petugas berhasil di-assign.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            dd($e);
            return redirect()->back()->with('error', 'Gagal melakukan assign petugas.');
        }
    }
}
