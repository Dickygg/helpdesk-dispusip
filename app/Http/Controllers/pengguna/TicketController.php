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

    // abort_if(Auth::user()->cannot('tiket.create'), 403); ikuti nama permission bukan dari names route

    public function index(Request $request)
    {
        $user = Auth::user();
        // dd($request->all());

        $data = TicketModels::with(['application', 'priority'])
            ->where('user_id', $user->id)
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('ticket_code', 'like', '%' . $request->search . '%')
                        ->orWhereHas('application', function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->orderBy('created_at', 'DESC')
            ->get();



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

            return redirect()->route('tiket.index')->with('success', 'Tiket berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal buat tiket: ' . $e->getMessage());
            dd($e);
            return redirect()->back()->withInput()->with('error', 'Oops, Gagal menyimpan tiket, silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort_if(Auth::user()->cannot('tiket.show'), 403);

        $data = TicketModels::with(['application', 'priority', 'attachments'])
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


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
