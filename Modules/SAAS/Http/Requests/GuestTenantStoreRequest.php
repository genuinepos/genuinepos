<?php

namespace Modules\SAAS\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class GuestTenantStoreRequest extends FormRequest
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
            'email' => 'required|email|max:191|unique:users,email',
            'phone' => 'required|max:60|unique:users,phone',
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

    protected function passedValidation()
    {
        $isIpAddressBlocked = User::where('ip_address', $this->ip())->exists();
        if ($isIpAddressBlocked) {
            throw ValidationException::withMessages([
                'ip_address' => ['Sorry, you already have an business registered.'],
            ]);
        }
    }
}
