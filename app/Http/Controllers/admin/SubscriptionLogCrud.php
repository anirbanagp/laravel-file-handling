<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionLogRequest;
use App\Http\Library\Crud\Traits\RbacHelper;

class SubscriptionLogCrud extends Crud
{
	use RbacHelper;
	/**
	 * name of the table . REQUIRED
	 * @var string
	 */
	public $table_name 				=	'subscription_logs';

	/**
	 * contain model path
	 * @var string
	 */
	public $model_path				=	'App\Models\\';

	/**
	 * route name that shold be used to create different action link. REQUIRED
	 * @var string
	 */
	public $route_slug 				=	'admin-subscription-management-companies-subscription-logs-';
	/**
	 * You can use RBAC to manage action button by crud. OPTIONAL
	 * @var bool
	 */
	public $use_rbac				=	true;

	/**
	 * crud will check permission for this slug if rbac is true
	 * @var string
	 */
	public $module_slug				=	'subscription-logs-';
	/**
	 * You can customize you table coloumn.
	 *  field name as key, label as value. only table field are acceptable. OPTIONAL
	 * @var array
	 */
	public $columns_list			=	['details' => 'details'];
	/**
	 * You can unset action button. 'view/edit/delete acceptable'. OPTIONAL
	 * @var array
	 */
	public $unset_actions_button	=	['view', 'edit', 'delete'];

	public $unset_coloumn 			=	['id','created_at','updated_at','updated_by', 'deleted_at'];

	/**
	 * This will display table data in view page in data table
	 * @return view           	 load view page
	 */
    public function show($id)
    {
		$this->base_id	=	$id;
		$this->page_title = 'Subscription Log List';
    	$data = $this->rendarShow();
		return view('admin.crud.show',$data);
    }
	/**
	 * This will display a details for an id of this table
	 * @param  integer  $id      id of selected row
	 * @return view           	 load view page
	 */
	public function view(Request $request)
	{
		$this->page_title = 'View Subscription Log';
		$data = $this->rendarView($id);
		return view('admin.crud.view',$data);
	}
	/**
	 * This will load an insert form for current table
	 * @return view   load view page
	 */
	public function add()
	{
		$this->page_title = 'Add Subscription Log';
		$data = $this->rendarAdd();
		return view('admin.crud.form',$data);
	}
	/**
	 * This will insert data into databse
	 * @param  SubscriptionLogRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function insert(SubscriptionLogRequest $request)
	{
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
		$this->page_title = 'Edit SubscriptionLog';
		$data = $this->rendarEdit($id);
		return view('admin.crud.form',$data);
	}
	/**
	 * this will update a row
	 * @param  SubscriptionLogRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function update(SubscriptionLogRequest $request)
	{
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
		$response = $this->deleteData($id);
		return redirect($response);
	}
	/**
	 * If you want to call any function for all, set here. by default crud will call this
	 * @return void        called by crud self
	 */
	public function callDefault()
	{
		$this->additional_where = 'WHERE subscription_logs.subscriber_id = '.$this->base_id;
		$this->unsetAdd();
		$this->addCallBackColoumn('details', "Details", 'setDetails');
	}
	public function setDetails($row, $value, $type)
	{
		return $value;
	}
}
