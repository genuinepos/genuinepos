<?php

namespace Modules\SAAS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            // 'domain' => ['required', 'string'],
            'domain' => ['required', 'string', 'unique:domains,domain'],
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
