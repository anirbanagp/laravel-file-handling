<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;

class UserCrud extends Crud
{
	/**
	 * name of the table . REQUIRED
	 * @var string
	 */
	public $table_name 				= 'users';
	/**
	 * route name that shold be used to create different action link. REQUIRED
	 * @var string
	 */
	public $route_slug 				= 'admin-users-';
	/**
	 * You can use RBAC to manage action button by crud. OPTIONAL
	 * @var bool
	 */
	public $use_rbac				= true;

	public $module_slug				= 'users-';
	/**
	 * You can customize you table coloumn.
	 *  field name as key, label as value. only table field are acceptable. OPTIONAL
	 * @var array
	 */
	//public $columns_list;
	/**
	 * You can unset action button. 'view/edit/delete acceptable'. OPTIONAL
	 * @var array
	 */

	 public  $unset_action_button  =  ['delete'];

	 public  $unset_relation_coloumn  =  ['parent_id'];

	public $unset_coloumn = [ 'parent_id', 'mobile', 'id','created_at','password', 'updated_at','updated_by', 'otp', 'deleted_at', 'unique_code'];

	/**
	 *  contain user id of parent user od current list
	 *
	 *  @var  int
	 */
	public $base_id;

	/**
	 * this will contain specific parent id received from url
	 * @var boolean|int
	 */
	public $users_parent_id;

	/**
	 * this will contain children role id of a role
	 * @var [type]
	 */
	public $role_based_children = [];

	public function  __construct(Request $request)
	{
		parent::__construct($request);
		$this->base_id = &$this->user_id;
	}
	/**
	 * This will display table data in view page in data table
	 * @return view           	 load view page
	 */
    public function show($id = null)
    {
		$parent_id = $id ? $id : null;
		$this->main_page_title 	= 'User List';
		$data					=	$this->getMenuData();
		$data['action']			=	$this->getActionButton();
		$data['parent_id']		=	$parent_id;
		return view('admin.users.show',$data);
    }
	/**
	 *  this will called bu ajax DataTables
	 *
	 *  @return  json  all data
	 */
	public function data($id = null)
	{
		$id = $id ? $id : $this->user_id;
		$users	= User::whereParentId($id);
		return   DataTables::of($users)
				->editColumn('email', function(User $user) {
					   return $this->viewChildrenLink($user, $user->email, 'list');
				})
				->editColumn('status', '{!! setStatus($status) !!}')
				->addColumn('action', function(User $user) {
					   return $this->actionField($user);
				})
				->rawColumns(['status', 'action', 'email'])
				->make(true);
	}
	/**
	 * This will display a details for an id of this table
	 * @param  integer  $id      id of selected row
	 * @return view           	 load view page
	 */
	public function view(Request $request)
	{
		$this->page_title = 'View User';
		$this->users_parent_id	= $request->user->parent_id;
		$data = $this->rendarView($request->id);
		return view('admin.crud.view',$data);
	}
	/**
	 * This will load an insert form for current table
	 * @return view   load view page
	 */
	public function add(Request $request)
	{
		$this->page_title = 'Add User';
		$this->unset_coloumn = ['id',  'created_at', 'updated_at','updated_by', 'otp', 'deleted_at', 'unique_code'];
		$data = $this->rendarAdd();
		if($this->user->role->site_admin == 'yes') {
			$data['input_list']['parent_id']['field_type'] 	= 'text';
			$data['input_list']['parent_id']['raw_html'] 	= $this->getUserSearchHtml($request->id);
		}
		$data['input_list']['role_id']['option_values'] = $this->getChildRoles();
		return view('admin.crud.form',$data);
	}
	/**
	 * This will insert data into databse
	 * @param  UserRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function insert(UserRequest $request)
	{
		$this->unset_coloumn = ['id','created_at', 'updated_at','updated_by', 'otp', 'deleted_at', 'unique_code'];
		$request->password = md5($request->password);
		// TODO: need to change this section when vendor can add sub ordinates
		if($this->user->role->site_admin != 'yes') {
			$request->parent_id = $this->user_id;
		}
		$response = $this->insertData($request);
		$this->current_model->user_profile()->create();
		$this->log($request->username .' user added');
		return redirect($response);
	}
	/**
	 * this will load edit form
	 * @param  integer $id id of this table
	 * @return view     load edit form
	 */
	public function edit(Request $request)
	{
		$this->page_title = 'Edit User';
		$this->users_parent_id	= $request->user->parent_id;
		$this->unset_coloumn = ['id','created_at', 'updated_at','updated_by', 'otp', 'deleted_at', 'unique_code'];
		$data = $this->rendarEdit($request->id);
		$data['input_list']['role_id']['option_values'] = $this->getChildRoles();
		$data['input_list']['email']['extra_attribute'] = 'disabled';
		if($this->user->role->site_admin == 'yes') {
			$data['input_list']['parent_id']['field_type'] 	= 'text';
			$data['input_list']['parent_id']['raw_html'] 	= $this->getUserSearchHtml($data['input_list']['parent_id']['default_value']);
		}
		return view('admin.crud.form',$data);
	}
	/**
	 * this will update a row
	 * @param  UserRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function update(UserRequest $request)
	{
		$this->unset_coloumn = ['id','email', 'created_at', 'updated_at','updated_by', 'otp', 'deleted_at', 'unique_code'];
		if($request->password) {
			$request->password = md5($request->password);
		}
		if($request->email) {
			unset($request->email);
		}
		// TODO: need to change this section when vendor can add sub ordinates
		// $request->parent_id = $this->user_id;

		if($request->status == 'inactive') {
			$active_children	=	User::select('id', 'parent_id')->whereId($request->id)->first()->children()->whereStatus('active')->count();
			if($active_children > 0) {
				$this->setFlashAlert('danger', 'This user has '. $active_children.' active sub ordinates. Please inactive them before this action.');
				return back();
			}
		} else {
			if($request->user->parent->status !== 'active') {
				$this->setFlashAlert('danger', 'Parent of this user is inactive. Please active him first!');
				return back();
			}
		}
		$this->log($request->username .' user updated', null, (string)User::find($request->id));
		$response = $this->updateData($request);
		return redirect($response);
	}
	/**
	 * this will delete a row
	 * @param  Request $request
	 * @return void     redirect to list page
	 */
	public function delete(Request $request)
	{
		// $this->users_parent_id	= $request->user->parent_id;
		// $response = $this->deleteData($request->id);
		$this->setFlashAlert('danger', 'Unauthorized Action!');
		return back();
	}

	public function callDefault()
	{
		$users_parent_id	=	$this->users_parent_id ? $this->users_parent_id : $this->base_id;
		$where = 'WHERE users.parent_id = '.$users_parent_id.' AND users.deleted_at IS NULL';
		$this->additional_where   = $where;
		$this->addCallBackColoumn('email', 'Email', 'viewChildrenLink');
		$this->changeFieldType('parent_id', 'hidden' , null, $this->user_id);
	}

	public function viewChildrenLink($row_data,$value,$type)
	{
		if(array_key_exists($row_data->role_id, $this->role_based_children) === false) {
			$this->role_based_children[$row_data->role_id] = getChildRoles($row_data->role_id);
		}
		if($type =="list" && count($this->role_based_children[$row_data->role_id])){
			return '<a target="_blank" href="'.route('admin-users-list').'/'.$row_data->id.'">'.$value.'</a>';
		}
		return $value;
	}
	/**
	 * return user search box
	 * @param  null|int $parent_id
	 * @return string
	 */
	public function getUserSearchHtml($parent_id=null)
	{
		$value	=	null;
		$image_name = $parent_id ? 'success' : 'danger';
		if($parent_id) {
			if($user=	User::find($parent_id)) {
				$value	=	$user->email;
			}
		}
		return '<input type="text"  id="search_box" autocomplete="off"  value="'.$value.'" class="form-control" placeholder="Enter parent Email" value=""><span><img style="display:none;" id="suggestion_status" src="'.asset('new_admin/images/'.$image_name.'.svg').'" class="icon_svg"/></span>
		<input type="hidden" name="parent_id" id="user_id" value="'.$parent_id.'" class="form-control" >
		<div id="suggestion-box" class="sg_box"  style="display:none;"></div>';
	}
	/**
	 *  it will return action button html based on role of user
	 *
	 *  @param  object  $value  each row object
	 *  @return  string  html string
	 */
	public function actionField($value)
	{
		$this->module_slug_name =   $this->module_slug_name ? $this->module_slug_name : ($this->module_slug ? $this->module_slug : '');
		$html = '';
		$show_dropdown_start = '<div class="dropdown">
                    <button class="btn btn-default dropdown-toggle bg-blue-grey" type="button" id="menu'.$value->id.'" data-toggle="dropdown">More
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu'.$value->id.'">';
        $show_dropdown_end = '</ul></div></div>';
        $more_than_3    = false;
		if($this->canView()) {
			$html	.=	'<a href="'. route('admin-users-view',$value->id).'" class="waves-effect btn btn-info"><i class="material-icons">info</i> View</a>';
		}
		if($this->canModify()) {
			$html	.= '<a href="'. route('admin-users-edit',$value->id) .'" class="waves-effect btn btn-warning"><i class="material-icons">create</i> Edit</a>';
						// <a href="'. route('admin-users-delete',$value->id) .'" class="delete_button waves-effect btn btn-danger"><i class="material-icons">delete_sweep</i> Delete</a>';
		}
		if($this->canView('user-activity-log-')) {
			$html	.= '<a href="'. route('admin-user-activity-log-',$value->id) .'" class="waves-effect btn bg-grey"><i class="material-icons">create</i> Activity</a>';
						// <a href="'. route('admin-users-delete',$value->id) .'" class="delete_button waves-effect btn btn-danger"><i class="material-icons">delete_sweep</i> Delete</a>';
		}
		$html       .= '{{ show_dropdown_start }}';
		$html       .=  $more_than_3 ? $show_dropdown_end : '';
        $replace     =  $more_than_3 ? $show_dropdown_start : '';
        $html        =   str_ireplace('{{ show_dropdown_start }}', $replace, $html);
		return $html;
	}
	/**
	 *  it will return a users email list as suggestion
	 *
	 *  @param  string  $slug	email | username
	 *  @return  string  html
	 */
	public function getUserName($slug)
	{
		$users		=	User::select('id', 'email', 'parent_id')->where(function ($query) {
						    $query->where('status', 'LIKE', 'active');
						})->where(function ($query) use($slug) {
						    $query->where('email', 'LIKE', '%'.$slug.'%')->orWhere('username', 'LIKE', '%'.$slug.'%');
						})->get();

		$li_count 	=	0;
		$html 		=	'<ul class="users-list">';
		if($users && count($users)) {
			foreach ($users as $key => $value) {
				$is_child		=	true;
				if($this->user->role->site_admin !== 'yes') {
					$parents_id	=	$value->getAllParentIds();
					$is_child	=	in_array($this->user->id, $parents_id) ? true : false;
				}
				if($is_child) {
					$li_count++;
					$html 	.=	'<li class="each-user" data-id="'.$value->id.'" data-val="'.$value->email.'">'.$value->email.'</li>';
				}
			}
		}
		if($li_count == 0) {
			$html 	    .=	'<li>No Match Found..</li>';
		}
		$html 	        .=	'</ul>';
		return $html;
	}

	public function getAllParentList(Request $request)
	{
		$parents_id	=	$request->user->getAllParentIds();
		$users		=	User::select('id', 'email')->whereIn('id', $parents_id)->whereStatus('active')->get();
		$html 	=	'<option value="">-- Please select --</option>';
		if($users && count($users)) {
			foreach ($users as $key => $value) {
				$html 	.=	'<option value="'.$value->id.'" >'.$value->email.'</option>';
			}
		}else {
			$html 	    .=	'<option value="">No Value Found</option>';
		}
		return $html;
	}

}
