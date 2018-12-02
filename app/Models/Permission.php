<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public function module()
    {
        return $this->belongsTo('App\Models\Module');
    }

	public function role()
	{
		return $this->belongsTo('App\Models\Role');
	}
}
