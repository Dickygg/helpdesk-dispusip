<?php

namespace App\Http\Controllers\petugas;

use App\Helpres\ActivityHelper;
use App\Http\Controllers\admin\AssigmentController;
use App\Http\Controllers\Controller;
use App\Models\ApplicationModels;
use App\Models\AssignmentAttachmentModel;
use App\Models\TicketAssignmentModels;
use App\Models\TicketModels;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;
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
            'admin:id,name',
            'Assignattachments'
        ])->findOrFail($id);


        $logs = Activity::where('subject_type', TicketModels::class)
            ->where('subject_id', $data->ticket->id)
            ->with('causer')
            ->latest()
            ->take(4)
            ->get();



        return view('assignment.petugas.detail', [
            'data' => $data,
            'logs' => $logs
        ]);
    }
    public function prosesAssignment(string $id)
    {
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
            ->take(3)
            ->get();

        return view('assignment.petugas.prosesAssignment', [
            'data' => $data,
            'logs' => $logs
        ]);
    }

    public function startWork(string $id)
    {
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
        $data = TicketAssignmentModels::with('ticket')->findOrFail($id);
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
                $request->file->storeAs($path, $filename, 'public'); // ✅ simpan ke storage
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
