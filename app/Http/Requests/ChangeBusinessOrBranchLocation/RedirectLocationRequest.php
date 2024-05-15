<?php

namespace App\Http\Requests\ChangeBusinessOrBranchLocation;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RedirectLocationRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        return [
            'select_type' => 'required',
            'branch_id' => Rule::when($request->select_type == 'shop', 'required'),
        ];
    }

    public function messages()
    {
        return [
            'branch_id.required' => 'Please select a shop.'
        ];
    }
}
