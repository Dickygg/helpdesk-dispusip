<?php

namespace App\Traits;

use App\Helpres\ActivityHelper;

trait HasActivityLog
{
    public function logcreate(array $properties = []): void
    {
        ActivityHelper::logcreate($this, $properties);
    }

    public function logupdate(array $before, array $after): void
    {
        ActivityHelper::logupdate($this, $before, $after);
    }

    public function logdelete(array $properties = []): void
    {
        ActivityHelper::logdelete($this, $properties);
    }

    // public function logView(array $properties = []): void
    // {
    //     ActivityHelper::logView($this, $properties);
    // }

    public function logStatusChange(string $dari, string $ke): void
    {
        ActivityHelper::logStatusChange($this, $dari, $ke);
    }
}
