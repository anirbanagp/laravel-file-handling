<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StStatusTypeRequest extends FormRequest
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
            'name' => 'required|unique:stm_st_status_types,name,'.$this->input('id', 0),
            'is_view' => 'required',
            'status' => 'required',
        ];
    }
}
