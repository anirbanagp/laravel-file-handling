<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $guarded = [];

    public function RolePermissionOf()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function RolePermissionTo()
    {
        return $this->belongsTo('App\Models\Role');
    }
}
