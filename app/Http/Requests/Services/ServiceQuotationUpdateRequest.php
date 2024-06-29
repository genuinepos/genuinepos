<?php

namespace App\Http\Requests\Services;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class ServiceQuotationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('service_quotations_edit') && isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::True->value;
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
            'sale_account_id' => 'required'
        ];
    }
}
