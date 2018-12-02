<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $guarded = [];

    public function subscriber()
    {
        return $this->hasMany('App\Models\Subscriber');
    }

}
