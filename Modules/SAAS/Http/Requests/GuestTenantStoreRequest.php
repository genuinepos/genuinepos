<?php

namespace Modules\SAAS\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Modules\SAAS\Entities\Plan;

class GuestTenantStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'plan_id' => 'required|numeric',
            'shop_count' => 'required|numeric',
            'name' => 'required|string|max:70',
            'domain' => ['required', 'string', 'max:60', 'unique:domains,domain', 'regex:/^[a-zA-Z0-9\-]+$/'],
            'fullname' => 'required|string|max:191',
            'email' => 'required|email',
            'phone' => 'required|max:60',
            'currency_id' => 'required',
            'username' => 'required|min:5|max:25',
            'password' => 'required|string|min:6|confirmed',
            // 'password' => ['required', Password::default()],
        ];

        // if (!config('app.debug')) {
        //     $rules['g-recaptcha-response'] = 'required|captcha';
        // }

        return $rules;
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
            'domain.regex' => '[. , @ $ ~ ` ^ # @ etc] characters in not valid for store url.',
        ];
    }

    // protected function passedValidation()
    // {
    //     $isIpAddressBlocked = User::where('ip_address', $this->ip())->exists();
    //     $isTrial = Plan::find($this->plan_id)->price == 0;

    //     if ($isIpAddressBlocked && $isTrial) {
    //         throw ValidationException::withMessages([
    //             'ip_address' => ['Sorry, you already have an business registered.'],
    //         ]);
    //     }
    // }
}
