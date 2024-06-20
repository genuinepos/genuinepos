<?php

namespace App\Http\Requests\Services;

use App\Enums\BooleanType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StatusStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('status_create') && isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::True->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:50', Rule::unique('service_status', 'name')->where(function ($query) {
                return $query->where('branch_id', auth()->user()->branch_id);
            })],
            'sort_order' => ['nullable', Rule::unique('service_status', 'sort_order')->where(function ($query) {
                return $query->where('branch_id', auth()->user()->branch_id);
            })],
            'email_subject' => 'nullable|max:255',
        ];
    }
}
