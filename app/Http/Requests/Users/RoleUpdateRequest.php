<?php

namespace App\Http\Requests\Users;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('role_edit') && config('generalSettings')['subscription']->features['users'] == BooleanType::True->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'role_name' => 'required|unique:roles,name,' . $id,
        ];
    }
}
