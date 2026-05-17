<?php

namespace App\Models;

use App\Traits\Uuid as Uuid;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use Uuid;

    public $incrementing = false;
    protected $keyType = 'string';
}
