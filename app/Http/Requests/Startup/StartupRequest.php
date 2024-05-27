<?php

namespace App\Http\Requests\Startup;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;

class StartupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        $generalSettings = config('generalSettings');
        $checkBusinessValidation = Session::get('startupType') == 'business_and_branch' || Session::get('startupType') == 'business';
        $checkBranchValidation = Session::get('startupType') == 'business_and_branch' || Session::get('startupType') == 'branch';

        return [
            'business_name' => Rule::when($checkBusinessValidation && $generalSettings['subscription__has_business'] == BooleanType::True->value, 'required'),
            'business_address' => Rule::when($checkBusinessValidation && $generalSettings['subscription__has_business'] == BooleanType::True->value, 'required'),
            'business_phone' => Rule::when($checkBusinessValidation && $generalSettings['subscription__has_business'] == BooleanType::True->value, 'required'),
            'business_email' => Rule::when($checkBusinessValidation && $generalSettings['subscription__has_business'] == BooleanType::True->value, 'required'),
            'business_currency_id' => Rule::when($checkBusinessValidation && $generalSettings['subscription__has_business'] == BooleanType::True->value, 'required'),
            'business_account_start_date' => Rule::when($checkBusinessValidation && $generalSettings['subscription__has_business'] == BooleanType::True->value, 'required'),
            'business_logo' => Rule::when($checkBusinessValidation && $generalSettings['subscription__has_business'] == BooleanType::True->value, 'sometimes|image|max:1024'),
            'branch_code' => Rule::when($checkBranchValidation, 'required'),
            'branch_name' => Rule::when($checkBranchValidation, 'required'),
            'branch_area_name' => Rule::when($checkBranchValidation, 'required'),
            'branch_phone' => Rule::when($checkBranchValidation, 'required'),
            'branch_country' => Rule::when($checkBranchValidation, 'required'),
            'branch_state' => Rule::when($checkBranchValidation, 'required'),
            'branch_city' => Rule::when($checkBranchValidation, 'required'),
            'branch_zip_code' => Rule::when($checkBranchValidation, 'required'),
            'branch_timezone' => Rule::when($checkBranchValidation, 'required'),
            'branch_account_start_date' => Rule::when($checkBranchValidation, 'required'),
            'branch_currency_id' => Rule::when($checkBranchValidation, 'required'),
            'branch_logo' => Rule::when($checkBranchValidation, 'sometimes|image|max:1024'),
            'branch_user_first_name' => Rule::when(isset($request->add_initial_user) && $request->add_initial_user == BooleanType::True->value, 'required'),
            'branch_user_phone' => Rule::when(isset($request->add_initial_user) && $request->add_initial_user == BooleanType::True->value, 'required'),
            'branch_user_username' => Rule::when(isset($request->add_initial_user) && $request->add_initial_user == BooleanType::True->value, 'required'),
            'role_id' => Rule::when(isset($request->add_initial_user) && $request->add_initial_user == BooleanType::True->value, 'required'),
            'password' => Rule::when(isset($request->add_initial_user) && $request->add_initial_user == BooleanType::True->value, 'required|confirmed'),
        ];
    }
}
