<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Crud;
use App\Http\Traits\CrmFolderGenerator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\SubscriberRequest;
use App\Http\Requests\UpdateFullSubscriberDetails;
use App\Models\Subscriber;
use App\Mail\SendAuthenticationCodeForConfigFile;

class SubscriberCrud extends Crud
{
	use CrmFolderGenerator;
	/**
	 * name of the table . REQUIRED
	 * @var string
	 */
	public $table_name 				=	'subscribers';

	/**
	 * contain model path
	 * @var string
	 */
	public $model_path				=	'App\Models\\';

	/**
	 * route name that shold be used to create different action link. REQUIRED
	 * @var string
	 */
	public $route_slug 				=	'admin-subscription-management-companies-';
	/**
	 * You can use RBAC to manage action button by crud. OPTIONAL
	 * @var bool
	 */
	public $use_rbac				=	true;

	/**
	 * crud will check permission for this slug if rbac is true
	 * @var string
	 */
	public $module_slug				=	'companies-';
	/**
	 * You can customize you table coloumn.
	 *  field name as key, label as value. only table field are acceptable. OPTIONAL
	 * @var array
	 */
	public $columns_list			=	['company_name' => 'Company' ,'tenant_folder' => 'Link', 'email' => 'Email', 'subscription_plan_id' => 'Plan', 'expires_at' => 'Expires at'];
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
		$this->page_title = 'Company List';
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
		$this->page_title = 'View Company';
		$this->unset_coloumn =	array_merge($this->unset_coloumn,['password', 'db_host','db_name', 'db_user', 'db_password']);
		$data = $this->rendarView($id);
		$data['view_full_details_link']	=	$this->canView('company-config-file');
		$data['edit_full_details_link']	=	$this->canModify('company-config-file');
		$data['id']	=	$id;
		return view('admin.subscriber.view-company-details',$data);
	}
	/**
	 * This will load an insert form for current table
	 * @return view   load view page
	 */
	public function add()
	{
		$this->page_title = 'Add Company';
		$this->unset_relation_coloumn	=	['currency_id', 'subscription_plan_id'];
		array_push($this->unset_coloumn, 'active_user_count', 'expires_at', 'tenant_folder');
		$this->changeFieldType('subscription_plan_id','select','Subscription Plan',null, null,'subscription_dropdown');
		$data = $this->rendarAdd();
		$this->reOrderForm($data['input_list']);
		return view('admin.crud.form',$data);
	}
	/**
	 * This will insert data into databse
	 * @param  SubscriberRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function insert(SubscriberRequest $request)
	{
		$request->tenant_folder 		= 	$this->getCompanyFolderName($request->company_name);
		$this->request->tenant_folder	=	$request->tenant_folder;
		if($this->createCrmFolder()) {
			array_push($this->unset_coloumn, 'active_user_count', 'expires_at');
			$request->password 				= 	md5($request->password);
			$response 	 =  $this->insertData($request);
			$subscriber	 =	$this->current_model;
			$subscriber->active_user_count		=	$subscriber->subscriptionPlan->active_user_count;
	        $subscriber->expires_at				=	Carbon::now()->addMonthsWithOverflow($this->tenure_array[$subscriber->subscriptionPlan->tenure])->format('Y-m-d H:i:s');
			$subscriber->save();
			$this->writeConfig($subscriber);
			$log_details = 'subscribe crm with '.$subscriber->subscriptionPlan->plan_name.' for '.$subscriber->subscriptionPlan->active_user_count .' users upto '.$subscriber->expires_at;
			$subscriber->subscriptionLog()->create(['details' => $log_details]);
			$this->log($request->company_name .' company added');
			return redirect($response);
		} else {
			throwValidationError('db_name', $this->error);
		}
	}
	/**
	 * this will load edit form
	 * @param  integer $id id of this table
	 * @return view     load edit form
	 */
	public function edit($id)
	{
		$this->page_title = 'Edit Company';
		array_push($this->unset_coloumn, 'tenant_folder','subscription_plan_id', 'email',
		 'active_user_count', 'expires_at', 'password', 'db_host', 'db_name', 'db_user', 'db_password', 'timezone', 'country_id', 'currency_id', 'status');
		$data = $this->rendarEdit($id);
		return view('admin.crud.form',$data);
	}
	/**
	 * this will update a row
	 * @param  SubscriberRequest $request validated form request
	 * @return void                 redirect page
	 */
	public function update(SubscriberRequest $request)
	{
		array_push($this->unset_coloumn, 'tenant_folder','subscription_plan_id', 'email',
		 'active_user_count', 'expires_at', 'password', 'db_host', 'db_name', 'db_user', 'db_password', 'timezone', 'country_id', 'currency_id');
		 $this->log($request->subscriber->company_name .' company updated', null, (string)Subscriber::find($request->id));
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
		$html = '<a class="generate_password" href="javascript:void(0);">Generate Password</a>';
		$this->changeFieldType('db_password','password','db password', null, null, null, null, null, $html);
		$this->changeFieldType('timezone','select','Timezone', null, $this->get_timezones_list());
		// $this->changeFieldType('currency_id','select','Currency',null, null,'currency_dropdown');
		$this->setActionButton("History","btn bg-teal","info",route('admin-subscription-management-companies-subscription-logs-'),3);
		if($this->canModify('crm-modules-')) {
			$this->setActionButton("Modules","","",route('admin-subscription-management-companies-crm-modules-'),4);
		}
		$this->setRelation('currency_id', 'currencies', 'currency_name' , 'id', 'where status = 1');
		$this->setRelation('subscription_plan_id', 'subscription_plans', 'plan_name' , 'id', 'where status = 1');
		$this->addCallBackColoumn('tenant_folder', "Link", "showCrmAccessLink");
	}
	public function get_timezones_list()
    {
		$timezones 	= [];
		$lists		=	\DateTimeZone::listIdentifiers(\DateTimeZone::ASIA | \DateTimeZone::EUROPE | \DateTimeZone::AMERICA |
						\DateTimeZone::INDIAN | \DateTimeZone::AUSTRALIA | \DateTimeZone::AFRICA |
						\DateTimeZone::PACIFIC | \DateTimeZone::UTC);

		foreach ( $lists as $key => $value) {
			$timezones[$value] = $value;
		}
		return $timezones;
    }
	public function reOrderForm(&$array)
	{
		moveElement($array, 3, 1);
		moveElement($array, 4, 2);
		moveElement($array, 4, 3);
		moveElement($array, 13, 15);
	}
	public function loadScript()
	{
		return '
		$("#currency_id").change(function(){
			let currency_id = $(this).val();
			updateSubscriptionPlanDropdown(currency_id);
		});
		$("#subscription_plan_id").change(function(){
			let plan_id = $(this).val();
			if($("#active_user_count").length) {
				updatePlanDetails(plan_id);
			}
		});
		$(".generate_password").click(function(){
			let password = generatePassword();
			$("#db_password").attr("type", "text");
			$("#db_password").val(password);
		});
		$(document.body).on("click", ".each_plan", function() {
			if($("#active_user_count").length) {
				let active_user = $(this).attr("data-user");
				$("#active_user_count").val(active_user);
			}
		});
		$(document).ready(function() {
			if(currency_id = $("#currency_id").val()) {
				updateSubscriptionPlanDropdown(currency_id);
			}
		})

		function updateSubscriptionPlanDropdown(currency_id) {
			let old_id = $("#subscription_plan_id").val() ? $("#subscription_plan_id").val() : 0;
			console.log($("#subscription_plan_id").val());
			$.ajax({
				url : "'.route('admin-subscription-management-plans-fetch-list').'",
				data : {currency_id : currency_id, old_id : old_id },
				type : "POST",
				success : function(data) {
					$("#subscription_plan_id").html(data);
					$(".show-tick").selectpicker("refresh");
				}
			});
		}
		function updatePlanDetails(plan_id) {
			$.ajax({
				url : "'.route('admin-subscription-management-plans-fetch-details').'/" + plan_id,
				success : function(data) {
					if(data.length > 1) {
						let details = data.split(",");
						$("#active_user_count").val(details[0]);
						$("#expires_at").val(details[1]);
					}
				}
			});
		}
		';
	}
	public function showCrmAccessLink($row_data,$value,$type)
	{
		if($type =="list" || $type =="view"){

			return '<a href="'.getcompanyUrl($value.'/admin').'" target="_blank">Click Here</a>';
		}
		return $value;
	}
	/**
 *  it will generate a slug from company name
 *
 *  @param  string  $name  restaurant name
 *  @return  string  unique slug name
 */
	public function getCompanyFolderName($name)
	{
		$slug 	=	kebab_case($name);
		$accepted = false;
		while ($accepted == false) {
			$check  =	Subscriber::select('id')->where('tenant_folder', $slug)->first();
			if(isset($check->id)) {
				$accepted = false;
				$slug	=	$slug.'-'.mt_rand(0,99);
			}else {
				$accepted = true;
			}
		}
		return $slug;
	}

	/**
	 * this will send a code to logged in user and notification
	 * email to site admin to access sensitive info
	 *
	 * @param  Request $request
	 * @return integer          1 in success
	 */
	public function sendCode(Request $request)
	{
		$to_email	=	$this->user->email;
		$code		=	mt_rand(100000,999999);
		Session::put('access_token_to_'.$request->action,[ $code => $request->id]);

		Mail::to($to_email)->queue(new SendAuthenticationCodeForConfigFile($this->user, $code));
		$this->log("Requested to ". $request->action ." full details of ".$request->subscriber->company_name);

		if($this->user->role->site_admin != "yes") {
			$site_admin	=	$this->user->getParentSiteAdmin();
			Mail::to($site_admin->email)->queue(new SendAuthenticationCodeForConfigFile($site_admin, $this->user, $request->action.' full details', $request->subscriber->company_name));
		}
		return 1;
	}

	public function viewFullDetails(Request $request)
	{
		if($request->token && $id = session('access_token_to_view.'.$request->token)) {

			$this->page_title = 'View Company';
			$data = $this->rendarView($id);
			return view('admin.crud.view',$data);
		} else {
			$this->setFlashAlert('danger', 'Unauthorized access!');
			return back();
		}
	}
	public function editFullDetails(Request $request)
	{
		if($request->token && $id = session('access_token_to_edit.'.$request->token)) {

			$this->page_title = 'Edit Company';
			array_push($this->unset_coloumn,'email', 'country_id', 'timezone', 'password', 'tenant_folder');
			$this->changeFieldType('currency_id', 'hidden');
			$data = $this->rendarEdit($id);
			$this->reOrderFullEditForm($data['input_list']);
			$data['insert_url']	=	route('admin-subscription-management-companies-update-full-details');
			return view('admin.crud.form',$data);
		} else {
			$this->setFlashAlert('danger', 'Unauthorized access!');
			return back();
		}
	}

	public function updateFullDetails(UpdateFullSubscriberDetails $request)
	{
		array_push($this->unset_coloumn,'email', 'country_id', 'timezone', 'password', 'tenant_folder');
		if(!$request->db_password) {
			array_push($this->unset_coloumn, 'db_password');
		}
		$response 	 =  $this->updateData($request);
		$subscriber	 =	$this->current_model;
		$this->updateConfigDetails($subscriber);
		$log_details = 'update subscription crm with '.$subscriber->subscriptionPlan->plan_name.' for '.$subscriber->subscriptionPlan->active_user_count .' users upto '.$subscriber->expires_at;
		$subscriber->subscriptionLog()->create(['details' => $log_details]);
		$this->log($request->company_name .' subscription updated');
		return redirect($response);
	}
	public function reOrderFullEditForm(&$array)
	{
		moveElement($array, 5, 1);
		moveElement($array, 6, 2);
		moveElement($array, 12, 4);
	}
}
