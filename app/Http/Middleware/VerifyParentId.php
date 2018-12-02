<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Http\Traits\Rbac;
use Illuminate\Support\Facades\Session;

class VerifyParentId
{
	use Rbac;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $self_edit = false)
    {
		if($this->isLoggedIn()){
			if(!empty($request->id)) {
				$parent_ids = optional(User::find($request->id))->getAllParentIds();
				if(($self_edit && $this->user_id == $request->id )|| (is_array($parent_ids) && in_array($this->user_id, $parent_ids))) {
					return $next($request);
				}
				Session::flash('alert_class', 'danger');
				Session::flash('alert_msg', 'access denied');
				return redirect(route('admin-dashboard'));
			}
	        return $next($request);
		} else {
			Session::flash('alert_class', 'danger');
			Session::flash('alert_msg', 'Log in first');
			return redirect(route('admin-login'));
		}
    }
}
