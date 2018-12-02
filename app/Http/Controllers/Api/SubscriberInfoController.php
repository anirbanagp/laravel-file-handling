<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiBaseController;

class SubscriberInfoController extends ApiBaseController
{
    public function getActiveUserCount(Request $request)
    {
        dd($request);
    }
}
