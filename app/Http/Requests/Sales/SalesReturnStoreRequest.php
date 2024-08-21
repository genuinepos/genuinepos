<?php

namespace App\Http\Requests\Sales;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SalesReturnStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('create_sales_return') && config('generalSettings')['subscription']->features['sales'] == BooleanType::True->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'customer_account_id' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'sale_account_id' => 'required',
            'account_id' => 'required',
            'warehouse_id' => Rule::when(isset($request->warehouse_count) == true, 'required')
        ];
    }

    public function messages()
    {
        return [
            'sale_account_id.required' => __('Sale A/c is required.'),
            'account_id.required' => __('Credit field must not be empty.'),
            'payment_method_id.required' => __('Payment method field is required.'),
            'customer_account_id.required' => __('Customer is required.'),
        ];
    }
}
