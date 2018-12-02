<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Session;
use App\Models\User;
use Mail;
use App\Mail\resetpass;
use App\Models\UserProfile;
use DB;
use App\Http\Requests\AdminProfileRequest;
use App\Http\Traits\DataSaver;
use App\SupportTicket\StMessage;

class Authentication extends AdminBaseController
{
    use DataSaver;

    public $reset_pass_url = false;

    /**
     * this is index function. It will load the admin dashboard page after authentication
     */
    public function index()
    {
        $this->setLeftSideBarData();
        $dashboard_data = array();
		$user_count = 0;
        $user_id = Session::get('admin_details')['id'];
        return view('admin/dashboard/admin_dashboard',['dashboard_data' => $dashboard_data,'profile_image' => $this->profile_image,'parent_menu' => $this->parent_menu, 'sub_menu' =>$this->sub_menu]);
    }
    /**
     * this function is for load coming soon page
     */
    public function getComingSoon()
    {
        $this->setLeftSideBarData();

        return view('admin/admin_coming_soon',['profile_image' => $this->profile_image,'parent_menu' => $this->parent_menu, 'sub_menu' =>$this->sub_menu]);
    }
    /**
     * this is for load admin login page
     */
    public function getLogin()
    {
        $user_details = Session::get('admin_details');
        if($user_details){
            return redirect(route('admin-dashboard'));
        }
        else {
            return view('admin/admin_login');
        }
    }
    /**
     * this function will check admin login with correct email & password
     * @param  Request $request [description]
     * @var string
     */
    public function postLogin(Request $request)
    {
        if(($request->input('email'))&&($request->input('password')))
        {
            $email = $request->input('email');
            $password = $request->input('password');
			$condition = [
				'email' => $email,
				'password' => md5($password),
				'status' => 'active',
			];
			$user = User::where($condition)->with('role')->first();
            if(isset($user->id) && $user->role->status == 'active' && $user->role->is_admin == 'yes')
            {
                $this->setUserDetailsIntoSessionAdmin($user->id);
                $userid = $user->id;
                $username = $user->username;
                $this->user_id=$userid;
                $this->role_id=$user->role_id;
                $this->log('Logged In From Admin');
                return redirect(route('admin-dashboard'));
            }
            else
            {
                Session::flash('alert_class', 'danger');
                Session::flash('alert_msg', 'Invalid Email/Password!');
                return view('admin/admin_login');
            }
        }
        else
        {
            return view('admin/admin_login');
        }
    }
    /**
     * this is for load forgot password page
     */
    public function getForgotPassword()
    {
        $user_id = Session::get('admin_details');

        if($user_id){
            return redirect(route('admin-dashboard'));
        }
        else {
            return view('admin/admin_forgot_password');
        }
    }
    /**
     * this function is for forgot password
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postForgotPassword(Request $request)
    {
        if($request->input('email'))
        {
            $email = $request->input('email');
            $user = User::where('email', $email)
               ->where('status', 'active')
               ->where('role_id', '<', 4)
               ->whereNull('deleted_at')
               ->get()
               ->toArray();
            if($user) {
                $unique_code = str_random(50);
                $reset_pass_url = url('/').'/'.ADMIN_PATH.'/reset-password/'.$unique_code;
                $this->reset_pass_url = $reset_pass_url;
                User::where('id', $user[0]['id'])
                  ->update(['unique_code' => $unique_code]);

                $to_email = $email;
                Mail::to($to_email)->queue(new resetpass($this));

                $this->log('Reset Password Link Sent From Admin',$user[0]['id']);

                Session::flash('alert_class', 'success');
                Session::flash('alert_msg', 'An email has been sent to your email address for recover your password.');
                return redirect(route('admin-get-forgot-password'));
            }
            else {
                Session::flash('alert_class', 'danger');
                Session::flash('alert_msg', 'Invalid Email!');
                return redirect(route('admin-get-forgot-password'));
            }
        }
    }
    /**
     * this is for load reset password page with an unique code
     * @param integer $unique_code user can get unique code by email
     */
    public function getResetPassword($unique_code)
    {
        $user = User::where('unique_code', $unique_code)
               ->where('status', 'active')
               ->whereNull('deleted_at')
               ->get()
               ->toArray();
        if($user){
            Session::put('unique_code', $unique_code);
            return view('admin/admin_reset_password');
        }
        else {
            Session::flash('alert_class', 'danger');
            Session::flash('alert_msg', 'Invalid URL');
            return redirect(route('admin-get-forgot-password'));
        }
    }
    /**
     * this is for modify password
     * @param Request $request [description]
     */
    public function postResetPassword(Request $request)
    {
        if(($request->input('password'))&&($request->input('confirm_password')))
        {
            $unique_code = Session::get('unique_code');
            $password = $request->input('password');
            $confirm_password = $request->input('confirm_password');
            if($password==$confirm_password)
            {
                $user = User::where('unique_code', $unique_code)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->get()
                ->toArray();

                if($user)
                {
                    $user_id = $user[0]['id'];
                    $user   =   User::find($user_id);
                    User::where('id', $user_id)
                      ->update(['unique_code' => '', 'password'=>md5($password)]);
                      $this->log('Password Changed From Admin',$user_id, $user->toJson());

                      Session::flush();
                      Session::flash('alert_class', 'success');
                      Session::flash('alert_msg', 'Your password has been changed successfully');
                      return redirect(route('admin-login'));
                }
                else {
                    Session::flash('alert_class', 'dabger');
                    Session::flash('alert_msg', 'Account Deleted');
                    return redirect(route('admin-login'));
                }
            }
            else {
                Session::flash('alert_class', 'danger');
                Session::flash('alert_msg', 'Password & Confirm Password do not match!');
                $return_url = url('/').'/'.ADMIN_PATH.'/reset-password/'.$unique_code;
                return redirect($return_url);
            }
        }
        else {
            return redirect(route('admin-get-reset-password'));
        }
    }
    /**
     * This function is for load profile settings page
     */
    public function getProfileSettings()
    {
        $this->setLeftSideBarData();
        $user_id = $this->user_id;
        $where = array('users.id' =>$user_id);
        $user = DB::table('users')
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->where($where)
            ->whereNull('deleted_at')
            ->get();
        return view('admin/dashboard/admin_profile_settings',['user_details' => $user, 'profile_image' => $this->profile_image,'parent_menu' => $this->parent_menu, 'sub_menu' =>$this->sub_menu]);
    }
    /**
     * this function is for post profile settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postProfileSettings(AdminProfileRequest $request)
    {
        $user_id = Session::get('admin_details')['id'];
        $full_name = $request->input('full_name');
        $email = $request->input('email');
        $password = $request->input('password_confirmation');
        $updated_at = date("Y-m-d H:i:s");

        if($password)
        {
            $user_update = array('full_name' => $full_name, 'email' => $email, 'password' => md5($password), 'updated_at' => $updated_at);
        }
        else
        {
            $user_update = array('full_name' => $full_name, 'email' => $email, 'updated_at' => $updated_at);
        }

        $this->log('user details Updated ', null, (string)User::find($user_id));
        DB::table('users')
        ->where('id', $user_id)
        ->update($user_update);

        if($request->file('profile_image'))
        {
            $profile_image = $request->file('profile_image')->store('user_image');
            $this->log('user profile Updated ', null, (string)UserProfile::whereUserId($user_id)->first());
            DB::table('user_profiles')
            ->where('user_id', $user_id)
            ->update(['profile_image' => $profile_image, 'updated_at' => $updated_at]);
        }
        $this->setUserDetailsIntoSessionAdmin();

        $this->setFlashAlert('success', 'Successfully Updated');
        return redirect(route('admin-profile-settings'));
    }
    /**
     * this function is for admin logout
     * @return [type] [description]
     */
    public function postLogout()
    {
        $this->log('Logged Out From Admin');
        Session::flush();
        return redirect(route('admin-login'));
    }

}
