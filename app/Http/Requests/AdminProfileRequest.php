<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Session;
use Illuminate\Validation\Rule;

class AdminProfileRequest extends FormRequest
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
        return [
            'full_name'  => 'required',
            'username' => 'required|max:10|unique:users,username,'.$user_id,
            'email' => 'required|email|unique:users,email,'.$user_id,
            'password' => 'confirmed',
            'mobile' => 'numeric|phone|required',
            'profile_image' => 'max:10000|mimes:jpeg,bmp,png'
        ];
    }
}
