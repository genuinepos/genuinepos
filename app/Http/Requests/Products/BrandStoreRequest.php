<?php

namespace App\Http\Requests\Products;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class BrandStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // $inventory = (config('generalSettings')['subscription']->features['inventory'] == BooleanType::True->value) ? true : false;
        // $services = (isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::True->value) ? true : false;

        // $inventoryOrServices = ($inventory || $services) ? true : false;

        return auth()->user()->can('product_brand_add') && config('generalSettings')['subscription']->features['inventory'] == BooleanType::True->value;
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
            'photo' => 'sometimes|image|max:2048',
        ];
    }
}
