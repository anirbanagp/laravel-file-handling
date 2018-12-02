<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionDetail extends Model
{
    protected $guarded = [];

    public function subscriber()
    {
        return $this->belongsTo('App\Models\Subscriber');
    }

}
