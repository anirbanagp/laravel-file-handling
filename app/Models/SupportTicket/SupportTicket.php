<?php

namespace App\Models\SupportTicket;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\EventSendTicketClosureMail;

class SupportTicket extends Model
{
    use SoftDeletes;

    protected $guarded 	= [];

    protected $table 	= 'stm_support_tickets';

    protected $dispatchesEvents = [
       'saved' => EventSendTicketClosureMail::class,
   	];


    /**
	 *  A sport has many leagues
	 *
	 *  @return  object  $this
	 */
	public function stMessage()
	{
		return $this->hasMany('App\Models\SupportTicket\StMessage');
	}
}
