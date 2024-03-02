<?php

namespace App\Services\Accounts\Reports\VatTax;

use Carbon\Carbon;
use App\Enums\RoleType;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Models\Accounts\AccountLedger;
use App\Enums\AccountLedgerVoucherType;
use Yajra\DataTables\Facades\DataTables;

class VatTaxReportService
{
    private function vatTaxInputQuery(object $request): ?object
    {
        $query = AccountLedger::query()
            ->where('account_groups.sub_sub_group_number', 8)
            ->where('account_ledgers.amount_type', 'debit')
            ->whereIn(
                'account_ledgers.voucher_type',
                [
                    AccountLedgerVoucherType::Purchase->value,
                    AccountLedgerVoucherType::PurchaseProductTax->value,
                    AccountLedgerVoucherType::SalesReturn->value,
                    AccountLedgerVoucherType::SalesReturnProductTax->value,
                ]
            );

        $this->filter(request: $request, query: $query);

        $query->leftJoin('sale_returns', 'account_ledgers.sale_return_id', 'sale_returns.id')
            ->leftJoin('sale_return_products', 'account_ledgers.sale_return_product_id', 'sale_return_products.id')
            ->leftJoin('sale_returns as productSaleReturn', 'sale_return_products.sale_return_id', 'productSaleReturn.id')
            ->leftJoin('purchases', 'account_ledgers.purchase_id', 'purchases.id')
            ->leftJoin('purchase_products', 'account_ledgers.purchase_product_id', 'purchase_products.id')
            ->leftJoin('purchases as productPurchase', 'purchase_products.purchase_id', 'productPurchase.id')
            ->leftJoin('accounts', 'account_ledgers.account_id', 'accounts.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('branches', 'account_ledgers.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->with(
                [
                    'salesReturn:id,customer_account_id,total_return_amount,due',
                    'salesReturn.customer:id,name,phone,address',
                    'salesReturnProduct:id,sale_return_id,product_id,variant_id,return_subtotal',
                    'salesReturnProduct.salesReturn:id,voucher_no,customer_account_id,total_return_amount',
                    'salesReturnProduct.salesReturn.customer:id,name,phone,address',
                    'salesReturnProduct.product:id,name',
                    'salesReturnProduct.variant:id,variant_name',
                    'salesReturnProduct.unit:id,code_name,base_unit_multiplier',

                    'purchase:id,supplier_account_id,total_purchase_amount,due',
                    'purchase.supplier:id,name',
                    'purchaseProduct:id,purchase_id,product_id,variant_id,line_total',
                    'purchaseProduct.purchase:id,supplier_account_id',
                    'purchaseProduct.purchase.supplier:id,name',
                    'purchaseProduct.product:id,name',
                    'purchaseProduct.variant:id,variant_name',
                    'purchaseProduct.unit:id,code_name,base_unit_multiplier',
                ]
            )->select(
                'account_ledgers.branch_id',
                'account_ledgers.date',
                'account_ledgers.voucher_type',
                'account_ledgers.account_id',
                'account_ledgers.sale_return_id',
                'account_ledgers.sale_return_product_id',
                'account_ledgers.purchase_id',
                'account_ledgers.purchase_product_id',
                'account_ledgers.debit',
                'account_ledgers.amount_type',

                'sale_returns.voucher_no as sale_return_voucher',
                'productSaleReturn.id as product_sale_return_id',
                'productSaleReturn.voucher_no as product_sale_return_voucher',
                'purchases.invoice_id as purchase_voucher',
                'productPurchase.id as product_purchase_id',
                'productPurchase.invoice_id as product_purchase_voucher',

                'accounts.name as account_name',
                'branches.name as branch_name',
                'branches.area_name as branch_area_name',
                'parentBranch.name as parent_branch_name',
            );

        return $query->orderBy('account_ledgers.date', 'desc')->orderBy('account_ledgers.id', 'desc');
    }

    public function vatTaxInputTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $query = $this->vatTaxInputQuery($request);

        return DataTables::of($query)
            ->editColumn('date', function ($row) use ($generalSettings) {

                $dateFormat = $generalSettings['business_or_shop__date_format'];
                $__date_format = str_replace('-', '/', $dateFormat);

                return $row->date ? date($__date_format, strtotime($row->date)) : '';
            })

            ->editColumn('particulars', function ($row) {

                $voucherType = $row->voucher_type;
                $inputVatTaxParticularAndOnAmount = new \App\Services\Accounts\Reports\VatTax\InputVatTaxParticularAndOnAmountService();

                return $inputVatTaxParticularAndOnAmount->particulars(voucherType: $voucherType, data: $row);
            })

            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch) {

                    $areaName = $row?->branch_area_name ? '(' . $row?->branch_area_name . ')' : '';

                    if ($row?->parent_branch_name) {

                        return $row?->parent_branch_name . $areaName;
                    } else {

                        return $row?->branch_name . $areaName;
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'] . '(' . __('Business') . ')';
                }
            })

            ->editColumn('voucher_type', function ($row) {

                //return $row->voucher_type;
                $accountLedgerService = new \App\Services\Accounts\AccountLedgerService();
                $type = $accountLedgerService->voucherType($row->voucher_type);

                return '<strong>' . $type['name'] . '</strong>';
            })

            ->editColumn('voucher_no', function ($row) {

                //return $row->voucher_type;
                $accountLedgerService = new \App\Services\Accounts\AccountLedgerService();
                $type = $accountLedgerService->voucherType($row->voucher_type);

                return '<a href="' . (!empty($type['link']) ? route($type['link'], $row->{$type['details_id']}) : '#') . '" id="details_btn" class="fw-bold">' . $row->{$type['voucher_no']} . '</a>';
            })
            ->editColumn('input_amount', fn ($row) => '<span class="table_input_amount fw-bold" data-value="' . $row->debit . '">' .  \App\Utils\Converter::format_in_bdt($row->debit) . '</span>')

            ->editColumn('on_amount', function ($row) {

                $inputVatTaxParticularAndOnAmount = new \App\Services\Accounts\Reports\VatTax\InputVatTaxParticularAndOnAmountService();
                return $inputVatTaxParticularAndOnAmount->onAmounts(voucherType: $row->voucher_type, data: $row);
            })
            ->rawColumns(['date', 'particulars', 'branch', 'voucher_type', 'voucher_no', 'input_amount', 'on_amount'])
            ->make(true);
    }

    private function vatTaxOutputQuery(object $request): ?object
    {
        $query = AccountLedger::query()
            ->where('account_groups.sub_sub_group_number', 8)
            ->where('account_ledgers.amount_type', 'credit')
            ->whereIn(
                'account_ledgers.voucher_type',
                [
                    AccountLedgerVoucherType::Sales->value,
                    AccountLedgerVoucherType::SaleProductTax->value,
                    AccountLedgerVoucherType::Exchange->value,
                    AccountLedgerVoucherType::PurchaseReturn->value,
                    AccountLedgerVoucherType::SalesReturnProductTax->value,
                ]
            );

        $this->filter(request: $request, query: $query);

        $query->leftJoin('purchase_returns', 'account_ledgers.purchase_return_id', 'purchase_returns.id')
            ->leftJoin('purchase_return_products', 'account_ledgers.purchase_return_product_id', 'purchase_return_products.id')
            ->leftJoin('purchase_returns as productPurchaseReturn', 'purchase_return_products.purchase_return_id', 'productPurchaseReturn.id')
            ->leftJoin('sales', 'account_ledgers.sale_id', 'sales.id')
            ->leftJoin('sale_products', 'account_ledgers.sale_product_id', 'sale_products.id')
            ->leftJoin('sales as productSale', 'sale_products.sale_id', 'productSale.id')
            ->leftJoin('accounts', 'account_ledgers.account_id', 'accounts.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('branches', 'account_ledgers.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->with(
                [
                    'purchaseReturn:id,supplier_account_id,total_return_amount,due',
                    'purchaseReturn.supplier:id,name,phone,address',
                    'purchaseReturnProduct:id,purchase_return_id,product_id,variant_id,return_subtotal',
                    'purchaseReturnProduct.purchaseReturn:id,voucher_no,supplier_account_id,total_return_amount',
                    'purchaseReturnProduct.purchaseReturn.supplier:id,name,phone,address',
                    'purchaseReturnProduct.product:id,name',
                    'purchaseReturnProduct.variant:id,variant_name',
                    'purchaseReturnProduct.unit:id,code_name,base_unit_multiplier',

                    'sale:id,customer_account_id,total_invoice_amount,due',
                    'sale.customer:id,name',
                    'saleProduct:id,sale_id,product_id,variant_id,subtotal',
                    'saleProduct.sale:id,customer_account_id',
                    'saleProduct.sale.customer:id,name',
                    'saleProduct.product:id,name',
                    'saleProduct.variant:id,variant_name',
                    'saleProduct.unit:id,code_name,base_unit_multiplier',
                ]
            )->select(
                'account_ledgers.branch_id',
                'account_ledgers.date',
                'account_ledgers.voucher_type',
                'account_ledgers.account_id',
                'account_ledgers.purchase_return_id',
                'account_ledgers.purchase_return_product_id',
                'account_ledgers.sale_id',
                'account_ledgers.sale_product_id',
                'account_ledgers.credit',
                'account_ledgers.amount_type',

                'purchase_returns.id as purchase_return_id',
                'purchase_returns.voucher_no as purchase_return_voucher',
                'productPurchaseReturn.id as product_purchase_return_id',
                'productPurchaseReturn.voucher_no as product_sale_return_voucher',
                'sales.id as sale_id',
                'sales.invoice_id as sales_voucher',
                'productSale.id as product_sale_id',
                'productSale.invoice_id as product_sale_voucher',

                'accounts.name as account_name',
                'branches.name as branch_name',
                'branches.area_name as branch_area_name',
                'parentBranch.name as parent_branch_name',
            );

        return $query->orderBy('account_ledgers.date', 'desc')->orderBy('account_ledgers.id', 'desc')->get();
    }

    public function vatTaxOutputTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $query = $this->vatTaxOutputQuery($request);

        return DataTables::of($query)
            ->editColumn('date', function ($row) use ($generalSettings) {

                $dateFormat = $generalSettings['business_or_shop__date_format'];
                $__date_format = str_replace('-', '/', $dateFormat);

                return $row->date ? date($__date_format, strtotime($row->date)) : '';
            })

            ->editColumn('particulars', function ($row) {

                $voucherType = $row->voucher_type;
                $outputVatTaxParticularAndOnAmount = new \App\Services\Accounts\Reports\VatTax\OutputVatTaxParticularAndOnAmountService();

                return $outputVatTaxParticularAndOnAmount->particulars(voucherType: $voucherType, data: $row);
            })

            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch) {

                    $areaName = $row?->branch_area_name ? '(' . $row?->branch_area_name . ')' : '';

                    if ($row?->parent_branch_name) {

                        return $row?->parent_branch_name . $areaName;
                    } else {

                        return $row?->branch_name . $areaName;
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'] . '(' . __('Business') . ')';
                }
            })

            ->editColumn('voucher_type', function ($row) {

                //return $row->voucher_type;
                $accountLedgerService = new \App\Services\Accounts\AccountLedgerService();
                $type = $accountLedgerService->voucherType($row->voucher_type);

                return '<strong>' . $type['name'] . '</strong>';
            })

            ->editColumn('voucher_no', function ($row) {

                //return $row->voucher_type;
                $accountLedgerService = new \App\Services\Accounts\AccountLedgerService();
                $type = $accountLedgerService->voucherType($row->voucher_type);

                return '<a href="' . (!empty($type['link']) ? route($type['link'], $row->{$type['details_id']}) : '#') . '" id="details_btn" class="fw-bold">' . $row->{$type['voucher_no']} . '</a>';
            })
            ->editColumn('output_amount', fn ($row) => '<span class="table_input_amount fw-bold" data-value="' . $row->credit . '">' .  \App\Utils\Converter::format_in_bdt($row->credit) . '</span>')

            ->editColumn('on_amount', function ($row) {

                $outputVatTaxParticularAndOnAmount = new \App\Services\Accounts\Reports\VatTax\OutputVatTaxParticularAndOnAmountService();
                return $outputVatTaxParticularAndOnAmount->onAmounts(voucherType: $row->voucher_type, data: $row);
            })
            ->rawColumns(['date', 'particulars', 'branch', 'voucher_type', 'voucher_no', 'output_amount', 'on_amount'])
            ->make(true);
    }

    public function VatTaxAmounts(object $request): array
    {
        $query = DB::table('account_ledgers')
            ->where('account_groups.sub_sub_group_number', 8)
            ->where('account_ledgers.amount_type', 'debit')
            ->whereIn(
                'account_ledgers.voucher_type',
                [
                    AccountLedgerVoucherType::Purchase->value,
                    AccountLedgerVoucherType::PurchaseProductTax->value,
                    AccountLedgerVoucherType::SalesReturn->value,
                    AccountLedgerVoucherType::SalesReturnProductTax->value,
                ]
            )
            ->leftJoin('accounts', 'account_ledgers.account_id', 'accounts.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id');

        $this->filter(request: $request, query: $query);

        $inputTaxes = $query->select(
            'accounts.id',
            'accounts.name',
            DB::raw('SUM(account_ledgers.debit) as total_input_tax')
        )->groupBy('accounts.id', 'accounts.name')->get();

        $totalInputTaxAmount = 0;
        foreach ($inputTaxes as $inputTax) {
            $totalInputTaxAmount += $inputTax->total_input_tax;
        }

        $query = DB::table('account_ledgers')
            ->where('account_groups.sub_sub_group_number', 8)
            ->where('account_ledgers.amount_type', 'credit')
            ->whereIn(
                'account_ledgers.voucher_type',
                [
                    AccountLedgerVoucherType::Sales->value,
                    AccountLedgerVoucherType::SaleProductTax->value,
                    AccountLedgerVoucherType::Exchange->value,
                    AccountLedgerVoucherType::PurchaseReturn->value,
                    AccountLedgerVoucherType::SalesReturnProductTax->value,
                ]
            )->leftJoin('accounts', 'account_ledgers.account_id', 'accounts.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id');

        $this->filter(request: $request, query: $query);

        $outputTaxes = $query->select(
            'accounts.id',
            'accounts.name',
            DB::raw('SUM(account_ledgers.credit) as total_output_tax')
        )->groupBy('accounts.id', 'accounts.name')->get();

        $totalOutputTaxAmount = 0;
        foreach ($outputTaxes as $outputTax) {
            $totalOutputTaxAmount += $outputTax->total_output_tax;
        }

        $netAmount = $totalOutputTaxAmount - $totalInputTaxAmount;

        return [
            'inputTaxes' => $inputTaxes,
            'outputTaxes' => $outputTaxes,
            'totalInputTaxAmount' => $totalInputTaxAmount,
            'totalOutputTaxAmount' => $totalOutputTaxAmount,
            'netAmount' => $netAmount,
        ];
    }

    private function filter(object $request, object $query): object
    {
        if ($request->tax_account_id) {

            $query->where('account_ledgers.account_id', $request->tax_account_id);
        }

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('account_ledgers.branch_id', null);
            } else {

                $query->where('account_ledgers.branch_id', $request->branch_id);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('account_ledgers.date', $date_range);
        }

        // if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('account_ledgers.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
