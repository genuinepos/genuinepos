<?php

namespace App\Http\Requests\TaskManagement;

use Illuminate\Foundation\Http\FormRequest;

class TodoUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('todo_edit') && config('generalSettings')['subscription']->features['task_management'] == 1;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'task' => 'required',
            'priority' => 'required',
            'status' => 'required',
            'due_date' => 'required|date',
            "user_ids"    => "required|array",
            "user_ids.*"  => "required",
        ];
    }
}
