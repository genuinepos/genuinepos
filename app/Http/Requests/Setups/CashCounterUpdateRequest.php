<?php

namespace App\Http\Requests\Setups;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CashCounterUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('cash_counters_edit') && config('generalSettings')['subscription']->features['cash_counter_count'] > 0;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        $id = $this->route('id');
        $branchId = isset($request->branch_id) ? $request->branch_id : null;
        return [
            'counter_name' => ['required', Rule::unique('cash_counters', 'counter_name')->where(function ($query) use ($branchId, $id) {
                return $query->where('branch_id', $branchId)->where('id', '!=', $id);
            })],
            'short_name' => ['required', Rule::unique('cash_counters', 'short_name')->where(function ($query) use ($branchId, $id) {
                return $query->where('branch_id', $branchId)->where('id', '!=', $id);
            })],
        ];
    }
}
