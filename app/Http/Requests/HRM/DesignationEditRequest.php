<?php

namespace App\Http\Requests\HRM;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class DesignationEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('designations_edit') && config('generalSettings')['subscription']->features['hrm'] == BooleanType::True->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
