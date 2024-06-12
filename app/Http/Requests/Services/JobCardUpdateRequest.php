<?php

namespace App\Http\Requests\Services;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class JobCardUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('job_cards_edit') && isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::True->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_account_id' => 'required',
            'date' => 'required|date',
            'service_type' => 'required',
            'status_id' => 'required',
            'document' => 'sometimes|max:1024',
        ];
    }

    public function messages()
    {
        return [
            'customer_account_id.required' => __('Customer is required'),
            'status_id.required' => __('Status is required'),
        ];
    }
}
