<?php

namespace App\Http\Requests\Setups;

use Illuminate\Foundation\Http\FormRequest;

class CashCounterDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('cash_counters_delete') && config('generalSettings')['subscription']->features['cash_counter_count'] > 0;
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
