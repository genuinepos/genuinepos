<?php

namespace App\Http\Requests\Services;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class ServiceQuotationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('service_quotations_create') && isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::True->value;
    }

    public function rules(): array
    {
        return [
            'status' => 'required',
            'date' => 'required|date',
            'sale_account_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'sale_account_id.required' => __('Sales A/c is required'),
        ];
    }
}
