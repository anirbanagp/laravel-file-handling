<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\User;
use App\TGUser;
use App\LtmTranslation;
use App\Models\Restaurant;

/**
 *  this controller will do several function
 *
 *  @author Anirban Saha
 */
class Tools extends Controller
{
	public function __construct()
	{
		ini_set('max_execution_time', 0);
		ini_set('memory_limit','1000M');
	}
	/**
	 * this will check an unique field
	 *
	 * @param  Request $request request object
	 * @return bool           1 for not exist, 0 for exist
	 */
    public function postCheckUnique(Request $request)
    {
    	$field = $request->field;
    	$value = $request->value;
    	$id = $request->id;
    	$table = $request->table ? $request->table : 'users';
		$checking = DB::table($table)->where($field,$value)->get();
		if(isset($checking[0]) && $checking[0]->id != $id){
			echo 0;
		}else{
			echo 1;
		}
    }
	/**
	 * this will change site language and set into Session
	 *
	 * @param  Request $request request object
	 * @return void
	 */
	public function postChangeLanguage(Request $request)
	{
		$lang = $request->lang;
		if(array_key_exists($lang,config('app.lang_name'))){
			Session::put('selected_lang',$lang);
		}
	}
}
