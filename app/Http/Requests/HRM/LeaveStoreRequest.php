<?php

namespace App\Http\Requests\HRM;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class LeaveStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('leaves_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'leave_type_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ];
    }
}
