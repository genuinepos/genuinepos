<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;

class CashRegisterStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('pos_add');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cash_counter_id' => 'required',
            'sale_account_id' => 'required',
            'cash_account_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'sale_account_id.required' => __('Sale A/c is required'),
            'cash_account_id.required' => __('Cash A/c is required'),
        ];
    }
}
