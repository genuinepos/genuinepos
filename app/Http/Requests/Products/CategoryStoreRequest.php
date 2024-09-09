<?php

namespace App\Http\Requests\Products;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('product_category_add');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', Rule::unique('categories')->where(function ($query) {
                return $query->where('parent_category_id', null);
            })],
            'photo' => 'sometimes|image|max:2048',
        ];
    }
}
