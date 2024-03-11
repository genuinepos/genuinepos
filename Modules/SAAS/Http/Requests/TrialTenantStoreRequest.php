<?php

namespace Modules\SAAS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrialTenantStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'plan_id' => 'required|numeric',
            'name' => 'required|string|max:70',
            'domain' => ['required', 'string', 'max:60', 'unique:domains,domain', 'regex:/^[a-zA-Z0-9\-]+$/'],
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|min:60',
            'currency_id' => 'required',
            'phone' => 'required|max:60',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            'domain.regex' => '[. , @ $ ~ ` ^ # @ etc] characters in not valid for store url.',
        ];
    }
}
