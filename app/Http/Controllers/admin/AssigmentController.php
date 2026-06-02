<?php

namespace App\Http\Controllers\admin;

use App\Helpres\ActivityHelper;
use App\Http\Controllers\Controller;
use App\Models\ApplicationModels;
use App\Models\TicketAssignmentModels;
use App\Models\TicketModels;
use App\Models\TicketPriorityModels;
use App\Models\TicketsTypeModels;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;

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
            ->when($request->ticket_type_id, function ($q) use ($request) {
                $q->whereHas('ticket', function ($q) use ($request) {
                    $q->where('ticket_type_id', $request->ticket_type_id);
                });
            })
            ->orderBy('created_at', 'DESC')
            ->get();


        $aplikasi = ApplicationModels::select('id', 'name')->get();
        $petugas = User::select('id', 'username')
            ->role('petugas teknis')
            ->get();
        $prioritas = TicketPriorityModels::select('id', 'name')->get();
        $tipetiket = TicketsTypeModels::select('id', 'name')->get();

        return view('assignment.admin.index', [
            'data' => $assignment,
            'aplikasi' => $aplikasi,
            'petugas' => $petugas,
            'prioritas' => $prioritas,
            'tipetiket' => $tipetiket

        ]);
    }

    public function historyAssignment(Request $request)
    {
        abort_if(Auth::user()->cannot('assignment.history'), 403);

        $query = TicketAssignmentModels::with([
            'ticket',
            'technician:id,username',
            'admin:id,username'
        ])
            ->whereHas('ticket', function ($q) {
                $q->whereIn('status', ['Closed']);
            })
            // Filter tanggal
            ->when($request->start_date, fn($q) => $q->whereDate('ticket_assignments.created_at', '>=', $request->start_date))
            ->when($request->end_date,   fn($q) => $q->whereDate('ticket_assignments.created_at', '<=', $request->end_date))
            // Filter aplikasi
            ->when($request->id_aplikasi, fn($q) => $q->whereHas('ticket', fn($q) => $q->where('application_id', $request->id_aplikasi)))
            ->when($request->id_petugas, function ($q) use ($request) {
                $q->where('user_id', $request->id_petugas);
            })
            ->when($request->id_priority, function ($q) use ($request) {
                $q->whereHas('ticket', function ($q) use ($request) {
                    $q->where('priority_id', $request->id_priority);
                });
            })
            ->when($request->ticket_type_id, function ($q) use ($request) {
                $q->whereHas('ticket', function ($q) use ($request) {
                    $q->where('ticket_type_id', $request->ticket_type_id);
                });
            });

        $data = $query->get();
        $aplikasi = ApplicationModels::select('id', 'name')->get();
        $prioritas = TicketPriorityModels::select('id', 'name')->get();
        $tipetiket = TicketsTypeModels::select('id', 'name')->get();
        $petugas = User::select('id', 'username')
            ->role('petugas teknis')
            ->get();

        $getstats = $this->gethistroystats($request);

        return view('assignment.admin.history', [
            'getassignstats' => $getstats,
            'aplikasi'       => $aplikasi,
            'data'           => $data,
            'tipetiket' => $tipetiket,
            'prioritas' => $prioritas,
            'petugas' => $petugas
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

    public function show(string $id)
    {
        abort_if(Auth::user()->cannot('assignment.show'), 403);
        $data = TicketAssignmentModels::with([
            'ticket.attachments',
            'ticket.priority',
            'ticket.application',
            'ticket.tickettype',
            'technician:id,name',
            'admin:id,name',
            'Assignattachments',
        ])->findOrFail($id);


        $logs = Activity::where('subject_type', TicketModels::class)
            ->where('subject_id', $data->ticket->id)
            ->with('causer')
            ->latest()
            ->get();



        return view('assignment.admin.detail', [
            'data' => $data,
            'logs' => $logs
        ]);
    }

    private function gethistroystats(Request $request)
    {


        $query = TicketAssignmentModels::query()
            ->whereHas('ticket', function ($q) {
                $q->whereIn('status', ['closed']);
            })
            ->when($request->start_date, function ($q) use ($request) {
                $q->whereDate('ticket_assignments.created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($q) use ($request) {
                $q->whereDate('ticket_assignments.created_at', '<=', $request->end_date);
            })
            ->when($request->id_petugas, function ($q) use ($request) {
                $q->where('ticket_assignments.user_id', $request->id_petugas);
            })
            ->when($request->id_aplikasi, function ($q) use ($request) {
                $q->whereHas('ticket', function ($q) use ($request) {
                    $q->where('application_id', $request->id_aplikasi);
                });
            })
            ->when($request->ticket_type_id, function ($q) use ($request) {
                $q->whereHas('ticket', function ($q) use ($request) {
                    $q->where('ticket_type_id', $request->ticket_type_id);
                });
            });

        $AssignHistoryTotal = (clone $query)->count();

        $avgWorkDuration = (clone $query)
            ->avg('work_duration');
        $avgMinutes = round($avgWorkDuration);
        $hours      = intdiv($avgMinutes, 60);
        $minutes    = $avgMinutes % 60;

        $AssignHistoryTotalMonth = (clone $query)
            ->whereHas('ticket', function ($q) {
                $q->whereBetween('closed_at', [
                    now()->startOfMonth(),
                    now()->endOfMonth(),
                ]);
            })
            ->count();
        $AssignHistoryTotalOverDeadline = (clone $query)
            ->join('tickets as t_deadline', 't_deadline.id', '=', 'ticket_assignments.ticket_id')
            ->whereNotNull('ticket_assignments.finished_at')
            ->whereNotNull('t_deadline.due_date')
            ->whereRaw('ticket_assignments.finished_at > t_deadline.due_date')
            ->count('ticket_assignments.ticket_id');
        return [
            'assigntotal'       => $AssignHistoryTotal,
            'avg_work_duration' => "{$hours}j {$minutes}m",
            'assignmounthtotal' => $AssignHistoryTotalMonth,
            'historytotaloverdeadline' => $AssignHistoryTotalOverDeadline
        ];
    }
}
