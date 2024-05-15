<?php

namespace App\Http\Requests\Setups;

use Illuminate\Foundation\Http\FormRequest;

class AddBusinessConfirmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('billing_business_add');
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
