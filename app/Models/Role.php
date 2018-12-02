<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

	public function permission()
	{
		return $this->hasMany('App\Models\Permission');
	}

	public function module()
	{
		return $this->hasManyThrough('App\Models\Module', 'App\Models\Permission');
	}
}
