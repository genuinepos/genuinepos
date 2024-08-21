<?php

namespace Modules\SAAS\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExpireDateConfirmRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(Request $request): array
    {
        return [
            'business_new_expire_date' => Rule::when(isset($request->business_new_expire_date) == true, 'required'),
            'shop_new_expire_dates' => 'required',
            'shop_new_expire_dates.*' => 'required|date',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('tenants_update_expire_date');
    }
}
