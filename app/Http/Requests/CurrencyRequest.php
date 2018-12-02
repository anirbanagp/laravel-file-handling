<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
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
            'currency_name'  => 'required|unique:currencies,currency_name,'.$this->input('id', 0),
            'short_code'    => 'required|regex:/^\S*$/|unique:currencies,short_code,'.$this->input('id', 0),
            'status'        => 'required'
        ];
    }
    public function messages()
    {
        return [
            'short_code.regex'    =>  'Short code should not contain spaces.',
        ];
    }

}
