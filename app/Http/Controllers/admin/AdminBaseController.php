<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Rbac;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

/**
 *  This is base controller for admin, every controller should inherit this,
 *  so, we can implement any feature to all controller easily. It use Rbac trait for access control.
 *
 *  @author Anirban Saha
 */
class AdminBaseController extends Controller
{
	use Rbac;

	/**
	 * contain current request
	 * @var Request
	 */
	public $request;

	/**
	 *  this will assign user role and user in class property
	 *
	 */
	public function __construct(Request $request)
	{
		$this->isLoggedIn($request);
		$this->request	=	$request;
	}
	/**
	 *  it will upload a images
	 *
	 *  @param    File  file to be uploaded
	 *  @return  string|error  filepath on success
	 */
	public function uploadFile($file, $path)
	{
		Storage::makeDirectory($path);
		if($filename = $file->store($path)) {
			return $filename;
		}
		abort(422, 'upload failed');
	}

}
