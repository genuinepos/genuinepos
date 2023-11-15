<?php

namespace Modules\SAAS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class TenantStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:70',
            'domain' => ['required', 'string', 'max:60', 'unique:domains,domain'],
            'fullname' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|max:60',
            'password' => ['required', Password::default()],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'domain.unique' => 'Selected domain is already taken. Try other domain names.',
        ];
    }
}
