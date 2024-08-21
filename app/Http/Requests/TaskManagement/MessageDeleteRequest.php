<?php

namespace App\Http\Requests\TaskManagement;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class MessageDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('messages_delete') && config('generalSettings')['subscription']->features['task_management'] == BooleanType::True->value;
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
