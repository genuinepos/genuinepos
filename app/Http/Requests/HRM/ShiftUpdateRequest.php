<?php

namespace App\Http\Requests\HRM;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class ShiftUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('shifts_edit') && config('generalSettings')['subscription']->features['hrm'] == BooleanType::True->value;
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
            'name' => 'required|unique:hrm_shifts,name,' . $id,
            'start_time' => 'required',
            'end_time' => 'required',
        ];
    }
}
