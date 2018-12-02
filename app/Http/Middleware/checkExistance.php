<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class checkExistance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $model, $primery_key = 'id')
    {
		if($request->{$primery_key}) {
			$data	=	$model::find($request->{$primery_key});
			if(!$data || (isset($data->status) && $data->status == 3)) {
				Session::flash('alert_class', 'danger');
				Session::flash('alert_msg', 'Invalid Operation');
				return redirect(route('admin'));
			}
			if($data) {
				$model_name	=	array_reverse(explode('\\', $model));
                $request->merge([strtolower($model_name[0]) => $data]);
			}
		}
        return $next($request);
    }
}
