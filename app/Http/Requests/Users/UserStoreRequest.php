<?php

namespace App\Http\Requests\Users;

use App\Models\Role;
use App\Enums\BooleanType;
use App\Services\Users\RoleService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('user_add');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request, RoleService $roleService): array
    {
        $role = $roleService->singleRole(id: $request->role_id);
        
        return [
            'first_name' => 'required',
            'email' => 'required|unique:users,email',
            'sales_commission_percent' => 'nullable|integer|min:1',
            'photo' => 'nullable|file|mimes:png,jpg,jpeg,gif,webp',
            'branch_id' => Rule::when(
                auth()->user()->can('has_access_to_all_area') &&
                    !$role?->hasPermissionTo('has_access_to_all_area') &&
                    auth()->user()->is_belonging_an_area == BooleanType::False->value &&
                    config('generalSettings')['subscription']->has_business == BooleanType::True->value &&
                    $request->branch_count,
                'required'
            ),
            'role_id' => Rule::when($request->allow_login == BooleanType::True->value, 'required'),
            'username' => Rule::when($request->allow_login == BooleanType::True->value, 'required|unique:users,username'),
            'password' => Rule::when($request->allow_login == BooleanType::True->value, 'required|confirmed'),
        ];
    }

    public function messages()
    {
        return [
            'role_id.required' => __('Role is required.'),
            'branch_id.required' => __('Shop/Business is required.'),
        ];
    }
}
