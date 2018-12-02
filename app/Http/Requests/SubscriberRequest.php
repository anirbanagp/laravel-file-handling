<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'company_name'  =>  'required',
            'email'         =>  'required|email|unique:subscribers,email,'.$this->input('id', 0),
            'subscription_plan_id'=>  'required|integer',
            'first_name'    =>  'required',
            'last_name'     =>  'required',
            'password'      =>  'required|min:6',
            'db_host'       =>  'required|ipv4',
            'db_name'       =>  'required|regex:/^\S*$/|unique:subscribers,db_name,'.$this->input('id', 0),
            'db_user'       =>  'required|regex:/^\S*$/|unique:subscribers,db_user,'.$this->input('id', 0),
            'db_password'   =>  'required|min:6',
            'status'        =>  'required',
            'timezone'      =>  'required',
        ];
        if($this->input('id', 0)) {
            $rules  =   [];
            $rules['first_name']            =   'required';
            $rules['last_name']             =   'required';
        }
        return $rules;
    }
    public function messages()
    {
        return [
            'db_name.regex'         =>  'Db name should not contain spaces.',
            'db_user.regex'         =>  'Db username should not contain spaces.',
            'subscription_plan_id.required' =>  'Subscription plan field is required.',
        ];
    }
}
