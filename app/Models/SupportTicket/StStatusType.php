<?php

namespace App\Models\SupportTicket;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StStatusType extends Model
{
    use SoftDeletes;

    protected $guarded 	= [];

    protected $table 	= 'stm_st_status_types';
}
