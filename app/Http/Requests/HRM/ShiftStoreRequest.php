<?php

namespace App\Http\Requests\HRM;

use Illuminate\Foundation\Http\FormRequest;

class ShiftStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('shifts_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:hrm_shifts,name',
            'start_time' => 'required',
            'end_time' => 'required',
        ];
    }
}
