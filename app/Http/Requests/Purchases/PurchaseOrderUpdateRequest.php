<?php

namespace App\Http\Requests\Purchases;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('purchase_order_edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'supplier_account_id' => 'required',
            'date' => 'required|date',
            'delivery_date' => 'required|date',
            'payment_method_id' => 'required',
            'purchase_account_id' => 'required',
            'account_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'purchase_account_id.required' => __('Purchase A/c is required.'),
            'account_id.required' => __('Credit A/c is required.'),
            'payment_method_id.required' => __('Payment method field is required.'),
            'supplier_account_id.required' => __('Supplier is required.'),
        ];
    }
}
