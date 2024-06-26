<?php

namespace App\Http\Requests\Services;

use Illuminate\Foundation\Http\FormRequest;

class JobCardChangeStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('job_cards_change_status');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'status_id.required' => __('Status is required'),
        ];
    }
}
