<?php

namespace App\Http\Requests\Sales;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class DiscountStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('discounts') && config('generalSettings')['subscription']->features['sales'] == BooleanType::True->value;
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
            'priority' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
            'discount_type' => 'required',
            'discount_amount' => 'required',
        ];
    }
}
