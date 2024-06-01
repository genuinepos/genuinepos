<?php

namespace App\Http\Requests\Billing;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class AddShopConfirmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('billing_branch_add') && config('generalSettings')['subscription']->has_due_amount == BooleanType::False->value;
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
