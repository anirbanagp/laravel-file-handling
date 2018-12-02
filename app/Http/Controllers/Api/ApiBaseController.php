<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiBaseController extends Controller
{
    private $request;
    private $status_code 	= 200;
	private $status_message = 'success';
	public $result			=  [];

    public function __construct(Request $request)
    {
        $this->request  =   $request;
        if(!$this->isValidRequest()) {
	    	$this->throwUnAuthenticated();
        }
    }
    public function sendResponse()
    {
    	$data['status_code']	= $this->status_code;
    	$data['status_message']	= $this->status_message;
		$data['result']			= $this->result;
    	return json_encode($data);die;
    }
    public function throwUnAuthenticated()
    {
    	$this->status_code 		=	401;
    	$this->status_message 	=	'Unauthenticated Request!';
    	$this->sendResponse();
    }
    public function isValidRequest()
    {
        dd($this->request);
        return false;
    }
}
