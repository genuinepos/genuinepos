<?php

namespace App\Http\Requests\Products;

use App\Enums\BooleanType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UnitStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('product_unit_add');
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
            'short_name' => 'required',
            'base_unit_multiplier' => Rule::when($request->as_a_multiplier_of_other_unit == BooleanType::True->value, 'required|numeric'),
            'base_unit_id' =>  Rule::when($request->as_a_multiplier_of_other_unit == BooleanType::True->value, 'required'),
        ];
    }

    public function messages()
    {
        return [
            'base_unit_multiplier.required' => __('Amount field is required'),
            'base_unit_id.required' => __('Base unit field is required'),
        ];
    }
}
