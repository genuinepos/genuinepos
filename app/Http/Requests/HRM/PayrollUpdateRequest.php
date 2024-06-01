<?php

namespace App\Http\Requests\HRM;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class PayrollUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('payrolls_edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'expense_account_id' => 'required',
            'amount_per_unit' => 'required',
            'duration_time' => 'required',
            'duration_unit' => 'required',
        ];
    }
}
