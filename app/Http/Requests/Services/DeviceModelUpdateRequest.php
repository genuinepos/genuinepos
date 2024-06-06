<?php

namespace App\Http\Requests\Services;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DeviceModelUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'name' => ['required', 'max:100', Rule::unique('service_device_models', 'name')->where(function ($query) use ($id) {
                return $query->where('branch_id', auth()->user()->branch_id)->where('id', '!=', $id);
            })],
            'service_checklist' => 'nullable|max:255',
        ];
    }
}
