<?php

namespace App\Http\Requests\HRM;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class PayrollPaymentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('payroll_payments_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'paying_amount' => 'required',
            'payment_method_id' => 'required',
            'credit_account_id' => 'required',
            'debit_account_id' => 'required',
            'payroll_id' => 'required',
        ];
    }
}
