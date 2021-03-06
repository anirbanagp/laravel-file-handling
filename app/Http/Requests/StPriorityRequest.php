<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StPriorityRequest extends FormRequest
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
        return [
            'name' => 'required|unique:stm_st_priorities,name,'.$this->input('id', 0),
            'status' => 'required',
        ];
    }
}
