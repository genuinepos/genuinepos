<?php

namespace App\Http\Requests\Setups;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CashCounterStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('cash_counters_add') && config('generalSettings')['subscription']->features['cash_counter_count'] > 0;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'counter_name' => ['required', Rule::unique('cash_counters', 'counter_name')->where(function ($query) {
                return $query->where('branch_id', auth()->user()->branch_id);
            })],
            'short_name' => ['required', Rule::unique('cash_counters', 'short_name')->where(function ($query) {
                return $query->where('branch_id', auth()->user()->branch_id);
            })],
        ];
    }
}
