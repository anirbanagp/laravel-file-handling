<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\admin\AdminBaseController;

class CrmModuleController extends AdminBaseController
{
    /**
     * contain timestamp of api request
     *
     * @var integer
     */
    private $timestamp;

    /**
     * this will load view page containing all modules of a tenant
     *
     * @param  Request $request
     *
     * @return htmlstring           view page
     */
    public function show(Request $request)
    {
        $this->main_page_title  =   $this->request->subscriber->company_name .' modules list';
        $data                   =   $this->getMenuData();
        $data['main_modules']   =   [];
        $data['sub_modules']    =   [];
        $data['post_url']       =   route('admin-subscription-management-companies-crm-modules-update', $this->request->id);
        if($modules = $this->fetchModulesData()) {
            $modules    =   collect($modules);
            $data['main_modules'] = $modules->filter(function ($value, $key) {
                return $value->parent_id == null;
            });
            $data['sub_modules']    =   $modules->groupBy('parent_id');
        }
        return view('admin.crm-modules.module-list', $data);
    }

    /**
     * this will get all modules data from tenant database by calling api
     *
     * @return  bool|object  false if error on api call, else result
     */
    public function fetchModulesData()
    {
        if($response = $this->getApiResponse('api/get-all-modules')) {
            return $response->result->modules;
        }
        return false;
    }

    /**
     * this will generate access token before api calling
     *
     * @return string access token
     */
    public function getAccessToken()
    {
        $this->timestamp    =   time();
        // NOTE: 17+timestamp+40
        $token              =   str_random(17).$this->timestamp.mt_rand(100000000,999999999).str_random(31);
        $encrypted_token    =   base64_encode($token);
        return $encrypted_token;
    }

    /**
     * this will update new module permissions by calling api
     *
     * @param  Request $request
     *
     * @return void           redirect
     */
    public function update(Request $request)
    {
        $all_params =   $request->post();
        unset($all_params['_token'], $all_params['subscriber']);
        $params                 =   [];
        $params['modules']       =   json_encode(array_keys($all_params));
        if($response =   $this->getApiResponse('api/update-modules', $params)) {
            $this->setFlashAlert("success", 'Successfully Updated!');
        }
        return back();
    }

    /**
     * this is generic api calling function
     *
     * @param  string $uri    api uri
     * @param  array  $params post data| nullable
     *
     * @return bool|object         false if error occured|result object
     */
    public function getApiResponse($uri, $params = [])
    {
        $url                     =   getcompanyUrl($this->request->subscriber->tenant_folder).$uri;
        $params['token']         =   $this->getAccessToken();
        $params['request_at']    =   $this->timestamp;
        $api_response            =   callCURL($url , $params);
        if($api_response) {
            $response = json_decode($api_response);
            if($response && isset($response->status_code) && $response->status_code === 200) {
                return $response;
            } else {
                $this->setFlashAlert('danger', 'Authentication failed! Try again.');
            }
        } else {
            $this->setFlashAlert('danger', 'Could not connect remote site');
        }
        return false;
    }
}
