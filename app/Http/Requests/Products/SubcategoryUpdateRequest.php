<?php

namespace App\Http\Requests\Products;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class SubcategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('product_category_edit') && config('generalSettings')['subscription']->features['inventory'] == BooleanType::True->value;
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
            'parent_category_id' => 'required',
            'photo' => 'sometimes|image|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'parent_category_id.required' => __('Parent category field is required.'),
        ];
    }
}
