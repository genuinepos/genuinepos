<?php

namespace Modules\SAAS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRenewConfirmRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'net_total' => 'required|numeric',
            'total_payable' => 'required|numeric',
            'payment_status' => 'required',
            'payment_date' => 'required|date',
            'payment_method_name' => 'required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('tenants_upgrade_plan');
    }
}
