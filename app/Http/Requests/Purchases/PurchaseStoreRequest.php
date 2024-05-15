<?php

namespace App\Http\Requests\Purchases;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PurchaseStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('purchase_add');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'supplier_account_id' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'purchase_account_id' => 'required',
            'account_id' => 'required',
            'warehouse_id' => Rule::when(isset($request->warehouse_count) == true, 'required')
        ];
    }

    public function messages()
    {
        return [
            'purchase_account_id.required' => __('Purchase A/c is required.'),
            'account_id.required' => __('Credit Account field must not be is empty.'),
            'payment_method_id.required' => __('Payment method field is required.'),
            'supplier_account_id.required' => __('Supplier is required.'),
            'warehouse_id.required' => __('Warehouse is required.'),
        ];
    }
}
