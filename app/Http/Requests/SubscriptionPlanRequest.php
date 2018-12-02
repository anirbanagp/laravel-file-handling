<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Currency;

class SubscriptionPlanRequest extends FormRequest
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

        $rules  =    [
            'plan_name'         => 'required|unique:subscription_plans,plan_name,'.$this->input( 'id', 0),
            'tenure'            => 'required',
            'active_user_count' => 'required|integer',
            'status'            => 'required'
        ];
        $all_currencies = Currency::select('short_code')->whereStatus(1)->pluck('short_code')->toArray();
        foreach ($all_currencies as $key => $value) {
            $rules["price_in_".$value]   =   'required|numeric';
        }
        return $rules;
    }
}
