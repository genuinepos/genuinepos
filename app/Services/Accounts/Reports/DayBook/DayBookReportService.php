<?php

namespace App\Services\Accounts\Reports\DayBook;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Models\Accounts\DayBook;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DayBookReportService
{
    public function daybookTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $daybook = $this->daybookEntriesQuery(request: $request);

        return DataTables::of($daybook)
            ->editColumn('date', function ($row) use ($generalSettings) {

                $dateFormat = $generalSettings['business_or_shop__date_format'];
                $__dateFormat = str_replace('-', '/', $dateFormat);

                return date($__dateFormat . ' h:i:s', strtotime($row->date_ts));
            })

            ->editColumn('particulars', function ($row) use ($request) {

                $voucherType = $row->voucher_type;
                $daybookParticularService = new \App\Services\Accounts\Reports\DayBook\DayBookParticularService();

                return $daybookParticularService->particulars(request: $request, voucherType: $row->voucher_type, daybook: $row);
            })

            ->editColumn('voucher_type', function ($row) {

                $type = DayBookVoucherType::tryFrom($row->voucher_type)->name;

                return '<strong>' . str($type)->headline() . '</strong>';
            })

            ->editColumn('voucher_no', function ($row) {

                $dayBookService = new \App\Services\Accounts\DayBookService();
                $type = $dayBookService->voucherType($row->voucher_type);

                return '<a href="' . (!empty($type['link']) ? route($type['link'], $row->{$type['details_id']}) : '#') . '" id="details_btn" class="fw-bold">' . $row->{$type['voucher_no']} . '</a>';
            })
            ->editColumn('debit', fn ($row) => ($row->amount_type == 'debit' ? \App\Utils\Converter::format_in_bdt($row->amount) : ''))
            ->editColumn('credit', fn ($row) => ($row->amount_type == 'credit' ? \App\Utils\Converter::format_in_bdt($row->amount) : ''))
            ->rawColumns(['date', 'particulars', 'voucher_type', 'voucher_no', 'debit', 'credit'])
            ->make(true);
    }

    public function daybookEntriesQuery(object $request): ?object
    {
        $query = DayBook::query();

        $this->filter(request: $request, query: $query);

        $query->leftJoin('sales', 'day_books.sale_id', 'sales.id')
            ->leftJoin('sale_returns', 'day_books.sale_return_id', 'sale_returns.id')
            ->leftJoin('purchases', 'day_books.purchase_id', 'purchases.id')
            ->leftJoin('purchase_returns', 'day_books.purchase_return_id', 'purchase_returns.id')
            ->leftJoin('stock_adjustments', 'day_books.stock_adjustment_id', 'stock_adjustments.id')
            ->leftJoin('productions', 'day_books.production_id', 'productions.id')
            ->leftJoin('transfer_stocks', 'day_books.transfer_stock_id', 'transfer_stocks.id')
            ->leftJoin('stock_issues', 'day_books.stock_issue_id', 'stock_issues.id')
            ->leftJoin('hrm_payrolls', 'day_books.payroll_id', 'hrm_payrolls.id')
            ->leftJoin('accounting_voucher_descriptions', 'day_books.voucher_description_id', 'accounting_voucher_descriptions.id')
            ->leftJoin('accounting_vouchers', 'accounting_voucher_descriptions.accounting_voucher_id', 'accounting_vouchers.id')
            ->with(
                [
                    'account:id,name,account_number,account_group_id',
                    'account.group:id,name,sub_group_number,sub_sub_group_number',
                    'branch:id,name,area_name,branch_code,parent_branch_id',
                    'branch.parentBranch:id,name',
                    'voucherDescription',
                    'voucherDescription.accountingVoucher:id,remarks',
                    'voucherDescription.accountingVoucher.voucherDescriptions',
                    'voucherDescription.accountingVoucher.voucherDescriptions.account:id,name,account_number,account_group_id',
                    'voucherDescription.accountingVoucher.voucherDescriptions.account.group:id,name,sub_group_number,sub_sub_group_number',
                    'voucherDescription.accountingVoucher.voucherDescriptions.paymentMethod:id,name',
                    'voucherDescription.accountingVoucher.voucherDescriptions.references:id,voucher_description_id,sale_id,sale_return_id,purchase_id,purchase_return_id,stock_adjustment_id,payroll_id,amount',
                    'voucherDescription.accountingVoucher.voucherDescriptions.references.sale:id,invoice_id,order_id,order_status',
                    'voucherDescription.accountingVoucher.voucherDescriptions.references.salesReturn:id,voucher_no',
                    'voucherDescription.accountingVoucher.voucherDescriptions.references.purchase:id,invoice_id,purchase_status',
                    'voucherDescription.accountingVoucher.voucherDescriptions.references.purchaseReturn:id,voucher_no',
                    'voucherDescription.accountingVoucher.voucherDescriptions.references.payroll:id,voucher_no',

                    'sale:id,customer_account_id,total_invoice_amount,note,sale_account_id,total_sold_qty,order_discount_amount,order_tax_amount',
                    'sale.salesAccount:id,name',
                    'sale.customer:id,name,phone,address',
                    'sale.saleProducts:id,sale_id,product_id,variant_id,quantity,unit_id,unit_price_inc_tax,subtotal',
                    'sale.saleProducts.product:id,name',
                    'sale.saleProducts.variant:id,variant_name',
                    'sale.saleProducts.unit:id,code_name,base_unit_multiplier',

                    'salesReturn:id,customer_account_id,total_qty,sale_id,sale_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount,note',
                    'salesReturn.salesAccount:id,name',
                    'salesReturn.customer:id,name,phone,address',
                    'salesReturn.saleReturnProducts:id,sale_return_id,product_id,variant_id,unit_id,unit_price_inc_tax,return_qty,return_subtotal',
                    'salesReturn.saleReturnProducts.product:id,name',
                    'salesReturn.saleReturnProducts.variant:id,variant_name',
                    'salesReturn.saleReturnProducts.unit:id,code_name,base_unit_multiplier',

                    'purchase:id,supplier_account_id,total_qty,net_total_amount,order_discount_amount,purchase_tax_amount,total_purchase_amount,purchase_note,purchase_account_id',
                    'purchase.purchaseAccount:id,name',
                    'purchase.purchaseProducts:id,purchase_id,product_id,variant_id,unit_id,quantity,net_unit_cost,line_total',
                    'purchase.purchaseProducts.product:id,name',
                    'purchase.purchaseProducts.variant:id,variant_name',
                    'purchase.purchaseProducts.unit:id,code_name,base_unit_multiplier',

                    'purchaseReturn:id,supplier_account_id,total_qty,purchase_id,purchase_account_id,return_discount_amount,return_tax_amount,net_total_amount,total_return_amount',
                    'purchaseReturn.purchaseAccount:id,name',
                    'purchaseReturn.supplier:id,name,phone,address',
                    'purchaseReturn.purchaseReturnProducts:id,purchase_return_id,product_id,variant_id,unit_cost_inc_tax,return_qty,unit_id,return_subtotal',
                    'purchaseReturn.purchaseReturnProducts.product:id,name',
                    'purchaseReturn.purchaseReturnProducts.variant:id,variant_name',
                    'purchaseReturn.purchaseReturnProducts.unit:id,code_name,base_unit_multiplier',

                    'stockAdjustment:id,expense_account_id,total_qty,net_total_amount,recovered_amount,type,reason',
                    'stockAdjustment:account:id,name',
                    'stockAdjustment:adjustmentProducts:id,adjustmentProducts,product_id,variant_id,quantity,unit_cost_inc_tax,subtotal',
                    'stockAdjustment:adjustmentProducts.product:id,name',
                    'stockAdjustment:adjustmentProducts.variant:id,variant_name',
                    'stockAdjustment:adjustmentProducts.unit:id,code_name,base_unit_multiplier',

                    'stockIssue',
                    'stockIssue.stockIssuedProducts',
                    'stockIssue.stockIssuedProducts.product:id,name',
                    'stockIssue.stockIssuedProducts.variant:id,variant_name',
                    'stockIssue.stockIssuedProducts.unit:id,code_name,base_unit_multiplier',

                    'production',
                    'production.unit:id,code_name,base_unit_multiplier',
                    'production.product:id,name,product_code',
                    'production.variant:id,variant_name,variant_code',
                    'production.ingredients',
                    'production.ingredients.product:id,name,product_code',
                    'production.ingredients.variant:id,variant_name,variant_code',
                    'production.ingredients.unit:id,code_name,base_unit_multiplier',

                    'transferStock',
                    'transferStock.senderBranch:id,name,area_name,branch_code,parent_branch_id',
                    'transferStock.senderBranch.parentBranch:id,name,area_name,branch_code',
                    'transferStock.senderWarehouse:id,warehouse_name,warehouse_code',
                    'transferStock.receiverBranch:id,name,area_name,branch_code,parent_branch_id',
                    'transferStock.receiverBranch.parentBranch:id,name,area_name,branch_code',
                    'transferStock.receiverWarehouse:id,warehouse_name,warehouse_code',
                    'transferStock.transferStockProducts',
                    'transferStock.transferStockProducts.product:id,name',
                    'transferStock.transferStockProducts.variant:id,variant_name',
                    'transferStock.transferStockProducts.unit:id,code_name,base_unit_multiplier',

                    'payroll',
                    'payroll.user:id,prefix,name,last_name',
                    'payroll.allowances',
                    'payroll.allowances.allowance',
                    'payroll.deductions',
                    'payroll.deductions.deduction',

                    'product:id,name',
                    'variant:id,variant_name',
                ]
            )->select(
                'day_books.branch_id',
                'day_books.date_ts',
                'day_books.voucher_type',
                'day_books.account_id',
                'day_books.sale_id',
                'day_books.sale_return_id',
                'day_books.purchase_id',
                'day_books.purchase_return_id',
                'day_books.stock_adjustment_id',
                'day_books.voucher_description_id',
                'day_books.stock_issue_id',
                'day_books.production_id',
                'day_books.payroll_id',
                'day_books.transfer_stock_id',
                'day_books.product_id',
                'day_books.variant_id',
                'day_books.amount',
                'day_books.amount_type',
                // 'sales.id as sale_id',
                'sales.invoice_id as sales_voucher',
                'sales.order_id as sales_order_voucher',
                // 'sale_returns.id as sale_return_id',
                'sale_returns.voucher_no as sale_return_voucher',
                // 'purchases.id as purchase_id',
                'purchases.invoice_id as purchase_voucher',
                // 'purchase_returns.id as purchase_return_id',
                'purchase_returns.voucher_no as purchase_return_voucher',
                // 'stock_adjustments.id as stock_adjustment_id',
                'stock_adjustments.voucher_no as stock_adjustment_voucher',
                'accounting_vouchers.id as accounting_voucher_id',
                'accounting_vouchers.voucher_no as accounting_voucher_no',
                'stock_issues.id as stock_issue_voucher_id',
                'stock_issues.voucher_no as stock_issue_voucher_no',
                'productions.id as production_voucher_id',
                'productions.voucher_no as production_voucher_no',
                // 'hrm_payrolls.id as payroll_id',
                'hrm_payrolls.voucher_no as payroll_voucher',
                'transfer_stocks.id as transfer_stock_voucher_id',
                'transfer_stocks.voucher_no as transfer_stock_voucher_no',
            );

        return $query->orderBy('day_books.date_ts', 'asc')->orderBy('day_books.id', 'asc');
        // return $query->orderBy('day_books.date_ts', 'asc');
    }

    private function filter(object $request, object $query): object
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('day_books.branch_id', null);
            } else {

                $query->where('day_books.branch_id', $request->branch_id);
            }
        }

        if ($request->voucher_type) {

            $query->where('day_books.voucher_type', $request->voucher_type);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('day_books.date_ts', $date_range);
        }

        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('day_books.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
