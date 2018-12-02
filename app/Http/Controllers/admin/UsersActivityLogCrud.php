<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\UsersActivityLogRequest;
use App\Http\Library\Crud\Traits\RbacHelper;
use App\Models\UsersActivityLog;

class UsersActivityLogCrud extends Crud
{
	use RbacHelper;
	/**
	 * name of the table . REQUIRED
	 * @var string
	 */
	public $table_name 				=	'users_activity_logs';

	/**
	 * contain model path
	 * @var string
	 */
	public $model_path				=	'App\Models\\';

	/**
	 * route name that shold be used to create different action link. REQUIRED
	 * @var string
	 */
	public $route_slug 				=	'admin-user-activity-log-';
	/**
	 * You can use RBAC to manage action button by crud. OPTIONAL
	 * @var bool
	 */
	public $use_rbac				=	true;

	/**
	 * crud will check permission for this slug if rbac is true
	 * @var string
	 */
	public $module_slug				=	'user-activity-log-';
	/**
	 * You can customize you table coloumn.
	 *  field name as key, label as value. only table field are acceptable. OPTIONAL
	 * @var array
	 */
	 // public $columns_list			=	[];
	/**
	 * You can unset action button. 'view/edit/delete acceptable'. OPTIONAL
	 * @var array
	 */
	 public $unset_actions_button	=	[ 'edit', 'delete'];

	public $unset_coloumn 			=	['id','old_data','updated_at','updated_by', 'deleted_at'];

	/**
	 * This will display table data in view page in data table
	 * @return view           	 load view page
	 */
    public function show($user_id = null)
    {
		$parent_id = $user_id ? $user_id : null;
		$this->main_page_title 	= 'Users Activity Log List';
		$data					=	$this->getMenuData();
		$data['action']			=	$this->getActionButton();
		$data['parent_id']		=	$parent_id;
		return view('admin.user-activity-log.show',$data);

    }
	/**
	 *  this will called bu ajax DataTables
	 *
	 *  @return  json  all data
	 */
	public function data($id = null)
	{
		$users	= UsersActivityLog::select('id','user_id', 'event', 'created_at')->with('user')->latest();
		if($id) {
			$users->whereUserId($id);
		}
		return   DataTables::of($users)
				->editColumn('user_id', function(UsersActivityLog $user_log) {
					   return $user_log->user->username;
				})
				// ->addColumn('action', function(UsersActivityLog $user_log) {
				// 	   return '<a href="'.route('admin-user-activity-log-view', $user_log->id).'" class="waves-effect btn btn-info"><i class="material-icons">info</i>View</a>';
				// })
				// ->rawColumns([ 'action'])
				->make(true);
	}

	/**
	 * This will display a details for an id of this table
	 * @param  integer  $id      id of selected row
	 * @return view           	 load view page
	 */
	public function view($id)
	{
		$this->page_title = 'View Users Activity Log';
		$data = $this->rendarView($id);
		return view('admin.crud.view',$data);
	}
	/**
	 * This will load an insert form for current table
	 * @return view   load view page
	 */
	public function add()
	{
		$this->setFlashAlert('danger', 'Unauthorized Action!');
		return back();
		$this->page_title = 'Add Users ActivityLog';
		$data = $this->rendarAdd();
		return view('admin.crud.form',$data);
	}
	/**
	 * This will insert data into databse
	 * @param  UsersActivityLogRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function insert(UsersActivityLogRequest $request)
	{
		$this->setFlashAlert('danger', 'Unauthorized Action!');
		return back();
		$response = $this->insertData($request);
		return redirect($response);
	}
	/**
	 * this will load edit form
	 * @param  integer $id id of this table
	 * @return view     load edit form
	 */
	public function edit($id)
	{
		$this->setFlashAlert('danger', 'Unauthorized Action!');
		return back();
		$this->page_title = 'Edit UsersActivityLog';
		$data = $this->rendarEdit($id);
		return view('admin.crud.form',$data);
	}
	/**
	 * this will update a row
	 * @param  UsersActivityLogRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function update(UsersActivityLogRequest $request)
	{
		$this->setFlashAlert('danger', 'Unauthorized Action!');
		return back();
		$response = $this->updateData($request);
		return redirect($response);
	}
	/**
	 * this will delete a row
	 * @param  inetger $id id or row to be deleted
	 * @return void     redirect to list page
	 */
	public function delete($id)
	{
		$this->setFlashAlert('danger', 'Unauthorized Action!');
		return back();
		$response = $this->deleteData($id);
		return redirect($response);
	}
	/**
	 * If you want to call any function for all, set here. by default crud will call this
	 * @return void        called by crud self
	 */
	 public function callDefault()
 	{
		if($this->base_id) {
			$this->additional_where = 'WHERE users_activity_logs.user_id = '.$this->base_id;
		}
 		$this->unsetAdd();
 		// $this->addCallBackColoumn('details', "Details", 'setDetails');
 	}
 	public function setDetails($row, $value, $type)
 	{
 		return $value;
 	}
}
