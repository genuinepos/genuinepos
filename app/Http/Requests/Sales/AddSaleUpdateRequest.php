<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;

class AddSaleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('edit_add_sale');
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
            'sale_account_id.required' => __('Sales A/c is required'),
            'account_id.required' => __('Debit A/c is required'),
        ];
    }
}
