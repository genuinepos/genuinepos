<?php

namespace App\Http\Requests\Setups;

use App\Enums\BranchType;
use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BranchStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('branches_create') && $generalSettings['subscription']->current_shop_count > 1;
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
            'user_first_name' => Rule::when($request->add_initial_user == BooleanType::True->value, 'required'),
            'user_phone' => Rule::when($request->add_initial_user == BooleanType::True->value, 'required'),
            'user_email' => Rule::when($request->add_initial_user == BooleanType::True->value, 'required'),
            'user_username' => Rule::when($request->add_initial_user == BooleanType::True->value, 'required'),
            'password' => Rule::when($request->add_initial_user == BooleanType::True->value, 'required|confirmed'),
        ];
    }
}
