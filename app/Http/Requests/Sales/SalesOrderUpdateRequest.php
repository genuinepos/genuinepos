<?php

namespace App\Http\Requests\Sales;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class SalesOrderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('sales_orders_edit') && config('generalSettings')['subscription']->features['sales'] == BooleanType::True->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required',
            'date' => 'required|date',
            'sale_account_id' => 'required',
            'account_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'sale_account_id.required' => 'Sales A/c is required',
            'account_id.required' => 'Debit A/c is required',
        ];
    }
}
