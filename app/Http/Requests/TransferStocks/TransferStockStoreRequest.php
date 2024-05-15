<?php

namespace App\Http\Requests\TransferStocks;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class TransferStockStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('transfer_stock_create');
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
            'receiver_branch_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'receiver_branch_id.required' => __('Receiver branch is required.')
        ];
    }
}
