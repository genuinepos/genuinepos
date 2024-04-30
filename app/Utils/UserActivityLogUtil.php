<?php

namespace App\Utils;

use App\Models\UserActivityLog;

class UserActivityLogUtil
{
    public function subjectTypes()
    {
        return [
            26 => 'Product',
            1 => 'Customers',
            2 => 'Suppliers',
            3 => 'Users',
            18 => 'User Login',
            19 => 'User Logout',
            27 => 'Receipt',
            28 => 'Payment',
            31 => 'Contra',
            4 => 'Purchase',
            5 => 'Purchase Order',
            6 => 'Purchase Return',
            7 => 'Sales',
            29 => 'Draft',
            30 => 'Quotation',
            32 => 'Hold Invoice',
            33 => 'Suspend Invoice',
            8 => 'Sales Order',
            9 => 'Sale Return',
            20 => 'POS Sale',
            34 => 'Exchange Invoice',
            10 => 'Transfer Stock',
            13 => 'Stock Adjustment',
            15 => 'Expense',
            16 => 'Bank',
            17 => 'Accounts',
            20 => 'Categories',
            21 => 'Sub-Categories',
            22 => 'Brands',
            23 => 'Units',
            24 => 'Variants',
            25 => 'Warranties',
            35 => 'Selling Price Groups',
            36 => 'Location Switch',
            37 => 'Stock Issue',
        ];
    }

    public function actions()
    {
        return [
            1 => 'Added',
            2 => 'Updated',
            3 => 'Deleted',
            4 => 'User Login',
            5 => 'User Logout',
        ];
    }

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
                'fields' => ['name', 'account_number'],
                'texts' => ['Account Name : ', 'Account Number : '],
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
                'fields' => ['date', 'voucher_no',   'total_amount'],
                'texts' => ['Date : ', 'VoucherNo : ', 'Received Amount'],
            ],
            28 => [ // Payment
                'fields' => ['date', 'voucher_no',   'total_amount'],
                'texts' => ['Date : ', 'VoucherNo : ', 'Paid Amount : '],
            ],
            31 => [ // Contra
                'fields' => ['date', 'voucher_no',   'total_amount'],
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
            37 => [ // Stock Issue
                'fields' => ['date', 'voucher_no', 'total_item', 'total_qty', 'net_total_amount'],
                'texts' => ['Date: ', 'Voucher No: ', 'Total Item: ', 'Total Qty: ', 'Net Total Amount: '],
            ],
        ];
    }

    public function addLog($action, $subject_type, $data_obj, $branch_id = null, $user_id = null)
    {
        $generalSettings = config('generalSettings');
        $dateFormat = $generalSettings['business_or_shop__date_format'];
        $__dateFormat = str_replace('y', 'Y', $dateFormat);

        $descriptionModel = $this->descriptionModel();
        $addLog = new UserActivityLog();
        $addLog->branch_id = $branch_id ? $branch_id : auth()->user()->branch_id;
        $addLog->user_id = $user_id ? $user_id : auth()->user()->id;
        $addLog->action = $action;
        $addLog->subject_type = $subject_type;
        $addLog->date = date($__dateFormat);
        $addLog->report_date = date('Y-m-d H:i:s');

        // prepare the descriptions
        $description = '';

        $index = 0;
        foreach ($descriptionModel[$subject_type]['fields'] as $field) {

            $description .= $descriptionModel[$subject_type]['texts'][$index] . (isset($data_obj->{$field}) ? $data_obj->{$field} : 'N/A') . ', ';
            $index++;
        }

        $addLog->descriptions = $description;
        $addLog->save();
    }
}
