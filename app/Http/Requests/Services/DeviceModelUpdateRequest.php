<?php

namespace App\Http\Requests\Services;

use App\Enums\BooleanType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DeviceModelUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('device_models_edit') && isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::True->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('id');
        return [
            // 'name' => ['required', 'max:100', Rule::unique('service_device_models', 'name')->where(function ($query) use ($id) {
            //     return $query->where('branch_id', auth()->user()->branch_id)->where('id', '!=', $id);
            // })],
            'name' => ['required', 'max:100'],
            'service_checklist' => 'nullable|max:255',
        ];
    }
}
