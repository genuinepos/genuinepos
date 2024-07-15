<?php

namespace App\Http\Requests\Users;

use App\Models\Role;
use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\Users\RoleService;
use App\Services\Users\UserService;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('user_edit') && config('generalSettings')['subscription']->features['users'] == BooleanType::True->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request, UserService $userService, RoleService $roleService): array
    {
        $id = $this->route('id');
        $user = $userService->singleUser(id: $id);
        $role = $roleService->singleRole(id: $request->role_id);

        $roleId = $user?->roles()?->first()?->id;

        return [
            'first_name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'photo' => 'nullable|file|mimes:png,jpg,jpeg,gif,webp',
            'branch_id' => Rule::when(
                auth()->user()->can('has_access_to_all_area') &&
                    !$role?->hasPermissionTo('has_access_to_all_area') &&
                    auth()->user()->is_belonging_an_area == BooleanType::False->value &&
                    config('generalSettings')['subscription']->has_business == BooleanType::True->value &&
                    $request->branch_count,
                'required'
            ),
            'role_id' => Rule::when($request->allow_login == BooleanType::True->value && $roleId != 1, 'required'),
            'username' => Rule::when($request->allow_login == BooleanType::True->value, 'required|unique:users,username,' . $id),
            'password' => $request->allow_login == BooleanType::True->value && !$user->password ? 'required|confirmed' : 'sometimes|confirmed',
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
