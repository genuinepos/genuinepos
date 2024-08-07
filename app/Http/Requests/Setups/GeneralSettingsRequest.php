<?php

namespace App\Http\Requests\Setups;

use Illuminate\Foundation\Http\FormRequest;

class GeneralSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('business_or_shop_settings');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'business_name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'currency_id' => 'required',
            'account_start_date' => 'required|date',
            'business_logo' => 'sometimes|image|max:1024',
        ];
    }
}
