<?php

namespace App\Services\Accounts;

use App\Models\Accounts\DayBook;

class DayBookService
{
    public static function voucherTypes()
    {
        return [
            1 => 'Sales',
            2 => 'Sales Order',
            3 => 'Sales Return',
            4 => 'Purchase',
            5 => 'Purchase Order',
            6 => 'Purchase Return',
            7 => 'Stock Adjustment',
            8 => 'Receipt',
            9 => 'Payment',
            10 => 'Contra',
            11 => 'Expenses',
            12 => 'Incomes',
            13 => 'Production',
            14 => 'Transfer Stock',
            15 => 'Receive Stock',
            16 => 'Payroll',
            17 => 'Payroll Payment',
            18 => 'Stock Issue',
        ];
    }

    public function voucherType($voucherTypeId)
    {
        $data = [
            1 => ['name' => 'Sales', 'id' => 'sale_id', 'voucher_no' => 'sales_voucher', 'details_id' => 'sale_id', 'link' => 'sales.show'],
            2 => ['name' => 'Sales Order', 'id' => 'sale_id', 'voucher_no' => 'sales_order_voucher', 'details_id' => 'sale_id', 'link' => 'sales.orders.show'],
            3 => ['name' => 'Sales Return', 'id' => 'sale_return_id', 'voucher_no' => 'sale_return_voucher', 'details_id' => 'sale_return_id', 'link' => 'sales.returns.show'],
            4 => ['name' => 'Purchase', 'id' => 'purchase_id', 'voucher_no' => 'purchase_voucher', 'details_id' => 'purchase_id', 'link' => 'purchases.show'],
            5 => ['name' => 'Purchase Order', 'id' => 'purchase_id', 'voucher_no' => 'purchase_voucher', 'details_id' => 'purchase_id', 'link' => 'purchase.orders.show'],
            6 => ['name' => 'Purchase Return', 'id' => 'purchase_return_id', 'voucher_no' => 'purchase_return_voucher', 'details_id' => 'purchase_return_id', 'link' => 'purchase.returns.show'],
            7 => ['name' => 'Stock Adjustment', 'id' => 'stock_adjustment_id', 'voucher_no' => 'stock_adjustment_voucher', 'details_id' => 'stock_adjustment_id', 'link' => 'stock.adjustments.show'],
            8 => ['name' => 'Receipt', 'id' => 'voucher_description_id', 'voucher_no' => 'accounting_voucher_no', 'details_id' => 'accounting_voucher_id', 'link' => 'receipts.show'],
            9 => ['name' => 'Payment', 'id' => 'voucher_description_id', 'voucher_no' => 'accounting_voucher_no', 'details_id' => 'accounting_voucher_id', 'link' => 'payments.show'],
            10 => ['name' => 'Contra', 'id' => 'voucher_description_id', 'voucher_no' => 'accounting_voucher_no', 'details_id' => 'accounting_voucher_id', 'link' => 'contras.show'],
            11 => ['name' => 'Expenses', 'id' => 'voucher_description_id', 'voucher_no' => 'accounting_voucher_no', 'details_id' => 'accounting_voucher_id', 'link' => 'expenses.show'],
            12 => ['name' => 'Incomes', 'id' => 'voucher_description_id', 'voucher_no' => 'accounting_voucher_no', 'details_id' => 'accounting_voucher_id', 'link' => ''],
            13 => ['name' => 'Production', 'id' => 'production_id', 'voucher_no' => 'production_voucher_no', 'details_id' => 'production_voucher_id', 'link' => 'manufacturing.productions.show'],
            14 => ['name' => 'TransferStock', 'id' => 'transfer_stock_id', 'voucher_no' => 'transfer_stock_voucher_no', 'details_id' => 'transfer_stock_voucher_id', 'link' => 'transfer.stocks.show'],
            15 => ['name' => 'ReceivedStock', 'id' => 'transfer_stock_id', 'voucher_no' => 'transfer_stock_voucher_no', 'details_id' => 'transfer_stock_voucher_id', 'link' => 'transfer.stocks.show'],
            16 => ['name' => 'Payroll', 'id' => 'payroll_id', 'voucher_no' => 'payroll_voucher', 'details_id' => 'payroll_id', 'link' => 'hrm.payrolls.show'],
            17 => ['name' => 'PayrollPayment', 'id' => 'voucher_description_id', 'voucher_no' => 'accounting_voucher_no', 'details_id' => 'accounting_voucher_id', 'link' => 'hrm.payroll.payments.show'],
            18 => ['name' => 'StockIssue', 'id' => 'stock_issue_id', 'voucher_no' => 'stock_issue_voucher_no', 'details_id' => 'stock_issue_voucher_id', 'link' => 'stock.issues.show'],
        ];

        return $data[$voucherTypeId];
    }

    public function addDayBook(
        $voucherTypeId,
        $date,
        $accountId,
        $transId,
        $amount,
        $amountType,
        $productId = null,
        $variantId = null,
        $branchId = null,
    ) {
        $voucherType = $this->voucherType($voucherTypeId);
        $add = new DayBook();
        $add->branch_id = $branchId ? $branchId : auth()->user()->branch_id;
        $add->date_ts = date('Y-m-d H:i:s', strtotime($date . date(' H:i:s')));
        $add->account_id = isset($accountId) ? $accountId : null;
        $add->product_id = isset($productId) ? $productId : null;
        $add->variant_id = isset($variantId) ? $variantId : null;
        $add->voucher_type = $voucherTypeId;
        $add->{$voucherType['id']} = $transId;
        $add->amount = $amount;
        $add->amount_type = $amountType;
        $add->save();
    }

    public function updateDayBook(
        $voucherTypeId,
        $date,
        $accountId,
        $transId,
        $amount,
        $amountType,
        $productId = null,
        $variantId = null,
        $branchId = null,
    ) {
        $voucherType = $this->voucherType($voucherTypeId);
        $update = '';
        $query = DayBook::where($voucherType['id'], $transId)->where('voucher_type', $voucherTypeId);
        $update = $query->first();

        if ($update) {

            $previousTime = date(' H:i:s', strtotime($update->date_ts));
            $update->date_ts = date('Y-m-d H:i:s', strtotime($date . $previousTime));
            $update->account_id = $accountId ? $accountId : null;
            $update->product_id = isset($productId) ? $productId : null;
            $update->variant_id = isset($variantId) ? $variantId : null;
            $update->amount = $amount;
            $update->amount_type = $amountType;
            $update->save();
        } else {

            $this->addDayBook(
                voucherTypeId: $voucherTypeId,
                date: $date,
                accountId: $accountId,
                transId: $transId,
                amount: $amount,
                amountType: $amountType,
                productId: $productId,
                branchId: $branchId,
            );
        }
    }
}
