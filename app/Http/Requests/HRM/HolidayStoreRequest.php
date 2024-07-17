<?php

namespace App\Http\Requests\HRM;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class HolidayStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('holidays_create') && config('generalSettings')['subscription']->features['hrm'] == BooleanType::True->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'allowed_branch_ids' => Rule::when(isset($request->allowed_branch_count) == true, 'required|array'),
            'allowed_branch_ids.*' => Rule::when(isset($request->allowed_branch_count) == true, 'required'),
        ];
    }

    public function messages()
    {
        return [
            'allowed_branch_ids.required' => __('Allowed Store/Company is required')
        ];
    }
}
