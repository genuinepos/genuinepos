<?php

namespace Modules\SAAS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:users,email,'.$this->user->id,
            'phone' => 'nullable|max:255',
            'photo' => ['nullable', 'mimes:png,jpeg,jpg', 'max:2048'],
            'address' => 'nullable',
            'language' => 'required',
            'role_id' => 'required',
            'password' => ['nullable', Password::default()],
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
}
