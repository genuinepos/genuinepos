<?php

namespace Modules\SAAS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddShopConfirmRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('tenants_upgrade_plan');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'increase_shop_count' => 'required|numeric|gt:0',
            'shop_price_period_count' => 'required|numeric|gt:0',
            'net_total' => 'required|numeric|gt:0',
            'discount_percent' => 'required|numeric',
            'discount' => 'required|numeric',
            'total_payable' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
