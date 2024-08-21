<?php

namespace Modules\SAAS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|max:255',
            'photo' => ['nullable', 'mimes:png,jpeg,jpg', 'max:2048'],
            'address' => 'nullable',
            'language' => 'required',
            'role_id' => 'required',
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
        return auth()->user()->can('users_create');
    }
}
