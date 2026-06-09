<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TicketAssignmentModels;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class PetugasDashboardController extends Controller
{


    public function index(Request $request)
    {
        abort_if(Auth::user()->cannot('dashboard.petugas'), 403);
        $user = Auth::id();
        $causedBy = User::findOrFail($user);
        $logs = Activity::causedBy($causedBy)
            ->with('subject')
            ->latest()
            ->limit(5)
            ->get();
        $getassignstats = $this->gettotalAssign($request, $user);
        $newassignment = $this->getassignmentnew($request, $user);
        $priorityStats = $this->getPriorityStats($request, $user);
        $deadlineassignments = $this->getdeadlineassignment($user);
        $chartData = $this->getassignmentChartData($request, $user);
        $sla = $this->getSlaStatistics($request, $user);
        return view('dashboard.petugas.index', [
            'getassignstats' => $getassignstats,
            'newassignment' => $newassignment['data'],
            'priorityChart' => $priorityStats,
            'deadline' => $deadlineassignments,
            'sla' => $sla,
            'chartData' => $chartData,
            'logs' => $logs
        ]);
    }


    private function gettotalAssign(Request $request, string $user)
    {
        $query = TicketAssignmentModels::query()
            ->where('ticket_assignments.user_id', $user)
            ->when($request->start_date, function ($q) use ($request) {
                $q->whereHas('ticket', function ($query) use ($request) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                });
            })
            ->when($request->end_date, function ($q) use ($request) {
                $q->whereHas('ticket', function ($query) use ($request) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                });
            });
        $AssignStats = (clone $query)
            ->select('tickets.status', DB::raw('COUNT(*) as total'))
            ->join('tickets', 'ticket_assignments.ticket_id', '=', 'tickets.id')
            ->whereIn('tickets.status', ['Resolved', 'In Progress', 'Closed'])
            ->groupBy('tickets.status')
            ->get();

        $avgWorkDuration = (clone $query)
            ->avg('work_duration');
        $avgMinutes = round($avgWorkDuration);
        $hours      = intdiv($avgMinutes, 60);
        $minutes    = $avgMinutes % 60;

        $overDuetime = (clone $query)
            ->join('tickets', 'ticket_assignments.ticket_id', '=', 'tickets.id')
            ->where('tickets.due_date', '<', now())
            ->whereNotIn('tickets.status', ['Closed'])
            ->count();
        $AssignTotal = (clone $query)->count();

        $selesai  = $AssignStats->firstWhere('status', 'Resolved');
        $diproses = $AssignStats->firstWhere('status', 'In Progress');
        $closed = $AssignStats->firstWhere('status', 'Closed');

        return [
            'assignstats'     => $AssignStats,
            'assigntotal'     => $AssignTotal,
            'total_selesai'   => $selesai->total ?? 0,
            'total_diproses'  => $diproses->total ?? 0,
            'total_closed'  => $closed->total ?? 0,
            'avg_work_duration' => "{$hours}j {$minutes}m",
            'overDuetime' => $overDuetime,
        ];
    }

    private function getassignmentChartData(Request $request, string $user)
    {
        $query = TicketAssignmentModels::query()
            ->with('ticket')
            ->where('ticket_assignments.user_id', $user)
            ->when($request->start_date, function ($q) use ($request) {
                $q->whereHas('ticket', function ($query) use ($request) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                });
            })
            ->when($request->end_date, function ($q) use ($request) {
                $q->whereHas('ticket', function ($query) use ($request) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                });
            });

        $assignments = (clone $query)
            ->join('tickets', 'ticket_assignments.ticket_id', '=', 'tickets.id')
            ->selectRaw('MONTH(tickets.created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $labels = [];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = \Carbon\Carbon::create()->month($i)->translatedFormat('M');
            $data[] = $assignments[$i] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
    private function getassignmentnew(Request $request, string $user)
    {
        $data = TicketAssignmentModels::with('ticket')
            ->select('id', 'assigned_by', 'user_id', 'ticket_id')
            ->where('user_id', $user)
            ->orderBy('assigned_at', 'desc')
            ->limit(5)
            ->when($request->start_date, function ($q) use ($request) {
                $q->whereHas('ticket', function ($query) use ($request) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                });
            })
            ->when($request->end_date, function ($q) use ($request) {
                $q->whereHas('ticket', function ($query) use ($request) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                });
            })
            ->get();

        return [
            'data' => $data
        ];
    }

    private function getPriorityStats(Request $request, string $user)
    {
        $assignments = TicketAssignmentModels::with('ticket.priority')
            ->where('user_id', $user)
            ->whereHas('ticket', function ($query) {
                $query->whereIn('status', ['Closed', 'Resolved']);
            })
            ->when($request->start_date, function ($q) use ($request) {
                $q->whereHas('ticket', function ($query) use ($request) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                });
            })
            ->when($request->end_date, function ($q) use ($request) {
                $q->whereHas('ticket', function ($query) use ($request) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                });
            })
            ->get();

        $total = $assignments->count();

        $priorities = $assignments
            ->groupBy(fn($item) => $item->ticket->priority->name ?? 'Tanpa Prioritas')
            ->map(function ($items) use ($total) {
                $count = $items->count();

                return [
                    'count' => $count,
                    'percent' => $total > 0
                        ? round(($count / $total) * 100, 1)
                        : 0,
                ];
            });

        return [
            'total' => $total,
            'labels' => $priorities->keys()->values()->toArray(),
            'data' => $priorities->pluck('count')->values()->toArray(),
            'priorities' => $priorities,
        ];
    }
    private function getdeadlineassignment(string $user)
    {
        return TicketAssignmentModels::with(['ticket', 'admin'])
            ->where('user_id', $user)
            ->whereHas('ticket', function ($q) {
                $q->whereIn('status', ['In Progress', 'Assigned', 'Reopen'])
                    ->whereDate('due_date', '<=', today());
            })

            ->limit(5)
            ->get();
    }
    private function getSlaStatistics(Request $request, string $user)
    {
        $query = TicketAssignmentModels::query()
            ->where('ticket_assignments.user_id', $user)
            ->join('tickets', 'tickets.id', '=', 'ticket_assignments.ticket_id')

            ->when($request->start_date, function ($q) use ($request) {
                $q->whereHas('ticket', function ($query) use ($request) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                });
            })
            ->when($request->end_date, function ($q) use ($request) {
                $q->whereHas('ticket', function ($query) use ($request) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                });
            })
            ->whereNotNull('ticket_assignments.finished_at');

        $total = (clone $query)->count();

        $ontime = (clone $query)
            ->whereColumn('ticket_assignments.finished_at', '<=', 'tickets.due_date')
            ->count();

        $late = (clone $query)
            ->whereColumn('ticket_assignments.finished_at', '>', 'tickets.due_date')
            ->count();

        return [
            'ontime'         => $ontime,
            'late'           => $late,
            'ontime_percent' => $total > 0 ? round(($ontime / $total) * 100, 1) : 0,
            'late_percent'   => $total > 0 ? round(($late / $total) * 100, 1) : 0,
            'sla_percent'    => $total > 0 ? round(($ontime / $total) * 100, 1) : 0,
        ];
    }
}
