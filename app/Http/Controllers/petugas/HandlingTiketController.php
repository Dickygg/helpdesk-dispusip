<?php

namespace App\Http\Controllers\petugas;

use App\Exports\GenericExport;
use App\Helpres\ActivityHelper;
use App\Http\Controllers\admin\AssigmentController;
use App\Http\Controllers\Controller;
use App\Models\ApplicationModels;
use App\Models\AssignmentAttachmentModel;
use App\Models\TicketAssignmentModels;
use App\Models\TicketModels;
use App\Models\TicketPriorityModels;
use App\Models\TicketsTypeModels;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Models\Activity;

class HandlingTiketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::id();
        $data = $this->getdata($request, $user);
        $deadline = TicketAssignmentModels::with(['ticket', 'technician', 'admin'])
            ->where('user_id', $user)
            ->wherehas('ticket', function ($q) {
                $q->whereIn('status', ['In Progress', 'Assign', 'Reopen']);
            })
            ->whereHas('ticket', function ($q) {
                $q->whereDate('due_date', '<=', today());
            })
            ->get();

        $getassignstats = $this->gettotalAssign($request);
        $aplikasi = ApplicationModels::select('id', 'name')->get();
        $tipetiket = TicketsTypeModels::select('id', 'name')->get();

        return view('assignment.petugas.index', [
            'getassignstats' => $getassignstats,
            'aplikasi'       => $aplikasi,
            'data'           => $data,
            'deadline'       => $deadline,
            'tipetiket' => $tipetiket
        ]);
    }

    public function show(string $id)
    {
        abort_if(Auth::user()->cannot('assignment.show'), 403);
        $data = TicketAssignmentModels::with([
            'ticket.attachments',
            'ticket.priority',
            'ticket.application',
            'technician:id,name',
            'admin:id,name',
            'Assignattachments'
        ])->findOrFail($id);


        $logs = Activity::where('subject_type', TicketModels::class)
            ->where('subject_id', $data->ticket->id)
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
        abort_if(Auth::user()->cannot('assignment.prosesAssignment'), 403);
        $data = TicketAssignmentModels::with([
            'ticket.attachments',
            'ticket.priority',
            'ticket.application',
            'technician:id,name',
            'admin:id,name',
            'Assignattachments'
        ])->findOrFail($id);

        $logs = Activity::where('subject_type', TicketModels::class)
            ->where('subject_id', $data->ticket->id)
            ->with('causer')
            ->latest()
            ->get();

        return view('assignment.petugas.prosesAssignment', [
            'data' => $data,
            'logs' => $logs
        ]);
    }

    public function startWork(string $id)
    {
        abort_if(Auth::user()->cannot('assignment.StartWork'), 403);

        $data = TicketAssignmentModels::with('ticket')->findOrFail($id);
        $oldStatus = $data->ticket->status;
        DB::beginTransaction();
        try {
            $data->update([
                'started_at' => now()
            ]);
            $data->ticket->update([
                'status' => 'In Progress'
            ]);

            $tiket = TicketModels::findOrFail($data->ticket->id);
            ActivityHelper::logUpdate(
                $tiket,
                before: ['status' => $oldStatus],
                after: ['status' => $tiket->status],
            );
            DB::commit();
            return redirect()->back()->with('success', 'Berhasil Mengubah Status Menjadi In Progress, Segera Selesai Kan Assignment!.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->with('error', 'Oops, Gagal Mengubah Status Menjadi In Progress!.');
        }
    }

    public function finishWork(Request $request, string $id)
    {
        abort_if(Auth::user()->cannot('assignment.finishWork'), 403);

        $data = TicketAssignmentModels::with('ticket')->findOrFail($id);

        if ($data->ticket?->status == 'Resolved' || $data->ticket?->status == 'Closed') {
            return redirect()->back()->with('error', 'Oops, Anda Sudah Menyelesaikan Tiket!.');
        };

        $oldStatus = $data->ticket->status;

        if (!$data->started_at) {
            return redirect()->back()->with('error', 'Oops, Anda Belum Mulai Mengerjakan Tiket!.');
        }

        $request->validate([
            'note' => 'required|string',
            'file' => 'required|nullable|mimes:' . config('upload.file.accept_name.image') . '|max:' . config('upload.file.max.image'),
        ], [
            'note.required' => 'Catatan tidak boleh kosong.',
            'file.required' => 'File tidak boleh kosong.',
            'file.mimes'    => 'Ekstensi file harus ' . config('upload.file.accept_name.image') . '.',
            'file.max'      => 'Ukuran file maksimal ' . config('upload.file.max.image') . ' KB.',
        ]);

        $user = Auth::user();
        $path = config('upload.file.path.assignment');

        DB::beginTransaction();
        try {
            // hitung work_duration dari started_at sampai sekarang (dalam menit)
            $finishedAt   = now();
            $workDuration = $data->started_at
                ? \Carbon\Carbon::parse($data->started_at)->diffInMinutes($finishedAt)
                : null;

            $data->update([
                'finished_at'     => $finishedAt,
                'work_duration' => $workDuration,
            ]);

            $data->ticket->update([
                'status' => 'Resolved',
                'note' => $request->note,
            ]);

            $filename = null;
            $fullPath = null;
            if ($request->hasFile('file')) {
                $filename = $data->id . '_' . time() . '.' . $request->file->getClientOriginalExtension();
                $fullPath = $path . '/' . $filename;
                $request->file->storeAs($path, $filename, 'public'); // simpan ke storage
            }

            //  simpan catatan + file ke assignment_attachments
            AssignmentAttachmentModel::create([
                'ticket_assignment_id' => $data->id,
                'uploaded_by'       => $user->id,
                'file_path'     => $fullPath,
                'file_name'     => $filename,
                'file_type'     => $request->hasFile('file') ? $request->file->getClientOriginalExtension() : null,
            ]);

            $data->ticket->refresh();

            ActivityHelper::logUpdate(
                $data->ticket,
                before: ['status' => $oldStatus],
                after: ['status' => 'Resolved'],
            );

            DB::commit();
            return redirect()->back()->with('success', 'Berhasil Menyelesaikan Tiket, Menunggu Pengguna Konfirmasi!.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            dd($e);
            return redirect()->back()->with('error', 'Oops, Gagal Menyelesaikan Tiket!');
        }
    }

    public function historyAssignment(Request $request)
    {
        abort_if(Auth::user()->cannot('assignment.history'), 403);

        $user = Auth::id();


        $data = $this->getdatahistory($request, $user);
        $aplikasi = ApplicationModels::select('id', 'name')->get();
        $prioritas = TicketPriorityModels::select('id', 'name')->get();
        $getstats = $this->gethistroystats($request);
        $tipetiket = TicketsTypeModels::select('id', 'name')->get();

        return view('assignment.petugas.history', [
            'getassignstats' => $getstats,
            'aplikasi'       => $aplikasi,
            'data'           => $data,
            'prioritas' => $prioritas,
            'tipetiket' => $tipetiket
        ]);
    }

    public function export(Request $request)
    {
        $user = Auth::id();
        $assignment = $this->getdata($request, $user);
        $data = $assignment->map(function ($assignment) {
            return [
                'Kode Tiket' => $assignment->ticket?->ticket_code,
                'Diassign Oleh' => $assignment->admin?->name,
                'Tipe Tiket' => $assignment->ticket?->tickettype?->name,
                'Aplikasi' => $assignment->ticket?->application?->name,
                'Prioritas' => $assignment->ticket?->priority?->name,
                'Status' => $assignment->ticket?->status,
                'Durasi Pengerjaan' => $assignment->formattedWorkDuration() ?? '-',
                'Pengguna Konfirmasi' => $assignment->ticket?->user_confirmed_at?->format('d-m-Y'),
                'Deadline' => $assignment->ticket?->due_date?->format('d-m-Y '),
            ];
        });

        return Excel::download(
            new GenericExport($data),
            Auth::user()->name . '-' . $this->generateFileName($request)
        );
    }
    public function exporthistory(Request $request)
    {
        $user = Auth::id();
        $assignment = $this->getdatahistory($request, $user);
        $data = $assignment->map(function ($assignment) {
            return [
                'Kode Tiket' => $assignment->ticket?->ticket_code,
                'Diassign Oleh' => $assignment->admin?->name,
                'Tipe Tiket' => $assignment->ticket?->tickettype?->name,
                'Aplikasi' => $assignment->ticket?->application?->name,
                'Prioritas' => $assignment->ticket?->priority?->name,
                'Status' => $assignment->ticket?->status,
                'Durasi Pengerjaan' => $assignment->formattedWorkDuration() ?? '-',
                'Pengguna Konfirmasi' => $assignment->ticket?->user_confirmed_at?->format('d-m-Y'),
                'Deadline' => $assignment->ticket?->due_date?->format('d-m-Y '),
                'Ditutup Pada' => $assignment->ticket?->closed_at?->format('d-m-Y')
            ];
        });

        return Excel::download(
            new GenericExport($data),
            'History-' . Auth::user()->name . '-' . $this->generateFileName($request)
        );
    }

    private function getdata(Request $request, string $user)
    {
        return TicketAssignmentModels::with([
            'ticket',
            'technician:id,username',
            'admin:id,username'
        ])
            ->where('ticket_assignments.user_id', $user)
            ->whereHas('ticket', function ($q) {
                $q->whereNotIn('status', ['Closed']);
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
            })
            ->when($request->ticket_type_id, function ($q) use ($request) {
                $q->whereHas('ticket', function ($q) use ($request) {
                    $q->where('ticket_type_id', $request->ticket_type_id);
                });
            })
            ->get();
    }

    private function getdatahistory(Request $request, string $user)
    {
        return TicketAssignmentModels::with([
            'ticket',
            'technician:id,username',
            'admin:id,username'
        ])
            ->where('ticket_assignments.user_id', $user)
            ->whereHas('ticket', function ($q) {
                $q->whereIn('status', ['Closed']);
            })
            // Filter tanggal
            ->when($request->start_date, fn($q) => $q->whereDate('ticket_assignments.created_at', '>=', $request->start_date))
            ->when($request->end_date,   fn($q) => $q->whereDate('ticket_assignments.created_at', '<=', $request->end_date))
            // Filter aplikasi
            ->when($request->id_aplikasi, fn($q) => $q->whereHas('ticket', fn($q) => $q->where('application_id', $request->id_aplikasi)))
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
        if ($request->filled('id_priority')) {
            $priority = TicketPriorityModels::find($request->id_priority);
            $filename .= '-' . $priority->name;
        }

        // Timestamp export
        $filename .= '-' . now()->format('Ymd-His');

        return $filename . '.xlsx';
    }

    private function gettotalAssign(Request $request)
    {
        $user = Auth::id();

        $query = TicketAssignmentModels::query()
            ->where('ticket_assignments.user_id', $user)
            ->whereHas('ticket', function ($q) {
                $q->whereNotIn('status', ['closed']); // sesuaikan value
            })
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
            })
            ->when($request->ticket_type_id, function ($q) use ($request) {
                $q->whereHas('ticket', function ($q) use ($request) {
                    $q->where('ticket_type_id', $request->ticket_type_id);
                });
            });

        $AssignStats = (clone $query)
            ->select('tickets.status', DB::raw('COUNT(*) as total'))
            ->join('tickets', 'ticket_assignments.ticket_id', '=', 'tickets.id')
            ->whereIn('tickets.status', ['Resolved', 'In Progress', 'Reopen'])
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
        $Reopen = $AssignStats->firstWhere('status', 'Reopen');

        return [
            'assignstats'     => $AssignStats,
            'assigntotal'     => $AssignTotal,
            'total_selesai'   => $selesai->total ?? 0,
            'total_diproses'  => $diproses->total ?? 0,
            'total_reopen'  => $Reopen->total ?? 0,
            'menuju_deadline' => $menujuDeadline,
            'overDuetime' => $overDuetime,
        ];
    }


    private function gethistroystats(Request $request)
    {
        $user = Auth::id();

        $query = TicketAssignmentModels::query()
            ->where('ticket_assignments.user_id', $user)
            ->whereHas('ticket', function ($q) {
                $q->whereIn('status', ['closed']);
            })
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
