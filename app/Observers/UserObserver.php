<?php

namespace App\Observers;

use App\Models\User;
use App\Events\EventUserSaved;
use App\Events\EventSendAccountActivationMail;

class UserObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        EventSendAccountActivationMail::class;
		EventUserSaved::class;
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function saved(User $user)
    {
        EventUserSaved::class;
    }
}
