<?php

namespace App\Http\Requests\Contacts;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class MoneyReceiptUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('money_receipt_edit') && config('generalSettings')['subscription']->features['contacts'] == BooleanType::True->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date'
        ];
    }
}
