<?php

namespace Modules\SAAS\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PlanUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(Request $request): array
    {
        $id = $this->route('plan');
        return [
            'name' => 'required|unique:plans,name,' . $id,
            'price_per_month' => Rule::when($request->is_trial_plan == 0, 'required|numeric'),
            'price_per_year' => Rule::when($request->is_trial_plan == 0, 'required|numeric'),
            'lifetime_price' => Rule::when($request->is_trial_plan == 0 && $request->has_lifetime_period == 1, 'required|numeric'),
            'applicable_lifetime_years' => Rule::when($request->is_trial_plan == 0 && $request->has_lifetime_period == 1, 'required|numeric'),
            'currency_id' => Rule::when($request->is_trial_plan == 0, 'required'),
            'trial_days' => Rule::when($request->is_trial_plan == 1, 'required|numeric'),
            'trial_shop_count' => Rule::when($request->is_trial_plan == 1, 'required|numeric'),
            'status' => 'required',
            'business_price_per_month' => Rule::when($request->is_trial_plan == 0, 'required'),
            'business_price_per_year' => Rule::when($request->is_trial_plan == 0, 'required'),
            'business_lifetime_price' => Rule::when($request->is_trial_plan == 0 && $request->has_lifetime_period == 1, 'required|numeric'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('plans_update');
    }
}
