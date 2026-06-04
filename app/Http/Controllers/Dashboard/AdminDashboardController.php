<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TicketAssignmentModels;
use App\Models\TicketModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{


    public function index(Request $request)
    {
        abort_if(Auth::user()->cannot('dashboard.admin'), 403);


        $getstatusstats = $this->getstastStatus($request);
        $chartData = $this->getTicketChartData($request);
        $priorityChart = $this->getPriorityChart($request);
        $typetikett = $this->getTypechart($request);
        $sla = $this->getSlaStatistics($request);
        $newtiket = $this->getNewTicket($request);
        $deadlineticket = $this->getDeadlineTicket($request);
        $assignmentStats = $this->getTechnicianPerformance($request);
        return view('dashboard.admin.index', [
            'tiketstats' => $getstatusstats['statysStats'],
            'tikettotal' => $getstatusstats['total'],
            'chartData' => $chartData,
            'priorityChart' => $priorityChart,
            'typetikett' => $typetikett,
            'sla' => $sla,
            'newtiket' => $newtiket['data'],
            'deadlinetiket' => $deadlineticket['data'],
            'assignmentstats' => $assignmentStats
        ]);
    }


    private function getstastStatus(Request $request)
    {
        $query = TicketModels::query()

            ->when($request->start_date, function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->start_date);
            })

            ->when($request->end_date, function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->end_date);
            });

        $statusStats = (clone $query)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'total'       => (clone $query)->count(),
            'statysStats' => $statusStats
        ];
    }

    private function getTicketChartData(Request $request)
    {
        $query = TicketModels::query()

            ->when($request->start_date, function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->start_date);
            })

            ->when($request->end_date, function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->end_date);
            });

        $tickets = (clone $query)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $labels = [];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = \Carbon\Carbon::create()->month($i)->translatedFormat('M');
            $data[] = $tickets[$i] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    private function getPriorityChart(Request $request)
    {
        $priorities = TicketModels::query()
            ->join(
                'ticket_priorities',
                'tickets.priority_id',
                '=',
                'ticket_priorities.id'
            )
            ->when($request->start_date, function ($q) use ($request) {
                $q->whereDate('tickets.created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($q) use ($request) {
                $q->whereDate('tickets.created_at', '<=', $request->end_date);
            })
            ->selectRaw('ticket_priorities.name, COUNT(*) as total')
            ->groupBy('ticket_priorities.name')
            ->pluck('total', 'name');

        return [
            'labels' => [
                'Emergency',
                'Urgent',
                'Normal',
            ],

            'data' => [
                $priorities['Emergency'] ?? 0,
                $priorities['Urgent'] ?? 0,
                $priorities['Normal'] ?? 0,
                $priorities['Low'] ?? 0,
            ],

            'total' => $priorities->sum(),
        ];
    }
    private function getTypechart(Request $request)
    {
        $typetiket = TicketModels::query()
            ->join(
                'ticket_types',
                'tickets.ticket_type_id',
                '=',
                'ticket_types.id'
            )
            ->when($request->start_date, function ($q) use ($request) {
                $q->whereDate('tickets.created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($q) use ($request) {
                $q->whereDate('tickets.created_at', '<=', $request->end_date);
            })
            ->selectRaw('ticket_types.name, COUNT(*) as total')
            ->groupBy('ticket_types.name')
            ->pluck('total', 'name');

        return [
            'labels' => $typetiket->keys()->toArray(),

            'data' => $typetiket->values()->toArray(),

            'total' => $typetiket->sum(),
        ];
    }

    private function getSlaStatistics(Request $request)
    {
        $query = TicketModels::query()
            ->join(
                'ticket_assignments',
                'tickets.id',
                '=',
                'ticket_assignments.ticket_id'
            )

            ->when($request->start_date, function ($q) use ($request) {
                $q->whereDate('tickets.created_at', '>=', $request->start_date);
            })

            ->when($request->end_date, function ($q) use ($request) {
                $q->whereDate('tickets.created_at', '<=', $request->end_date);
            })

            ->whereNotNull('ticket_assignments.finished_at');

        $total = (clone $query)->count();

        $ontime = (clone $query)
            ->whereColumn(
                'ticket_assignments.finished_at',
                '<=',
                'tickets.due_date'
            )
            ->count();

        $late = (clone $query)
            ->whereColumn(
                'ticket_assignments.finished_at',
                '>',
                'tickets.due_date'
            )
            ->count();

        return [
            'ontime' => $ontime,
            'late' => $late,
            'ontime_percent' => $total > 0
                ? round(($ontime / $total) * 100, 1)
                : 0,

            'late_percent' => $total > 0
                ? round(($late / $total) * 100, 1)
                : 0,

            'sla_percent' => $total > 0
                ? round(($ontime / $total) * 100, 1)
                : 0,
        ];
    }

    private function getNewTicket(Request $request)
    {
        $data = TicketModels::select('id', 'ticket_code', 'created_at', 'application_id')
            ->with(['application' => function ($query) {
                $query->select('id', 'name');
            }])
            ->whereNotin('status', ['Closed', 'Rejected'])
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();


        return [
            'data' => $data
        ];
    }

    private function getDeadlineTicket(Request $request)
    {
        $data = TicketModels::select('id', 'ticket_code', 'due_date', 'created_at', 'application_id', 'ticket_type_id', 'priority_id')
            ->with(['application' => function ($query) {
                $query->select('id', 'name');
            }])
            ->with(['priority' => function ($query) {
                $query->select('id', 'name');
            }])
            ->with(['tickettype' => function ($query) {
                $query->select('id', 'name');
            }])
            ->whereNotin('status', ['Closed', 'Rejected'])
            ->orderBy('due_date', 'DESC')
            ->limit(5)
            ->get();

        return [
            'data' => $data
        ];
    }

    private function getTechnicianPerformance(Request $request)
    {
        $data = TicketAssignmentModels::query()
            ->join('users', 'ticket_assignments.user_id', '=', 'users.id')
            ->join('tickets', 'ticket_assignments.ticket_id', '=', 'tickets.id')

            ->when($request->start_date, function ($q) use ($request) {
                $q->whereDate('ticket_assignments.created_at', '>=', $request->start_date);
            })

            ->when($request->end_date, function ($q) use ($request) {
                $q->whereDate('ticket_assignments.created_at', '<=', $request->end_date);
            })

            ->whereNotNull('ticket_assignments.finished_at')

            ->select(
                'users.id',
                'users.name',

                DB::raw('COUNT(ticket_assignments.id) as total_assignment'),

                DB::raw('AVG(TIMESTAMPDIFF(HOUR, assigned_at, finished_at)) as avg_hours'),

                DB::raw("
                SUM(
                    CASE
                        WHEN ticket_assignments.finished_at <= tickets.due_date
                        THEN 1
                        ELSE 0
                    END
                ) as ontime_count
            ")
            )

            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_assignment')
            ->get()
            ->map(function ($item) {

                $item->sla_percent = $item->total_assignment > 0
                    ? round(($item->ontime_count / $item->total_assignment) * 100, 1)
                    : 0;

                return $item;
            });

        return $data;
    }
}
