<?php

namespace App\Http\Controllers\admin;

use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;
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
        $assignment = $this->getdata($request);
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

        $data = $this->getdatahistory($request);
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

    public function export(Request $request)
    {
        $assignment = $this->getdata($request);
        $data = $assignment->map(function ($assignment) {
            return [
                'Kode Tiket' => $assignment->ticket?->ticket_code,
                'Petugas' => $assignment->technician?->name,
                'Tipe Tiket' => $assignment->ticket?->tickettype?->name,
                'Aplikasi' => $assignment->ticket?->application?->name,
                'Prioritas' => $assignment->ticket?->priority?->name,
                'Status' => $assignment->ticket?->status,
                'Durasi Pengerjaan' => $assignment?->formattedWorkDuration() ?? '-',
                'Deadline' => $assignment->ticket?->due_date->format('d-m-Y '),
            ];
        });

        return Excel::download(
            new GenericExport($data),
            $this->generateFileName($request)
        );
    }
    public function exporthistory(Request $request)
    {
        $assignment = $this->getdatahistory($request);
        $data = $assignment->map(function ($assignment) {
            return [
                'Kode Tiket' => $assignment->ticket?->ticket_code,
                'Petugas' => $assignment->technician?->name,
                'Tipe Tiket' => $assignment->ticket?->tickettype?->name,
                'Aplikasi' => $assignment->ticket?->application?->name,
                'Prioritas' => $assignment->ticket?->priority?->name,
                'Status' => $assignment->ticket?->status,
                'Ditutup Tanggal' => $assignment->ticket?->closed_at?->format('d-m-Y '),
                'Durasi Pengerjaan' => $assignment?->formattedWorkDuration() ?? '-',
            ];
        });

        return Excel::download(
            new GenericExport($data),
            'History-' . $this->generateFileName($request)
        );
    }

    private function getdata(Request $request)
    {
        return TicketAssignmentModels::with(['ticket', 'technician', 'admin'])
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
    }

    private function getdatahistory(Request $request)
    {
        return TicketAssignmentModels::with([
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
            })
            ->get();
    }

    private function generateFileName(Request $request)
    {
        $filename = 'Data-Assignment';

        // Status
        if ($request->filled('status')) {
            $filename .= '-' . $request->status;
        }

        // Aplikasi
        if ($request->filled('id_aplikasi')) {
            $app = ApplicationModels::find($request->id_aplikasi);

            if ($app) {
                $filename .= '-' . str_replace(' ', '-', $app->name);
            }
        }

        // Tipe Tiket
        if ($request->filled('ticket_type_id')) {
            $type = TicketsTypeModels::find($request->ticket_type_id);

            if ($type) {
                $filename .= '-' . str_replace(' ', '-', $type->name);
            }
        }

        // Deadline Filter
        if ($request->filled('deadline_filter')) {
            $filename .= '-' . ucfirst($request->deadline_filter);
        }

        // Rentang Tanggal
        if ($request->filled('start_date')) {
            $filename .= '-' . $request->start_date;
        }

        if ($request->filled('end_date')) {
            $filename .= '_sd_' . $request->end_date;
        }
        if ($request->filled('id_petugas')) {
            $petugas = User::find($request->id_petugas);
            $filename .= '-' . $petugas->name;
        }
        if ($request->filled('id_priority')) {
            $priority = TicketPriorityModels::find($request->id_priority);
            $filename .= '-' . $priority->name;
        }

        // Timestamp export
        $filename .= '-' . now()->format('Ymd-His');

        return $filename . '.xlsx';
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
