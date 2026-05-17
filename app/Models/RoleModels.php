<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleModels extends BaseModel
{
    protected $table = "master_roles";

    protected $fillable = ['roles_name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
