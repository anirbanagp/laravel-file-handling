<?php
namespace App\Http\Traits;
use App\Models\Module;
use App\Models\Permission;
use App\Models\UserProfile;
use Session;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
/**
 * This will contain all permission formula
 *
 *  @author Anirban Saha
 */
trait Rbac{
	/**
	 * logged in user's id
	 *
	 * @var integer
	 */

    public $user_id = false;
	/**
	 * logged in user's role_id
	 *
	 * @var integer
	 */

    public $role_id = false;

	/**
	 *  contain current user model
	 *
	 *  @var  App\User|bool
	 */
	public $user	=	false;

	/**
	 * current module name that should be validate for logged in user
	 *
	 * @var string
	 */

    public $module_slug_name = false;
	/**
	 * parrent menu data for logged in user
	 *
	 * @var array
	 */

	public $parent_menu;
	/**
	 * sub menu data for logged in user
	 *
	 * @var array
	 */

	public $sub_menu;
	/**
	 * This will contain profile image
	 *
	 * @var string
	 */

	public $profile_image;

	/**
	 *  contain page tile
	 *
	 *  @var  string
	 */
	public $main_page_title	= 'Welcome';

    /**
     * It will check the permission of logged in user for adding any info in specific module
     *
     *  @param  string $module_slug slug_name of modules table
     * @return boolean              return true if  permitter else false
     */
    public function canAdd($module_slug=null)
    {
        $module_slug = $module_slug == null ? $this->module_slug_name :  $module_slug;
        $module_id = $this->getModuleId($module_slug);
        $where_array = array('role_id'=>$this->role_id,'module_id'=>$module_id);
        $permissions = Permission::select('can_add')->where($where_array)->first();
        if(isset($permissions->can_add) && $permissions->can_add==1) {
            return true;
        }else {
            return false;
        }
    }
    /**
     * It will check the permission of logged in user for viewing any info in specific module
     *
     * @param  string $module_slug slug_name of modules table
     * @return boolean              return true if  permitter else false
     */
    public function canView($module_slug = null)
    {
		$module_slug = $module_slug == null ? $this->module_slug_name :  $module_slug;
        $module_id = $this->getModuleId($module_slug);
        $where_array = array('role_id'=>$this->role_id,'module_id'=>$module_id);
        $permissions = Permission::select('can_view')->where($where_array)->first();
        if(isset($permissions->can_view) && $permissions->can_view==1) {
            return true;
        }else {
            return false;
        }
    }
    /**
     * It will check the permission of logged in user for modifying any info in specific module
     *
     * @param  string $module_slug slug_name of modules table
     * @return boolean              return true if  permitter else false
     */
    public function canModify($module_slug = null)
    {
        $module_slug = $module_slug == null ? $this->module_slug_name :  $module_slug;
        $module_id = $this->getModuleId($module_slug);
        $where_array = array('role_id'=>$this->role_id,'module_id'=>$module_id);
        $permissions = Permission::select('can_modify')->where($where_array)->first();
        if(isset($permissions->can_modify) && $permissions->can_modify==1){
            return true;
        }else {
            return false;
        }
    }
    /**
     * it will send id  of modules table from a  slug_name
     *
     *  @param  string $module_slug slug_name of modules table
     * @return integer              id of modules table
     */
    public function getModuleId($module_slug){
        $where_array = array('slug_name'=>$module_slug,'status'=>'active');
        $module_id = Module::select('id')->where($where_array)->first();
        return  isset($module_id->id) ? $module_id->id : 0;
    }
    /**
     * it will check an user either logged in or not, and set role_id and user_id into  class property
     *
     * @return boolean return true if logged in
     */
    public function isLoggedIn()
    {
        if(Session::get('admin_details')) {
            $this->user_id	=	Session::get('admin_details')['id'];
            $this->role_id	=	Session::get('admin_details')['role_id'];
            if(Session::get('user_model_of_logged_in_user')) {
                $this->user		=	Session::get('user_model_of_logged_in_user');
            } else {
                $this->user		=	User::find($this->user_id);
                Session::put('user_model_of_logged_in_user',$this->user);
            }
            return true;
        }else {
          return false;
        }
    }
	/**
	 * This will set role base menu
	 */
	public function setLeftSideBarData()
	{
		$this->isLoggedIn();
		$role_id 	= $this->role_id;

        $modules = Permission::
                    where('status','active')
                    ->where('can_view',1)
                    ->where('role_id',$role_id)
                    ->with('module')
                    ->get()
                    ->where('module.status','active')
                    ->where('module.is_menu','yes')
                    ->groupBy('module.parent_id');
        $parent_menu = $modules[0]->sortByDesc('module.rank')->unique('module.id')->pluck('module');

        $sub_menu = $modules->mapToGroups(function ($item, $key) {
                    return [$key => $item->pluck('module')->unique('id')->sortByDesc('rank')];
                });
		$this->parent_menu = $parent_menu;
		$this->sub_menu = $sub_menu;
	}

	/**
	 *  return left menu bar data and page title
	 *
	 *  @return  array  parent_menu, sub_menu, page_title
	 */
	public function getMenuData()
	{
		$this->setLeftSideBarData();
		$data 					=	[];
		$data['parent_menu'] 	= 	$this->parent_menu;
		$data['sub_menu'] 		= 	$this->sub_menu;
		$data['page_title'] 	= 	$this->main_page_title;
		return $data;
	}
	/**
	 *  this will return menu data of leftbar
	 *
	 *  @return  array  all main and sub menu data
	 */
	public function getLeftSideBarData()
	{
		$this->setLeftSideBarData();
		$data['parent_menu'] 	= $this->parent_menu;
		$data['sub_menu'] 		= $this->sub_menu;
		$data['profile_image'] 	= $this->profile_image;
		return $data;
	}

	/**
	 * this will check logged in user is parent or not
	 *
	 * @param  int  $user_id user_id
	 * @return boolean          true id parent or self/ false for others
	 */
	public function isParrent($user_id)
	{
		if($this->user->role->site_admin == 'yes') {
			//super admin
			return true;
		}else {
			$permitted_ids = $this->getParentsId($user_id);
			if(in_array($this->user_id, $permitted_ids)) {
				return true;
			}
			return false;
		}
	}

	/**
	 * return parent ids with self is
	 *
	 * @param  int $user_id user_id
	 * @return array          array of ids
	 */
	public function getParentsId($user_id)
	{
		$user_details = $this->user;
		if(isset($user_details->id)) {
			$parent_agent_id = $user_details->parent->id;
			$master_agent_id = 0;
			if($user_details->role_id == 4) {
				$master_agent_id = $user_details->parent->parent->id;
			}
			return [$user_id,$parent_agent_id,$master_agent_id];
		}
		return [];
	}

	/**
	 *  this will return allowed roles for current user
	 *
	 *  @return  array  [role_id => role_name]
	 */
	public function getChildRoles($parent_role = null)
	{
        $parent_role    =   $parent_role === null ? $this->role_id : $parent_role;
		$permitted_ids = RolePermission::select('r_id')->whereRoleId($parent_role)->whereCanAdd('1')->whereStatus('active')->pluck('r_id');
        $roles = Role::select('id', 'role_name')->whereStatus('active')->whereIn('id',$permitted_ids)->pluck('role_name', 'id')->toArray();
		if(!is_array($roles)) {
			$roles = [];
		}
		return $roles;
	}

	public function getActionButton()
	{
        // IDEA: This is to make compatible rbac with crud controller, both use own module slug property
        $this->module_slug_name =   $this->module_slug_name ? $this->module_slug_name : ($this->module_slug ? $this->module_slug : '');
		$data 			= [];
		$data['add'] 	= $this->canAdd();
		$data['edit'] 	= $this->canModify();
		$data['view'] 	= $this->canView();
		return $data;
	}

}
