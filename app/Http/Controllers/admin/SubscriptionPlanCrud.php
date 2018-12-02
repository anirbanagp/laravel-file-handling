<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionPlanRequest;
use App\Models\Currency;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;

class SubscriptionPlanCrud extends Crud
{
	/**
	 * name of the table . REQUIRED
	 * @var string
	 */
	public $table_name 				=	'subscription_plans';

	/**
	 * contain model path
	 * @var string
	 */
	public $model_path				=	'App\Models\\';

	/**
	 * route name that shold be used to create different action link. REQUIRED
	 * @var string
	 */
	public $route_slug 				=	'admin-subscription-management-plans-';
	/**
	 * You can use RBAC to manage action button by crud. OPTIONAL
	 * @var bool
	 */
	public $use_rbac				=	true;

	/**
	 * crud will check permission for this slug if rbac is true
	 * @var string
	 */
	public $module_slug				=	'plans-';
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
	public $unset_actions_button	=	['delete'];

	public $unset_coloumn 			=	['id','created_at','updated_at','updated_by', 'deleted_at'];

	public $tenure_array			= 	['monthly' => 1, 'quarterly' => 3, 'half yearly' => 6, 'yearly' => 12];

	/**
	 * This will display table data in view page in data table
	 * @return view           	 load view page
	 */
    public function show()
    {
		$this->page_title = 'Subscription Plan List';
    	$data = $this->rendarShow();
		return view('admin.crud.show',$data);
    }
	/**
	 * This will display a details for an id of this table
	 * @param  integer  $id      id of selected row
	 * @return view           	 load view page
	 */
	public function view($id)
	{
		$this->page_title = 'View Subscription Plan';
		$data = $this->rendarView($id);
		return view('admin.crud.view',$data);
	}
	/**
	 * This will load an insert form for current table
	 * @return view   load view page
	 */
	public function add()
	{
		$this->page_title = 'Add Subscription Plan';
		$this->setPriceFields();
		$data = $this->rendarAdd();
		unset($data['input_list']['price']);
		moveElement($data['input_list'], 3, count($data['input_list']));
		return view('admin.crud.form',$data);
	}
	/**
	 * This will insert data into databse
	 * @param  SubscriptionPlanRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function insert(SubscriptionPlanRequest $request)
	{
		$this->setPriceFieldValue();
		$response = $this->insertData($this->request);
		$this->log($request->plan_name .' plan added');
		return redirect($response);
	}
	/**
	 * this will load edit form
	 * @param  integer $id id of this table
	 * @return view     load edit form
	 */
	public function edit($id)
	{
		$this->page_title = 'Edit Subscription Plan';
		$this->setPriceFields();
		$data = $this->rendarEdit($id);
		unset($data['input_list']['price']);
		moveElement($data['input_list'], 4, count($data['input_list']));
		return view('admin.crud.form',$data);
	}
	/**
	 * this will update a row
	 * @param  SubscriptionPlanRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function update(SubscriptionPlanRequest $request)
	{
		$this->setPriceFieldValue();
		$this->log($request->plan_name .' plan updated', null, (string)SubscriptionPlan::find($request->id));
		$response = $this->updateData($this->request);
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
		$this->log('id : '. $id .' plan added');
		return redirect($response);
	}
	/**
	 * If you want to call any function for all, set here. by default crud will call this
	 * @return void        called by crud self
	 */
	public function callDefault()
	{
		$array = range(0,50);
		unset($array[0]);
		$this->addCallBackColoumn('price', "Price", "showPriceFromJson");
		$this->changeFieldType('active_user_count','select','Active user count',null, $array);
	}
	public function setPriceFields()
	{
		$all_currencies = Currency::select('short_code')->whereStatus(1)->pluck('short_code')->toArray();
		foreach ($all_currencies as $key => $value) {
			$this->setExtraFields("price_in_".$value , "text", 'callbackPriceFields', "Price in ".$value);
		}
	}
	public function callbackPriceFields($row_data, $field, $action_type)
	{
		if($action_type == 'edit') {
			$price_array	=	json_decode($row_data->price);
			$field	=	str_ireplace('price_in_', '', $field);
			if(isset($price_array->$field)) {
				return $price_array->$field;
			} else {
				return null;
			}
		}
	}
	public function setPriceFieldValue()
	{
		$all_currencies = Currency::select('short_code')->whereStatus(1)->pluck('short_code')->toArray();
		$price	=	[];
		foreach ($all_currencies as $key => $value) {
            $price[$value]	=	$this->request->{"price_in_".$value};
        }
		$this->request->price = json_encode($price);
	}
	public function showPriceFromJson($row_data,$value,$type)
	{
		if($type == 'view' || $type == 'list') {
			$value	= str_ireplace(["{", "}", '"'], '', $value);
			return str_ireplace([",", ":"], [', ', ' : '], $value);
		}
	}
	public function fetchList(Request $request)
	{
		$currency_short_code	=	Currency::select('short_code')->whereStatus(1)->whereId($request->currency_id)->first();
		$dropdown_options		=	'<option> select </option>';
		if($currency_short_code) {
			$currency_short_code = $currency_short_code->short_code;
			$all_plans	=	SubscriptionPlan::whereStatus(1)->get()->toArray();
			foreach ($all_plans as $key => $value) {
				$price_array	=	json_decode($value['price'], true);
				$show_label	=	$value['plan_name'] .' ('.$value['active_user_count'] .' ' . str_plural('user', $value['active_user_count']).')';
				$show_label	.=	isset($price_array[$currency_short_code]) ? ' - '.$price_array[$currency_short_code] .' '. $currency_short_code : '';
				$selected 	=	$request->old_id == $value['id'] ? "selected" : "" ;
				$dropdown_options .= '<option '.$selected.' class="each_plan" data-user="'.$value['active_user_count'].'" value="'.$value['id'].'"> '.$show_label.'</option>';
			}
		}
		return $dropdown_options;
	}
	public function fetchDetails(Request $request)
	{
		if($request->subscriptionplan) {
			$user_count = $request->subscriptionplan->active_user_count;
			$expire_at	=	Carbon::now()->addMonthsWithOverflow($this->tenure_array[$request->subscriptionplan->tenure])->format('Y-m-d H:i:s');
			return $user_count.','.$expire_at;
		}
		return 0;
	}
}
