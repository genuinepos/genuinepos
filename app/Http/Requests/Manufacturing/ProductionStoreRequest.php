<?php

namespace App\Http\Requests\Manufacturing;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('production_add') && config('generalSettings')['subscription']->features['manufacturing'] == BooleanType::True->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'process_id' => 'required',
            'date' => 'required|date',
            'total_output_quantity' => 'required',
            'total_final_output_quantity' => 'required',
            'net_cost' => 'required',
            'store_warehouse_id' => Rule::when(isset($request->store_warehouse_count) && $request->store_warehouse_count > 0, 'required'),
        ];
    }

    public function messages()
    {
        return [
            'process_id.required' => 'Please select the product'
        ];
    }
}
