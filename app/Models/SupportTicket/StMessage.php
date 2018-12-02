<?php

namespace App\Models\SupportTicket;

use Illuminate\Database\Eloquent\Model;

class StMessage extends Model
{
    protected $guarded	=	[];

    protected $table 	= 'stm_st_messages';


	public function support_ticket()
    {
    	return $this->belongsTo('App\Models\SupportTicket\SupportTicket');
    }
}
