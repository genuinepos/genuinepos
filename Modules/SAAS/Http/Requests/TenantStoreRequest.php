<?php

namespace Modules\SAAS\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class TenantStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'plan_id' => 'required|numeric',
            'shop_count' => 'required|numeric',
            'name' => 'required|string|max:70',
            'domain' => ['required', 'string', 'max:60', 'unique:domains,domain', 'regex:/^[a-zA-Z0-9\-]+$/'],
            'fullname' => 'required|string|max:191',
            'email' => 'required|unique:users,email',
            'currency_id' => 'required',
            'phone' => 'required|max:60|unique:users,phone',
            'payment_status' => Rule::when($request->is_trial_plan == 0, 'required|numeric'),
            'repayment_date' => Rule::when($request->is_trial_plan == 0 &&  $request->payment_status == 0, 'required'),
            'username' => 'required|min:5|max:25',
            'password' => 'required|confirmed',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('tenants_create');
    }

    public function messages()
    {
        return [
            'domain.unique' => 'Selected domain is already taken. Try other domain names.',
            'domain.regex' => '[. , @ $ ~`] characters in not valid for store url.',
        ];
    }
}
