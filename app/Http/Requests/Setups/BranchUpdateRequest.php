<?php

namespace App\Http\Requests\Setups;

use App\Enums\BranchType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BranchUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('branches_edit') || auth()->user()->can('business_or_shop_settings');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'name' => Rule::when(BranchType::DifferentShop->value == $request->branch_type, 'required'),
            'area_name' => 'required',
            'branch_code' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zip_code' => 'required',
            'timezone' => 'required',
            'currency_id' => 'required',
            'account_start_date' => Rule::when(BranchType::DifferentShop->value == $request->branch_type, 'required|date'),
            'logo' => 'sometimes|image|max:1024',
        ];
    }
}
