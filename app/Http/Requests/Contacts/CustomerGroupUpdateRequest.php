<?php

namespace App\Http\Requests\Contacts;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class CustomerGroupUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('customer_group');
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
            'name' => 'required|unique:customer_groups,name,' . $id,
        ];
    }
}
