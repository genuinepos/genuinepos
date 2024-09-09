<?php

namespace App\Http\Requests\Products;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('product_edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        $id = $this->route('id');
        return [
            'name' => 'required',
            'code' => 'sometimes|unique:products,product_code,' . $id,
            'unit_id' => 'required',
            'photo' => 'sometimes|image|max:2048',
            'variant_image.*' => Rule::when($request->is_variant == BooleanType::True->value, 'sometimes|image|max:1024'),
        ];
    }

    public function messages()
    {
        return [
            'unit_id.required' => __('Product unit field is required.')
        ];
    }
}
