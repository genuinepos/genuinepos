<?php

namespace App\Http\Requests\Products;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class UnitCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('product_unit_add') && config('generalSettings')['subscription']->features['inventory'] == BooleanType::True->value;
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
