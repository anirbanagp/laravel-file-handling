<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\EventUserSaved;
use App\Events\EventSendAccountActivationMail;
use App\Models\UserReview;
class User extends Model
{
	use SoftDeletes,Notifiable;

	protected $dispatchesEvents = [
       // 'created' 	=> [EventSendAccountActivationMail::class, EventUserSaved::class],
   	];

	protected $guarded = [];

    public function user_profile()
    {
    	return $this->hasOne('App\Models\UserProfile');
    }

	public function parent()
	{
	    return $this->belongsTo(self::class, 'parent_id');
	}

	public function children()
	{
	    return $this->hasMany(self::class, 'parent_id');
	}

	/**
	 *  return all parent ids of current user
	 *
	 *  @return  array  ids of parent
	 */
	public function getAllParentIds()
	{
		$not_super_admin 	= 	true;
		$admin_ids			=	[];
		$current_user 		= 	$this;
		while($not_super_admin) {
			$parent = $current_user->parent()->first();
			if($parent && isset($parent->id)) {
				$admin_ids[] = $parent->id;
				$current_user = $parent;
			} else {
				$not_super_admin = false;
			}
		}
		return $admin_ids;
	}

	public function getParentSiteAdmin()
	{
		$not_site_admin 	= 	true;
		$current_user 		= 	$this;
		while($not_site_admin) {
			$parent = $current_user->parent()->first();
			if($parent && $parent->role->site_admin != "yes") {
				$not_site_admin = false;
			} else {
				$admin 			= $parent;
				$current_user 	= $parent;
			}
		}
		return $admin;
	}
	public function role()
	{
		return $this->belongsTo('App\Models\Role');
	}

}
