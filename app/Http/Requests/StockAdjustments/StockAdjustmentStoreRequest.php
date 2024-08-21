<?php

namespace App\Http\Requests\StockAdjustments;

use Illuminate\Foundation\Http\FormRequest;

class StockAdjustmentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('stock_adjustment_add');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required',
            'type' => 'required',
            'expense_account_id' => 'required',
            'account_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'expense_account_id.required' => __('Expense Ledger A/c is required.'),
            'account_id.required' => __('Debit A/c is required.'),
        ];
    }
}
