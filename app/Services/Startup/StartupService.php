<?php

namespace App\Services\Startup;

class StartupService
{
    public function prepareAddBranchRequest(object $request)
    {
        $requestData = $request->all();

        $keyMappings = [
            'branch_category' => 'category',
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
            // 'branch_logo' => 'logo',
            'branch_date_format' => 'date_format',
            'branch_time_format' => 'time_format',
            'branch_timezone' => 'timezone',
            'branch_stock_accounting_method' => 'stock_accounting_method',
            'branch_account_start_date' => 'account_start_date',
            'branch_financial_year_start_month' => 'financial_year_start_month',
            'branch_currency_id' => 'currency_id',
            'branch_currency_symbol' => 'currency_symbol',
            'branch_auto_repayment_sales_and_purchase_return' => 'auto_repayment_sales_and_purchase_return',
            'branch_auto_repayment_purchase_and_sales_return' => 'branch_auto_repayment_purchase_and_sales_return',
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
