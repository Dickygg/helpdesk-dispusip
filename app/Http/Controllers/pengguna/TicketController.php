<?php

namespace App\Http\Controllers\pengguna;

use App\Http\Controllers\Controller;
use App\Helpres\ActivityHelper;
use App\Models\ApplicationModels;
use App\Models\AttachmentModels;
use App\Models\TicketModels;
use App\Models\TicketStatusModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Ticket;
use Spatie\Activitylog\Models\Activity;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // abort_if(Auth::user()->cannot('data.create'), 403); ikuti nama permission bukan dari names route

    public function index(Request $request)
    {
        $user = Auth::user();
        // dd($request->all());
        $data = TicketModels::with(['application', 'priority', 'assignment', 'tickettype'])
            ->where('user_id', $user->id)
            ->whereNotIn('status', ['Closed', 'Rejected'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) { // ← bungkus dengan where()
                    $query->where('ticket_code', 'like', '%' . $request->search . '%')
                        ->orWhere('title', 'like', '%' . $request->search . '%')
                        ->orWhereHas('application', function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->search . '%');
                        })
                        ->orWhereHas('tickettype', function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(4)
            ->appends($request->query());


        return view('tiket.pengguna.index', [
            'tikets' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Auth::user()->cannot('tiket.create'), 403);
        $aplikasi = ApplicationModels::select('id', 'name')->get();

        return view('tiket.pengguna.createTicket', [
            'aplikasi' => $aplikasi
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Auth::user()->cannot('tiket.store'), 403);
        $request->validate([
            'title'       => 'required|min:3|max:50',
            'description' => 'required',
            'id_aplikasi' => 'required|exists:applications,id', // pastikan aplikasi ada di DB
            'file'        => 'required|mimes:' . config('upload.file.accept_name.image') . '|max:' . config('upload.file.max.image'),
        ], [
            'title.required'       => 'Judul tiket tidak boleh kosong.',
            'title.min'            => 'Judul minimal 3 huruf.',
            'title.max'            => 'Judul maksimal 50 huruf.',
            'description.required' => 'Deskripsi tiket tidak boleh kosong.',
            'id_aplikasi.required' => 'Aplikasi tidak boleh kosong.',
            'id_aplikasi.exists'   => 'Aplikasi tidak ditemukan.',
            'file.required'        => 'File belum diisi.',
            'file.mimes'           => 'Ekstensi file harus ' . config('upload.file.accept_name.image') . '.',
            'file.max'             => 'Ukuran file maksimal ' . config('upload.file.max.image') . ' KB.',
        ]);

        $user     = Auth::user();
        $aplikasi = ApplicationModels::findOrFail($request->id_aplikasi);
        $path     = config('upload.file.path.' . strtolower($aplikasi->name));

        DB::beginTransaction();
        try {
            // Buat tiket
            $tiket = TicketModels::create([
                'user_id'        => $user->id,
                'application_id' => $aplikasi->id,
                'title'          => $request->title,
                'description'    => $request->description,
            ]);

            // Handle file
            $filename = $tiket->id . '.' . $request->file->getClientOriginalExtension();
            $request->file->storeAs($path, $filename, 'public');

            // Simpan attachment
            AttachmentModels::create([
                'ticket_id'   => $tiket->id,
                'uploaded_by' => $user->id,
                'file_path'   => $path,
                'file_name'   => $filename,
                'file_type'   => $request->file->getClientOriginalExtension(),
            ]);

            ActivityHelper::logCreate($tiket, [
                'Ticket_Code' => $tiket->ticket_code,
                'Aplikasi'    => $aplikasi->name,
                'Title'       => $tiket->title,
            ]);

            DB::commit();

            // Kirim notifikasi telegram
            sendTelegram(
                "📢 *Tiket Baru*\n" .
                    "⚡ Code Tiket: {$tiket->ticket_code}\n" .
                    "👤 Pembuat: {$user->name}\n" .
                    "📝 Judul: {$tiket->title}\n" .
                    "🖥 Aplikasi: {$aplikasi->name}\n" .
                    "📅 Tanggal: {$tiket->created_at}\n"
            );

            $role = $user->roles->first()->name; // ← ambil nama role dulu

            return match ($role) {
                'super admin' => redirect()->route('sa.tiket.index')->with('success', 'Tiket berhasil dibuat!'),
                'admin'       => redirect()->route('admin.tiket.index')->with('success', 'Tiket berhasil dibuat!'),
                default       => redirect()->route('tiket.index')->with('success', 'Tiket berhasil dibuat!'),
            };
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal buat tiket: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Oops, Gagal menyimpan tiket, silakan coba lagi.');
        }
    }

    public function show(string $id)
    {
        abort_if(Auth::user()->cannot('tiket.show'), 403);

        $data = TicketModels::with(['application', 'priority', 'attachments', 'assignment.Assignattachments', 'tickettype'])
            ->where('id', $id)
            ->first();

        $logs = Activity::where('subject_type', TicketModels::class)
            ->where('subject_id', $data->id)
            ->with('causer')
            ->latest()
            ->get();

        return view('tiket.pengguna.showdetail', [
            'tiket' => $data,
            'logs' => $logs
        ]);
    }


    public function Konfirmasi(string $id)
    {
        $data = TicketModels::findOrFail($id);
        DB::beginTransaction();
        try {
            $data->update([
                'user_confirmed_at' => now()
            ]);
            $data->refresh();
            ActivityHelper::logUpdate(
                $data,
                before: ['Tiket' => 'Pengguna Belum Konfirmasi'],
                after: ['Tiket' => 'Pengguna Sudah Konfirmasi'],
            );
            DB::commit();
            sendTelegram(
                "📢 *Pemberitahuan Tiket*\n" .
                    "⚡ Code Tiket: {$data->ticket_code}\n" .
                    "📢 Pemberitahuan: Pengguna Sudah Mengkonfirmasi Tiket, Segera Tutup Tiket.!.\n"
            );
            return redirect()->back()->with('success', 'Tiket berhasil diKonfirmasi!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            dd($e);
            return redirect()->back()->withInput()->with('error', 'Oops, Gagal Konfirmasi tiket, silakan coba lagi.');
        }
    }

    public function rejectedKonfirmasi(Request $request, string $id)
    {
        $data = TicketModels::findOrFail($id);

        $request->validate([
            'reason_rejected' => 'required'
        ], [
            'reason_rejected.required' => 'Harap Memberikan Alasan Penolakan!'
        ]);

        $oldstatus = $data->status;
        DB::beginTransaction();
        try {
            $data->update([
                'reason_rejected' => $request->reason_rejected,
                'status' => 'Reopen'
            ]);

            ActivityHelper::logUpdate(
                $data,
                before: ['Tiket' => 'Pengguna Belum Konfirmasi'],
                after: ['Tiket' => 'Pengguna Menolak Konfirmasi'],
            );
            DB::commit();
            sendTelegram(
                "📢 *Pemberitahuan Tiket*\n" .
                    "⚡ Code Tiket: {$data->ticket_code}\n" .
                    "📢 Pemberitahuan: Pengguna Menolak Konfrimasi Tiket.!.\n"
            );
            return redirect()->route('tiket.index')->with('success', 'Tiket berhasil ditolak!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            dd($e);
            return redirect()->route('tiket.index')->with('error', 'Oops, Gagal Mengubah Status Konfirmasi!');
        }
    }

    // riwayat tiket section
    public function historyTicket(Request $request)
    {
        $user = Auth::user();
        $data = TicketModels::with(['application', 'priority', 'assignment'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['Closed', 'Rejected'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) { // ← bungkus dengan where()
                    $query->where('ticket_code', 'like', '%' . $request->search . '%')
                        ->orWhere('title', 'like', '%' . $request->search . '%')
                        ->orWhereHas('application', function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(4)
            ->appends($request->query());


        return view('tiket.pengguna.history', [
            'tikets' => $data,
        ]);
    }
}
