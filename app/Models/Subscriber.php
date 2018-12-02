<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $guarded = [];

    public function subscriptionDetail()
    {
        return $this->hasOne('App\Models\SubscriptionDetail');
    }
    public function subscriptionLog()
    {
        return $this->hasMany('App\Models\SubscriptionLog');
    }
    public function subscriptionPlan()
    {
        return $this->belongsTo('App\Models\SubscriptionPlan');
    }

}
