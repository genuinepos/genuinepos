<?php

namespace Modules\SAAS\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PlanStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(Request $request): array
    {
        return [
            'name' => 'required|unique:plans,name',
            'price_per_month' => Rule::when($request->is_trial_plan == 0, 'required|numeric'),
            'price_per_year' => Rule::when($request->is_trial_plan == 0, 'required|numeric'),
            'lifetime_price' => Rule::when($request->is_trial_plan == 0 && $request->has_lifetime_period == 1, 'required|numeric'),
            'applicable_lifetime_years' => Rule::when($request->is_trial_plan == 0 && $request->has_lifetime_period == 1, 'required|numeric'),
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
        return auth()->user()->can('plans_store');
    }
}
