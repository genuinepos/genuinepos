<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class QuickProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('product_add');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'code' => 'sometimes|unique:products,product_code',
            'unit_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'unit_id.required' => __('Product unit field is required.'),
        ];
    }
}
