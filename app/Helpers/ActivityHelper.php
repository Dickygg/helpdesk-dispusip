<?php

namespace App\Helpres;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Cast\Void_;

class ActivityHelper
{


    public static function log(Model $model, string $description, array $properties = []): void
    {
        activity()
            ->performedOn($model)
            ->causedBy(Auth::user())
            ->withProperties($properties)
            ->log($description);
    }

    public static function logcreate(Model $model, array $properties): void
    {
        self::log($model, 'Data Dibuat', $properties);
    }

    public static function logupdate(Model $model, array $before, array $after): void
    {
        self::log($model, 'Data Ticket Diperbarui', [
            'before' => $before,
            'after' => $after,
        ]);
    }
    public static function logdelete(Model $model, array $properties = []): void
    {
        self::log($model, 'Data dihapus', $properties);
    }
    public static function logStatusChange(Model $model, string $dari, string $ke): void
    {
        self::log($model, "Status Tiket diubah dari {$dari} ke {$ke}", [
            'dari' => $dari,
            'ke'   => $ke,
        ]);
    }
    public static function logAssign(Model $model, array $before, array $after): void
    {
        self::log($model, 'Tiket Diassign', [
            'before' => $before,
            'after' => $after,
        ]);
    }
}
