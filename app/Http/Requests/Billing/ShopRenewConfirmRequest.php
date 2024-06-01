<?php

namespace App\Http\Requests\Billing;

use Illuminate\Foundation\Http\FormRequest;

class ShopRenewConfirmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('billing_renew_branch');
    }

    public function rules(): array
    {
        return [
            'net_total' => 'required|numeric|gt:0',
            'discount_percent' => 'required|numeric',
            'discount' => 'required|numeric',
            'total_payable' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
