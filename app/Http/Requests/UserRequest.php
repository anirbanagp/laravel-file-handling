<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserRequest extends FormRequest
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
		$user_id = $this->input('id') ?  $this->input('id') : 0;
		$rules = [
            'full_name'    => 'required|max:50',
            'role_id'       => 'required',
            'username'      => 'required|max:20|unique:users,username,'.$user_id,
            'parent_id'     => 'required',
            'password'      => 'max:10',
			'mobile'        => 'numeric|phone',
			'status'        => 'required'
        ];
		if(!$this->input('id')){
			$rules['email'] = 'required|email|unique:users,email';
		}
        if($this->parent_id) {
            $parent_role	=	User::select('id', 'role_id')->whereId($this->parent_id)->first();
            $child_roles	=	array_keys(getChildRoles($parent_role->role_id));
            $rules['role_id'] = [   'required',
                                    Rule::in($child_roles),
                                ];

        }
		return $rules;
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'role_id.required'  => 'Role field is required.',
            'role_id.in'        => 'Parent is invalid for this role.',
            'parent_id.required'=> 'Parent field is required.',
        ];
    }
}
