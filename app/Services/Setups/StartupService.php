<?php

namespace App\Services\Setups;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

class StartupService
{
    public function startupValidation(object $request): ?array
    {
        $generalSettings = config('generalSettings');
        $checkBusinessValidation = Session::get('startupType') == 'business_and_branch' || Session::get('startupType') == 'business';
        $checkBranchValidation = Session::get('startupType') == 'business_and_branch' || Session::get('startupType') == 'branch';

        return $request->validate([
            'business_name' => Rule::when($checkBusinessValidation && $generalSettings['subscription']->has_business == 1, 'required'),
            'business_address' => Rule::when($checkBusinessValidation && $generalSettings['subscription']->has_business == 1, 'required'),
            'business_phone' => Rule::when($checkBusinessValidation && $generalSettings['subscription']->has_business == 1, 'required'),
            'business_email' => Rule::when($checkBusinessValidation && $generalSettings['subscription']->has_business == 1, 'required'),
            'business_currency_id' => Rule::when($checkBusinessValidation && $generalSettings['subscription']->has_business == 1, 'required'),
            'business_account_start_date' => Rule::when($checkBusinessValidation && $generalSettings['subscription']->has_business == 1, 'required'),
            'business_logo' => Rule::when($checkBusinessValidation && $generalSettings['subscription']->has_business == 1, 'sometimes|image|max:1024'),
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
            'branch_user_first_name' => Rule::when(isset($request->add_initial_user) && $request->add_initial_user == 1, 'required'),
            'branch_user_phone' => Rule::when(isset($request->add_initial_user) && $request->add_initial_user == 1, 'required'),
            'branch_user_username' => Rule::when(isset($request->add_initial_user) && $request->add_initial_user == 1, 'required'),
            'role_id' => Rule::when(isset($request->add_initial_user) && $request->add_initial_user == 1, 'required'),
            'password' => Rule::when(isset($request->add_initial_user) && $request->add_initial_user == 1, 'required|confirmed'),
        ]);
    }

    public function prepareAddBranchRequest(object $request)
    {
        $requestData = $request->all();

        $keyMappings = [
            'branch_name' => 'name',
            'branch_area_name' => 'area_name',
            'branch_phone' => 'phone',
            'branch_alternative_phone' => 'alternative_phone',
            'branch_bin' => 'bin',
            'branch_tin' => 'tin',
            'branch_country' => 'country',
            'branch_state' => 'state',
            'branch_city' => 'city',
            'branch_zip_code' => 'zip_code',
            'branch_address' => 'address',
            'branch_email' => 'email',
            'branch_website' => 'website',
            'branch_logo' => 'logo',
            'branch_date_format' => 'date_format',
            'branch_time_format' => 'time_format',
            'branch_timezone' => 'timezone',
            'branch_stock_accounting_method' => 'stock_accounting_method',
            'branch_account_start_date' => 'account_start_date',
            'branch_financial_year_start_month' => 'financial_year_start_month',
            'branch_currency_id' => 'currency_id',
            'branch_currency_symbol' => 'currency_symbol',
            'branch_user_first_name' => 'user_first_name',
            'branch_user_last_name' => 'user_last_name',
            'branch_user_phone' => 'user_phone',
            'branch_user_email' => 'user_email',
            'branch_user_username' => 'user_username',
        ];

        foreach ($keyMappings as $oldKey => $newKey) {
            
            if (isset($requestData[$oldKey])) {

                $requestData[$newKey] = $requestData[$oldKey];
                unset($requestData[$oldKey]);
            }
        }

        $request->replace($requestData);
        return $request;
    }
}
