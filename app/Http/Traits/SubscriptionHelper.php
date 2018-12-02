<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\Subscriber;

/**
 * this will contain all functionalities related to subscription
 *
 * @author Anirban Saha
 */
trait SubscriptionHelper
{
    public function logSubscription(Subscriber $subscriber)
    {
        $details	=	[];
        $details['active_user_count']	=	$subscriber->subscriptionPlan->active_user_count;
        $details['expires_on']			=	Carbon::today()->addDays($subscriber->subscriptionPlan->tenure_in_days)->format('Y-m-d');
        $subscriber->subscriptionDetail()->create($details);
        $log_details = 'subscribe crm with '.$subscriber->subscriptionPlan->plan_name.' for '.$subscriber->subscriptionPlan->active_user_count .' users upto '.$details['expires_on'];
        $subscriber->subscriptionLog()->create(['details' => $log_details]);
    }

    public function updateSubscriptionDetails($value='')
    {
        // code...
    }
}
