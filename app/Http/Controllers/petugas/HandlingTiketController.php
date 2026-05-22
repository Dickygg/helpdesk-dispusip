<?php

namespace App\Http\Controllers\petugas;

use App\Http\Controllers\admin\AssigmentController;
use App\Http\Controllers\Controller;
use App\Models\ApplicationModels;
use App\Models\TicketAssignmentModels;
use App\Models\TicketModels;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class HandlingTiketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::id();

        $query = TicketAssignmentModels::with([
            'ticket',
            'technician:id,username',
            'admin:id,username'
        ])
            ->where('ticket_assignments.user_id', $user)
            ->whereHas('ticket', function ($q) {
                $q->whereNotIn('status', ['Closed']);
            })
            // Filter condition (status/deadline)
            ->when($request->condition, function ($q) use ($request) {
                match ($request->condition) {
                    'Resolved'    => $q->whereHas('ticket', fn($q) => $q->where('status', 'Resolved')),
                    'In Progress' => $q->whereHas('ticket', fn($q) => $q->where('status', 'In Progress')),
                    'upcoming'    => $q->whereHas('ticket', fn($q) => $q->whereBetween('due_date', [now(), now()->addHours(24)])),
                    'overDuetime' => $q->whereHas('ticket', fn($q) => $q->where('due_date', '<', now())->whereNotIn('status', ['Closed', 'Resolved'])),
                    default       => null
                };
            })
            // Filter tanggal
            ->when($request->start_date, fn($q) => $q->whereDate('ticket_assignments.created_at', '>=', $request->start_date))
            ->when($request->end_date,   fn($q) => $q->whereDate('ticket_assignments.created_at', '<=', $request->end_date))
            // Filter aplikasi
            ->when($request->id_aplikasi, fn($q) => $q->whereHas('ticket', fn($q) => $q->where('application_id', $request->id_aplikasi)))
            // Filter deadline
            ->when($request->deadline_filter, function ($q) use ($request) {
                match ($request->deadline_filter) {
                    'today'    => $q->whereHas('ticket', fn($q) => $q->whereDate('due_date', today())),
                    'week'     => $q->whereHas('ticket', fn($q) => $q->whereBetween('due_date', [now(), now()->endOfWeek()])),
                    'overdue'  => $q->whereHas('ticket', fn($q) => $q->where('due_date', '<', now())),
                    'upcoming' => $q->whereHas('ticket', fn($q) => $q->whereBetween('due_date', [now(), now()->addHours(24)])),
                    default    => null
                };
            });

        $data = $query->get();

        $deadline = TicketAssignmentModels::with(['ticket', 'technician', 'admin'])
            ->where('user_id', $user)
            ->whereHas('ticket', function ($q) {
                $q->whereDate('due_date', today());
            })
            ->get();

        $getassignstats = $this->gettotalAssign($request);
        $aplikasi = ApplicationModels::select('id', 'name')->get();

        return view('assignment.petugas.index', [
            'getassignstats' => $getassignstats,
            'aplikasi'       => $aplikasi,
            'data'           => $data,
            'deadline'       => $deadline
        ]);
    }

    public function show(string $id)
    {
        $data = TicketAssignmentModels::with([
            'ticket.attachments',
            'ticket.priority',
            'ticket.application',
            'technician:id,name',
            'admin:id,name'
        ])->findOrFail($id);


        $logs = Activity::where('subject_type', TicketModels::class)
            ->where('subject_id', $data->id)
            ->with('causer')
            ->latest()
            ->get();

        return view('assignment.petugas.detail', [
            'data' => $data,
            'logs' => $logs
        ]);
    }
    public function prosesAssignment(string $id)
    {
        //
    }

    private function gettotalAssign(Request $request)
    {
        $user = Auth::id();

        $query = TicketAssignmentModels::query()
            ->where('ticket_assignments.user_id', $user)
            ->when($request->start_date, function ($q) use ($request) {
                $q->whereDate('ticket_assignments.created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($q) use ($request) {
                $q->whereDate('ticket_assignments.created_at', '<=', $request->end_date);
            })
            ->when($request->id_aplikasi, function ($q) use ($request) {
                $q->whereHas('ticket', function ($q) use ($request) {
                    $q->where('application_id', $request->id_aplikasi);
                });
            });

        $AssignStats = (clone $query)
            ->select('tickets.status', DB::raw('COUNT(*) as total'))
            ->join('tickets', 'ticket_assignments.ticket_id', '=', 'tickets.id')
            ->whereIn('tickets.status', ['Resolved', 'In Progress'])
            ->groupBy('tickets.status')
            ->get();

        $menujuDeadline = (clone $query)
            ->join('tickets', 'ticket_assignments.ticket_id', '=', 'tickets.id')
            ->whereBetween('tickets.due_date', [now(), now()->addHours(24)])
            ->count();

        $overDuetime = (clone $query)
            ->join('tickets', 'ticket_assignments.ticket_id', '=', 'tickets.id')
            ->where('tickets.due_date', '<', now())
            ->whereNotIn('tickets.status', ['Closed'])
            ->count();
        $AssignTotal = (clone $query)->count();

        $selesai  = $AssignStats->firstWhere('status', 'Closed');
        $diproses = $AssignStats->firstWhere('status', 'In Progress');

        return [
            'assignstats'     => $AssignStats,
            'assigntotal'     => $AssignTotal,
            'total_selesai'   => $selesai->total ?? 0,
            'total_diproses'  => $diproses->total ?? 0,
            'menuju_deadline' => $menujuDeadline,
            'overDuetime' => $overDuetime,
        ];
    }
}
