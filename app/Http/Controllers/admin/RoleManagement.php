<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Session;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\admin\AdminBaseController;
use App\Http\Traits\DataSaver;
use App\Models\Role;
use DB;
use App\Models\Module;
use App\Models\Permission;

/**
 *  This is a command to fetch all roll management details and save into database from admin panel.
 *
 *  @author	Sourav Chowdhury
 */
class RoleManagement extends AdminBaseController
{
    use DataSaver;
    /**
     * this function is for list role management listing page
     */
    public function listRoleManagement()
    {
        $this->setLeftSideBarData();

        $roles = Role::where('status', 'active')
                ->whereIn('id', array_keys(getChildRoles($this->role_id)))
				->whereIsAdmin(true)
               ->get()
               ->toArray();
        if($this->user->role_id == '1') {
            $modules = Module::where('status', 'active')->orderBy('rank', 'asc')->get();
        } else {
            $available_modules_id = $this->user->role->permission()->select('module_id')->where('status', 'active')->pluck('module_id')->toArray();
            $modules = Module::where('status', 'active')->whereIn('id', $available_modules_id)->orderBy('rank', 'asc')->get();
        }
        $parent_module = array();
        $sub_module = array();
        $all_modules = array();
        foreach($modules as $key => $mdata){
         if($mdata->parent_id==0)
         {
             array_push($parent_module, $mdata);
         }
         else {
             array_push($sub_module, $mdata);
         }

        }

        foreach($parent_module as $pmodule){
            foreach($sub_module as $smodule){
                if($pmodule->parent_id==0)
                {
                    if (!in_array($pmodule, $all_modules))
                    {
                        array_push($all_modules, $pmodule);
                    }
                }
                if ($smodule->parent_id == $pmodule->id)
                {
                    array_push($all_modules, $smodule);
                }
            }
        }

        $where = array('permissions.status' =>'active');
        $permissions = DB::table('permissions')
        ->leftJoin('modules', 'permissions.module_id', '=', 'modules.id')
        ->where($where)
        ->orderBy('permissions.role_id', 'asc')
        ->orderBy('modules.id', 'asc')
        ->get()
        ->toArray();

        $arr = array();
        $per = array();
        foreach($permissions as $permission){
            $arr['role_id'] = $permission->role_id;
            $arr['module_id'] = $permission->module_id;
            $arr['can_add'] = $permission->can_add;
            $arr['can_view'] = $permission->can_view;
            $arr['can_modify'] = $permission->can_modify;
            $per[$permission->module_id.'-'.$permission->role_id] = $arr;
        }

        return view('admin/settings/role_management_list',['profile_image' => $this->profile_image,'parent_menu' => $this->parent_menu, 'sub_menu' =>$this->sub_menu,'roles' => $roles,'modules' =>$all_modules, 'permissions' =>$per]);
    }

    /**
     * this function is for insert/update module permissions
     * @param  Request $request [description]
     * @var string
     */
    public function postRoleManagement(Request $request)
    {
        if(($request))
        {
            $role = $this->role_id;
            $method = $request->method;
            $value = $request->value;
            $role_id = $request->role_id;
            $module_id = $request->module_id;

            $where = array('permissions.role_id' =>$role_id, 'permissions.module_id' =>$module_id);
            $permissions = DB::table('permissions')
              ->where($where)
              ->get()
              ->toArray();

            if($permissions){
                $id = $permissions[0]->id;
                $this->log('Permission Updated', null, (string)Permission::find($id));
                DB::table('permissions')
                ->where('id', $id)
                ->update([$method => $value, 'updated_by' => 1, 'updated_at' => date("Y-m-d H:i:s")]);
            }
            else {
                $data = array('role_id'=>$role_id,
                          'module_id'=>$module_id,
                           $method => $value,
                           'status' => 'active',
                           'created_by' => 1,
                           'created_at' => date("Y-m-d H:i:s"));
                DB::table('permissions')
                ->insert($data);
                $this->log('Permission Added ');
            }

            echo 'true';

        }
    }
    public function getNotifications()
    {
        return redirect(route('admin-coming-soon'));
    }
    public function getSiteSettings()
    {
        return redirect(route('admin-coming-soon'));
    }
    public function getProfileSettings()
    {
        return redirect(route('admin-coming-soon'));
    }
    public function getLanguageSettings()
    {
        return redirect(route('admin-coming-soon'));
    }
}
