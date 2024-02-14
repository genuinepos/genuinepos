<?php

namespace App\Services\Setups;

use Illuminate\Validation\Rule;

class StartupService
{
    public function startupValidation(object $request): ?array
    {
        $generalSettings = config('generalSettings');

        return $request->validate([
            'business_name' => Rule::when($generalSettings['addons__branch_limit'] > 1, 'required'),
            'business_address' => Rule::when($generalSettings['addons__branch_limit'] > 1, 'required'),
            'business_phone' => Rule::when($generalSettings['addons__branch_limit'] > 1, 'required'),
            'business_email' => Rule::when($generalSettings['addons__branch_limit'] > 1, 'required'),
            'business_currency_id' => Rule::when($generalSettings['addons__branch_limit'] > 1, 'required'),
            'business_account_start_date' => Rule::when($generalSettings['addons__branch_limit'] > 1, 'required'),
            'business_logo' => Rule::when($generalSettings['addons__branch_limit'] > 1, 'sometimes|image|max:1024'),
            'branch_code' => 'required',
            'branch_name' => 'required',
            'branch_area_name' => 'required',
            'branch_phone' => 'required',
            'branch_country' => 'required',
            'branch_state' => 'required',
            'branch_city' => 'required',
            'branch_zip_code' => 'required',
            'branch_timezone' => 'required',
            'branch_account_start_date' => 'required',
            'branch_currency_id' => 'required',
            'branch_logo' => 'sometimes|image|max:1024',
            'branch_user_first_name' => Rule::when($request->add_initial_user == 1, 'required'),
            'branch_user_last_name' => Rule::when($request->add_initial_user == 1, 'required'),
            'branch_user_phone' => Rule::when($request->add_initial_user == 1, 'required'),
            'branch_user_username' => Rule::when($request->add_initial_user == 1, 'required'),
            'role_id' => Rule::when($request->add_initial_user == 1, 'required'),
            'password' => Rule::when($request->add_initial_user == 1, 'required|confirmed'),
        ]);
    }

    public function prepareAddBranchRequest(object $request): object
    {
        $request->name = $request->branch_name;
        $request->area_name = $request->branch_area_name;
        $request->phone = $request->branch_phone;
        $request->alternate_phone_number = $request->branch_alternate_phone_number;
        $request->bin = $request->branch_bin;
        $request->tin = $request->branch_tin;
        $request->country = $request->branch_country;
        $request->state = $request->branch_state;
        $request->city = $request->branch_city;
        $request->address = $request->branch_address;
        $request->email = $request->branch_email;
        $request->date_format = $request->branch_date_format;
        $request->timezone = $request->branch_timezone;
        $request->stock_accounting_method = $request->branch_stock_accounting_method;
        $request->account_start_date = $request->branch_account_start_date;
        $request->financial_year_start_month = $request->branch_financial_year_start_month;
        $request->first_name = $request->branch_user_first_name;
        $request->last_name = $request->branch_user_last_name;
        $request->user_phone = $request->branch_user_phone;
        $request->username = $request->branch_user_username;

        return $request;
    }
}
