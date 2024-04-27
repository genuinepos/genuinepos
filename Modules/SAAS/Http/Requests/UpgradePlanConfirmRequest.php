<?php

namespace Modules\SAAS\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpgradePlanConfirmRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(Request $request): array
    {
        return [
            'plan_id' => 'required',
            'net_total' => 'required|numeric|gt:0',
            'discount_percent' => 'required|numeric',
            'discount' => 'required|numeric',
            'total_payable' => 'required|numeric|gt:0',
            'payment_status' => 'required',
            'repayment_date' => Rule::when($request->payment_status == 0, 'required'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('tenants_upgrade_plan');
    }
}
