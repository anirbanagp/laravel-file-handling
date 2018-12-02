<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use App\Http\Traits\Rbac;
use App\Models\User;

class VerifyAuthorizedAdmin
{
	use Rbac;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $relationship)
    {
		if($this->isLoggedIn($request)){
			if($request->id) {
				$split 		= 	explode("@",$relationship);
				$relation 	= 	$split[0];
				$field		=	$split[1];
				if($this->user->role->site_admin == 'no') {
					$ids = $this->user->{$relation}->pluck($field)->toArray();
					if(!in_array($request->id, $ids)) {
						Session::flash('alert_class', 'danger');
						Session::flash('alert_msg', 'Access Denied');
						return redirect(route('admin-dashboard'));
					}
				}
			}
	        return $next($request);
		} else {
			Session::flash('alert_class', 'danger');
			Session::flash('alert_msg', 'Log in first');
			return redirect(route('admin-login'));
		}

    }
}
