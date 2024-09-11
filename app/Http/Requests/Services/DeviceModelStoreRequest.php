<?php

namespace App\Http\Requests\Services;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DeviceModelStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('device_models_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'name' => ['required', 'max:100', Rule::unique('service_device_models', 'name')->where(function ($query) {
            //     return $query->where('branch_id', auth()->user()->branch_id);
            // })],
            'name' => ['required', 'max:100'],
            'service_checklist' => 'nullable|max:255',
        ];
    }
}
