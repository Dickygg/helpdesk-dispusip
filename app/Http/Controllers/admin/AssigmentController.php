<?php

namespace App\Http\Controllers\admin;

use App\Helpres\ActivityHelper;
use App\Http\Controllers\Controller;
use App\Models\TicketAssignmentModels;
use App\Models\TicketModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AssigmentController extends Controller
{
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
        $oldStatus = $tiket->status;
        DB::beginTransaction();
        try {
            TicketAssignmentModels::create([
                'ticket_id' => $id,
                'user_id' => $request->user_id,
                'assigned_by' => Auth::user()->id,
                'assigned_at' => now()
            ]);
            $tiket->update([
                'status' => 'Assigned'
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
}
