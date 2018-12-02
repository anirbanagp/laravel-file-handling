<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Cms_pageRequest extends FormRequest
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
            'title'  => 'required|max:50|unique:stf_cms_pages,title,'.$this->input('id', 0),
            'slug_name'  => 'required|max:50|unique:stf_cms_pages,slug_name,'.$this->input('id', 0),
            'content'  => 'required',
            'status'  => 'required',
        ];
    }
}
