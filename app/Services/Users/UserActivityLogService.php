<?php

namespace App\Services\Users;

use App\Models\UserActivityLog;

class UserActivityLogService
{
    public function descriptionModel()
    {
        return [
            1 => [ // Customers
                'fields' => ['name', 'phone',  'contact_id'],
                'texts' => ['Name : ', 'Phone : ', 'Customer ID : '],
            ],
            2 => [ // Suppliers
                'fields' => ['name', 'phone', 'contact_id'],
                'texts' => ['Name : ', 'Phone : ', 'Supplier ID : '],
            ],
            3 => [ // Users
                'fields' => ['prefix', 'name', 'last_name', 'username'],
                'texts' => ['prefix : ', 'Name : ', 'Last Lame : ', 'Username'],
            ],
            4 => [ // Purchase
                'fields' => ['date', 'invoice_id', 'total_purchase_amount', 'paid', 'due'],
                'texts' => ['Date : ', 'P.Invoice ID : ', 'Total Purchase Amount : ', 'Paid : ', 'Due : '],
            ],
            5 => [ // Purchase Order
                'fields' => ['date', 'invoice_id', 'total_purchase_amount', 'paid', 'due'],
                'texts' => ['Order Date : ', 'Purchase Order ID : ', 'Total Ordered Amt : ', 'Paid : ', 'Due : '],
            ],
            6 => [ // Purchase Return
                'fields' => ['date', 'voucher_no', 'total_return_amount', 'received_amount'],
                'texts' => ['Date : ', 'Return Invoice ID : ', 'Total Returned Amt : ', 'Received Amt. : ', 'Due : '],
            ],
            29 => [ // Draft
                'fields' => ['date', 'draft_id', 'total_invoice_amount'],
                'texts' => ['Date : ', 'Draft ID : ', 'Total Amount : '],
            ],
            30 => [ // Quotation
                'fields' => ['date', 'quotation_id', 'total_invoice_amount'],
                'texts' => ['Date : ', 'Quotation ID : ', 'Total Amount : '],
            ],
            7 => [ // Sales
                'fields' => ['date', 'invoice_id', 'total_invoice_amount', 'paid', 'due'],
                'texts' => ['Date : ', 'Invoice ID : ', 'Total Invoice Amount : ', 'Paid : ', 'Due : '],
            ],
            8 => [ // Sales Order
                'fields' => ['date', 'order_id', 'total_invoice_amount', 'paid', 'due'],
                'texts' => ['Date : ', 'Order ID : ', 'Total Ordered Amt : ', 'Advance Received : ', 'Due : '],
            ],
            32 => [ // Hold Invoice
                'fields' => ['date', 'hold_invoice_id', 'total_invoice_amount'],
                'texts' => ['Date : ', 'Hold Invoice ID : ', 'Total Amt : '],
            ],
            33 => [ // Suspended
                'fields' => ['date', 'suspend_id', 'total_invoice_amount'],
                'texts' => ['Date : ', 'Suspend ID : ', 'Total Amt : '],
            ],
            9 => [ // Sales Return
                'fields' => ['date', 'voucher_no', 'total_return_amount', 'paid', 'due'],
                'texts' => ['Date : ', 'Return Voucher No : ', 'Total Returned Amt. : ', 'Paid : ', 'Due : '],
            ],
            34 => [ // Sale Exchange
                'fields' => ['date', 'invoice_id', 'total_invoice_amount'],
                'texts' => ['Date : ', 'Invoice ID : ', 'Total Amt : '],
            ],
            10 => [ // Transfer Stock
                'fields' => ['date', 'voucher_no', 'total_send_qty', 'total_received_qty'],
                'texts' => ['Date : ', 'Voucher No : ', 'Total Send Quantity : ', 'Total Received Quantity : '],
            ],
            13 => [ // Stock Adjustment
                'fields' => ['date', 'voucher_no', 'net_total_amount', 'recovered_amount'],
                'texts' => ['Date : ', 'Voucher No : ', 'Total Adjusted Amt. : ', 'Total Recovered Amount : '],
            ],
            15 => [ // Expenses
                'fields' => ['date', 'voucher_no', 'total_amount'],
                'texts' => ['Date : ', 'Expense Voucher No : ', 'Total Expense Amt. : '],
            ],
            16 => [ // Bank
                'fields' => ['name'],
                'texts' => ['Bank Name : '],
            ],
            17 => [ // Accounts
                'fields' => ['name', 'account_number', 'opening_balance', 'opening_balance_type'],
                'texts' => ['Account Name : ', 'Account Number : ', 'Opening Balance : ', 'Type : '],
            ],
            18 => [ // User login
                'fields' => ['username'],
                'texts' => ['Username : '],
            ],
            19 => [ // User Logout
                'fields' => ['username'],
                'texts' => ['Username : '],
            ],
            20 => [ // Categories
                'fields' => ['id', 'name'],
                'texts' => ['Category ID : ', 'Category Name : '],
            ],
            21 => [ // Sub-Categories
                'fields' => ['id', 'name'],
                'texts' => ['Sub-Category ID : ', 'Sub-Category Name : '],
            ],
            22 => [ // Brands
                'fields' => ['id', 'name'],
                'texts' => ['Brand ID: ', 'Brand Name : '],
            ],
            23 => [ // UNITS
                'fields' => ['name', 'code_name'],
                'texts' => ['Unit Name : ', 'Short Name : '],
            ],
            24 => [ // Variants
                'fields' => ['id', 'name'],
                'texts' => ['ID : ', 'Variant Name : '],
            ],
            25 => [ // Warranties
                'fields' => ['name', 'duration', 'duration_type'],
                'texts' => ['Warranty Name : ', 'Duration : ', 'Duration Type : '],
            ],
            26 => [ // Product
                'fields' => ['name', 'product_code', 'product_cost_with_tax', 'product_price'],
                'texts' => ['Name : ', 'P.Code(SKU) : ', 'Cost.inc Tax : ', 'Price.Exc Tax : '],
            ],
            27 => [ // Receipt Voucher
                'fields' => ['date', 'voucher_no', 'total_amount'],
                'texts' => ['Date : ', 'VoucherNo : ', 'Received Amount'],
            ],
            28 => [ // Payment
                'fields' => ['date', 'voucher_no',  'total_amount'],
                'texts' => ['Date : ', 'VoucherNo : ', 'Paid Amount : '],
            ],
            31 => [ // Contra
                'fields' => ['date', 'voucher_no', 'total_amount'],
                'texts' => ['Date : ', 'VoucherNo : ', 'Total Amount : '],
            ],
            35 => [ // Selling Price Group
                'fields' => ['name', 'description',],
                'texts' => ['Name : ', 'Description : '],
            ],
            36 => [ // Location Switch
                'fields' => ['location_switch_log_description'],
                'texts' => [''],
            ],
        ];
    }

    public function addLog($action, $subjectType, $dataObj, $branchId = null, $userId = null)
    {
        $generalSettings = config('generalSettings');
        $dateFormat = $generalSettings['business_or_shop__date_format'];
        $__dateFormat = str_replace('y', 'Y', $dateFormat);

        $descriptionModel = $this->descriptionModel();
        $addLog = new UserActivityLog();
        $addLog->branch_id = isset($branchId) ? $branchId : auth()->user()->branch_id;
        $addLog->user_id = isset($userId) ? $userId : auth()->user()->id;
        $addLog->action = $action;
        $addLog->subject_type = $subjectType;
        $addLog->date = date($__dateFormat);
        $addLog->report_date = date('Y-m-d H:i:s');

        $description = '';

        $index = 0;
        foreach ($descriptionModel[$subjectType]['fields'] as $field) {

            $description .= $descriptionModel[$subjectType]['texts'][$index] . (isset($dataObj->{$field}) ? $dataObj->{$field} : 'N/A') . ', ';
            $index++;
        }

        $addLog->descriptions = $description;
        $addLog->save();
    }
}
