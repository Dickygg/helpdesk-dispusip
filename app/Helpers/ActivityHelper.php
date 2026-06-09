<?php

namespace App\Helpres;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\AssignOp\Mod;
use PhpParser\Node\Expr\Cast\Void_;

class ActivityHelper
{


    public static function log(Model $model, string $description, string $event, array $properties = []): void
    {
        activity()
            ->performedOn($model)
            ->causedBy(Auth::user())
            ->event($event)
            ->withProperties($properties)
            ->log($description);
    }

    public static function logcreate(Model $model, array $properties): void
    {
        self::log($model, 'Tiket Dibuat', 'Created', $properties);
    }

    public static function logverifikasi(Model $model, array $properties): void
    {
        self::log($model, 'Tiket Sudah Diverifikasi', 'Verifikasi Tiket', $properties);
    }

    public static function logrejectsverifikasi(Model $model, array $properties): void
    {
        self::log($model, 'Tiket Ditolak Oleh Admin', 'Menolak Tiket', $properties);
    }

    public static function logupdate(Model $model, array $before, array $after): void
    {
        self::log($model, 'Data Ticket Diperbarui', 'Updated', [
            'before' => $before,
            'after' => $after,
        ]);
    }

    public static function logdelete(Model $model, array $properties = []): void
    {
        self::log($model, 'Data Dihapus', 'Delete', $properties);
    }

    public static function logStatusChange(Model $model, array $before, array $after): void
    {
        $oldStatus = $before['status'] ?? '-';
        $newStatus = $after['status'] ?? '-';

        self::log(
            $model,
            "Status Tiket diubah dari {$oldStatus} ke {$newStatus}",
            'Updated',
            [
                'before' => $before,
                'after' => $after,
            ]
        );
    }

    public static function logAssign(Model $model, array $before, array $after): void
    {
        self::log($model, 'Tiket Diassign', 'Assigned', [
            'before' => $before,
            'after' => $after,
        ]);
    }

    public static function logstartwork(Model $model): void
    {
        self::log(
            $model,
            'Petugas Mulai Mengerjakan Tiket',
            'Mulai Penanganan',
        );
    }

    public static function logfinishwork(Model $model, array $properties): void
    {
        self::log(
            $model,
            'Petugas Menyelesaikan Pekerjaan Tiket',
            'Selesai Penanganan',
            $properties

        );
    }

    public static function logkonfrimasipengguna(Model $model): void
    {
        self::log(
            $model,
            'Pengguna Mengkonfirmasi Penyelesaian Tiket',
            'Konfirmasi Pengguna',

        );
    }
    public static function logkonfrimasiotomatis(Model $model): void
    {
        self::log(
            $model,
            'Sistem Otomatis Mengkonfirmasi Tiket Sesuai Ketentuan',
            'Konfirmasi Otomatis',

        );
    }
    public static function logrejectkonfrimasipengguna(Model $model, array $properties): void
    {
        self::log(
            $model,
            'Pengguna Menolak Konfirmasi Akhir',
            'Tolak Konfirmasi',
            $properties
        );
    }

    public static function logclosedtiket(Model $model): void
    {
        self::log(
            $model,
            'Tiket Ditutup',
            'Updated',
        );
    }
}
