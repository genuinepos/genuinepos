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
            'fullname' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email',
            'currency_id' => 'required',
            'phone' => 'required|max:60',
            'password' => 'required|confirmed',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}