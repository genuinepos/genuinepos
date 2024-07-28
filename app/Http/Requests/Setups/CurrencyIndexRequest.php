<?php

namespace App\Http\Requests\Setups;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class CurrencyIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('currencies_index') && config('generalSettings')['subscription']->features['setup'] == BooleanType::True->value;
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
