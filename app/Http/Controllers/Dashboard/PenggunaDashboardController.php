<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TicketModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class PenggunaDashboardController extends Controller
{


    public function index(Request $request)
    {
        abort_if(Auth::user()->cannot('dashboard.pengguna'), 403);
        $user = Auth::id();
        $getstatusstats = $this->getstastStatus($request, $user);
        $getdatanew = $this->getdata($request, $user);
        $getchartdata = $this->getTicketChartData($request, $user);
        $recentActivity = $this->getRecentActivity();
        return view('dashboard.pengguna.index', [
            'recentActivity' => $recentActivity,
            'tiketstats' => $getstatusstats['statysStats'],
            'tikettotal' => $getstatusstats['total'],
            'newtiket' => $getdatanew,
            'chartData' => $getchartdata
        ]);
    }

    private function getTicketChartData(Request $request, string $user)
    {
        $query = TicketModels::query()
            ->where('tickets.user_id', $user)
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

    private function getRecentActivity()
    {
        return Activity::with('subject')
            ->where('causer_type', 'App\\Models\\User')
            // ->where('causer_id', $userId)
            ->whereNotNull('subject_type')
            ->latest()
            ->take(5)
            ->get();
    }

    private function getstastStatus(Request $request, string $user)
    {

        $query = TicketModels::query()
            ->where('user_id', $user)

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

    private function getdata(Request $request, string $id)
    {
        return TicketModels::with(['application', 'priority', 'assignment', 'tickettype'])
            ->where('user_id', $id)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();
    }
}
