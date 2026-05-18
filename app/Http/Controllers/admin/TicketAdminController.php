<?php

namespace App\Http\Controllers\admin;

use App\Helpres\ActivityHelper;
use App\Http\Controllers\Controller;
use App\Models\TicketAssignmentModels;
use App\Models\TicketModels;
use App\Models\TicketPriorityModels;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Activitylog\Models\Activity;

class TicketAdminController extends Controller
{


    public function index(Request $request)
    {
        // 'application', 'priority', 

        $data = TicketModels::with(['user' => function ($query) {
            $query->select('id', 'name');
        }])
            ->with(['application' => function ($query) {
                $query->select('id', 'name');
            }])
            ->with(['priority' => function ($query) {
                $query->select('id', 'name');
            }])
            ->whereNotin('status', ['Closed', 'Rejected'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'DESC')
            ->get();


        $getTiketstats = $this->getTotalStatus();

        return view('tiket.admin.index', [
            'tickets' => $data,
            'tiketstats' => $getTiketstats['tiketstats'],      // langsung collection-nya kara tidak ingin di loop di view
            'tikettotal' => $getTiketstats['tikettotal']
        ]);
    }

    public function show(string $id)
    {
        $data = TicketModels::with(['application', 'priority', 'attachments'])
            ->where('id', $id)
            ->first();

        $logs = Activity::where('subject_type', TicketModels::class)
            ->where('subject_id', $data->id)
            ->with('causer')
            ->latest()
            ->get();

        return view('tiket.admin.showAdmindetail', [
            'tiket' => $data,
            'logs' => $logs
        ]);
    }

    public function SiteprosesTiket(string $id)
    {
        abort_if(Auth::user()->cannot('tiket.admin.proses'), 403);

        $data = TicketModels::with(['application', 'priority', 'attachments', 'assignment.technician'])
            ->where('id', $id)
            ->first();

        $logs = Activity::where('subject_type', TicketModels::class)
            ->where('subject_id', $data->id)
            ->with('causer')
            ->latest()
            ->get();
        $piority = TicketPriorityModels::select('id', 'name', 'estimated_hours')
            ->orderBy('estimated_hours', 'desc')
            ->get();

        $petugas = $petugas = User::select('id', 'username')
            ->role('petugas teknis')
            ->get();

        return view('tiket.admin.prosesTiket', [
            'tiket' => $data,
            'logs' => $logs,
            'piority' => $piority,
            'petugas' => $petugas
        ]);
    }

    public function verificationAdmin(string $id, Request $request)
    {
        abort_if(Auth::user()->cannot('tiket.admin.verification'), 403);

        $rules = [
            'priority_id' => 'required'
        ];

        $messages = [
            'priority_id.required' => 'Mohon Tentukan Pioritas Tiket Terlebih Dahulu!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tiket = TicketModels::findOrFail($id);

        if (!$tiket) {
            abort(404);
        }
        $oldStatus = $tiket->status;

        DB::beginTransaction();
        try {
            $tiket->update([
                'status' => 'Accept',
                'verification_status' => 'verified',
                'priority_id' => $request->priority_id,
                'admin_verified_at' => now()
            ]);

            ActivityHelper::logUpdate(
                $tiket,
                before: ['status' => $oldStatus],
                after: ['status' => $tiket->status],
            );
            DB::commit();
            return redirect()->back()->with('success', 'Tiket telah Berhail Diverifikasi Dan Ditentukan Pioritas.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->with('error', 'Oops, Gagal Memperbarui Data!.');
        }
    }

    public function rejectVerificationAdmin(Request $request, string $id)
    {
        abort_if(Auth::user()->cannot('tiket.admin.rejected'), 403);

        $rules = [
            'note' => 'required'
        ];

        $messages = [
            'note.required' => 'Mohon Berikan Alasan Penolakan!.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $tiket = TicketModels::findOrFail($id);
        if (!$tiket) {
            abort(404);
        }
        $oldStatus = $tiket->status;
        DB::beginTransaction();
        try {
            $tiket->update([
                'status' => 'Rejected',
                'verification_status' => 'rejected',
                'note' => $request->note
            ]);

            ActivityHelper::logUpdate(
                $tiket,
                before: ['status' => $oldStatus],
                after: ['status' => $tiket->status],
            );
            DB::commit();
            return redirect()->back()->with('succes', 'Tiket telah ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->with('error', 'Oops, Gagal Memperbarui Data!.');
        }
    }

    public function updatePiorityTiket(Request $request, string $id)
    {
        abort_if(Auth::user()->cannot('tiket.admin.Updatepriority'), 403);

        $tiket = TicketModels::findOrFail($id);
        $rules = [
            'priority_id' => [
                'required',
                Rule::notIn([$tiket->priority_id])
            ]
        ];


        $messages = [
            'priority_id.required' => 'Mohon Tentukan Pioritas Tiket Terlebih Dahulu!',
            'priority_id.not_in' => 'Tidak bisa memilih prioritas yang sama dengan sekarang!'

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $newpriority = TicketPriorityModels::findOrFail($request->priority_id);
        $oldpiority = $tiket->priority?->name;
        if (!$tiket) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $tiket->update([
                'priority_id' => $request->priority_id
            ]);

            ActivityHelper::logUpdate(
                $tiket,
                before: ['Pioritas' => $oldpiority],
                after: ['Pioritas' => $newpriority->name],
            );

            DB::commit();
            return redirect()->back()->with('success', 'Pioritas Tiket telah Ditingkatkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->with('error', 'Oops, Gagal Memperbarui Data!.');
        }
    }

    public function closeTiket(string $id)
    {
        abort_if(Auth::user()->cannot('tiket.admin.closeTiket'), 403);

        $tiket = TicketModels::findOrFail($id);
        if (!$tiket) {
            abort(404);
        }

        if (!$this->cekUserverified($tiket)) {
            return redirect()->back()->with('error', 'Oops, Pengguna Belum ConfirmasiTiket!.');
        }

        if ($tiket->status !== 'Resolved') {
            return redirect()->back()->with('error', 'Oops, Status Tiket Belum Resolved!.');
        }


        $oldStatus = $tiket->status;
        DB::beginTransaction();
        try {
            $tiket->update([
                'status' => 'Closed',
            ]);
            ActivityHelper::logUpdate(
                $tiket,
                before: ['status' => $oldStatus],
                after: ['status' => $tiket->status],
            );
            DB::commit();
            return redirect()->back()->with('success', 'Tiket Berhasil DiClosed!.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->with('error', 'Oops, Tiket Tidak Berhasil diClosed!.');
        }
    }

    private function cekUserverified(TicketModels $tiket)
    {
        if (is_null($tiket->user_confirmed_at)) return false;

        return [
            'status' => true,
            'message' => 'Pengguna Belum Verifikasi Tiket!.'
        ];
    }

    private function getTotalStatus()
    {
        $tiketStats = TicketModels::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $tiketTotal = $tiketStats->sum('total');

        return [
            'tiketstats' => $tiketStats,
            'tikettotal' => $tiketTotal
        ];
    }
}
