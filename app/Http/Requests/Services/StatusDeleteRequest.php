<?php

namespace App\Http\Requests\Services;

use Illuminate\Foundation\Http\FormRequest;

class StatusDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('status_delete') && isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::False->value;
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
