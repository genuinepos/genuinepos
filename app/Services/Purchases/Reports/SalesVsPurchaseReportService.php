<?php

namespace App\Services\Purchases\Reports;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Enums\PurchaseStatus;
use Illuminate\Support\Facades\DB;

class SalesVsPurchaseReportService
{
    public function salesVsPurchaseAmounts(object $request): array
    {
        $authUserBranchId = auth()->user()->branch_id;

        $saleQuery = DB::table('sales')->where('sales.status', SaleStatus::Final->value)
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('currencies', 'branches.currency_id', 'currencies.id');

        $saleReturnQuery = DB::table('sale_returns')
            ->leftJoin('branches', 'sale_returns.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('currencies', 'branches.currency_id', 'currencies.id');

        $purchaseQuery = DB::table('purchases')->where('purchases.purchase_status', PurchaseStatus::Purchase->value)
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('currencies', 'branches.currency_id', 'currencies.id');

        $purchaseReturnQuery = DB::table('purchase_returns')
            ->leftJoin('branches', 'purchase_returns.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('currencies', 'branches.currency_id', 'currencies.id');

        if (!empty($request->branch_id)) {

            if ($request->branch_id == 'NULL') {

                $saleQuery->where('sales.branch_id', null);
                $saleReturnQuery->where('sale_returns.branch_id', null);
                $purchaseQuery->where('purchases.branch_id', null);
                $purchaseReturnQuery->where('purchase_returns.branch_id', null);
            } else {

                $saleQuery->where('sales.branch_id', $request->branch_id);
                $saleReturnQuery->where('sale_returns.branch_id', $request->branch_id);
                $purchaseQuery->where('purchases.branch_id', $request->branch_id);
                $purchaseReturnQuery->where('purchase_returns.branch_id', $request->branch_id);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $saleQuery->whereBetween('sales.sale_date_ts', $date_range); // Final
            $saleReturnQuery->whereBetween('sale_returns.date_ts', $date_range); // Final
            $purchaseQuery->whereBetween('purchases.report_date', $date_range); // Final
            $purchaseReturnQuery->whereBetween('purchase_returns.date_ts', $date_range); // Final
        }

        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $saleQuery->where('sales.branch_id', auth()->user()->branch_id);
            $saleReturnQuery->where('sale_returns.branch_id', auth()->user()->branch_id);
            $purchaseQuery->where('purchases.branch_id', auth()->user()->branch_id);
            $purchaseReturnQuery->where('purchase_returns.branch_id', auth()->user()->branch_id);
        }

        $sale = $saleQuery->select(
            DB::raw(
                '
                    SUM(
                        CASE
                            WHEN ' . ($authUserBranchId === null ? 1 : 0) . ' = 1
                                THEN sales.total_invoice_amount * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            ELSE
                                sales.total_invoice_amount
                        END
                    ) AS total_sale,
                    SUM(
                        CASE
                            WHEN ' . ($authUserBranchId === null ? 1 : 0) . ' = 1
                                THEN sales.order_tax_amount * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            ELSE
                                sales.order_tax_amount
                        END
                    ) AS total_sale_tax
                '
            ),
        )->get();

        $saleReturn = $saleReturnQuery->select(
            DB::raw(
                '
                    SUM(
                        CASE
                            WHEN ' . ($authUserBranchId === null ? 1 : 0) . ' = 1
                                THEN sale_returns.total_return_amount * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            ELSE
                                sale_returns.total_return_amount
                        END
                    ) AS total_sale_return,

                    SUM(
                        CASE
                            WHEN ' . ($authUserBranchId === null ? 1 : 0) . ' = 1
                                THEN sale_returns.return_tax_amount * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            ELSE
                                sale_returns.return_tax_amount
                        END
                    ) AS total_sale_return_tax
                '
            )
        )->get();

        $purchase = $purchaseQuery->select(
            DB::raw(
                '
                    SUM(
                        CASE
                            WHEN ' . ($authUserBranchId === null ? 1 : 0) . ' = 1
                                THEN purchases.total_purchase_amount * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            ELSE
                                purchases.total_purchase_amount
                        END
                    ) AS total_purchase,
                    SUM(
                        CASE
                            WHEN ' . ($authUserBranchId === null ? 1 : 0) . ' = 1
                                THEN purchases.purchase_tax_amount * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            ELSE
                                purchases.purchase_tax_amount
                        END
                    ) AS total_purchase_tax
                '
            ),
        )->get();

        $purchaseReturn = $purchaseReturnQuery->select(
            DB::raw(
                '
                    SUM(
                        CASE
                            WHEN ' . ($authUserBranchId === null ? 1 : 0) . ' = 1
                                THEN purchase_returns.total_return_amount * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            ELSE
                                purchase_returns.total_return_amount
                        END
                    ) AS total_purchase_return,

                    SUM(
                        CASE
                            WHEN ' . ($authUserBranchId === null ? 1 : 0) . ' = 1
                                THEN purchase_returns.return_tax_amount * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            ELSE
                                purchase_returns.return_tax_amount
                        END
                    ) AS total_purchase_return_tax
                '
            )
        )->get();

        $totalSaleExcludedTax = $sale->sum('total_sale') - $sale->sum('total_sale_tax');
        $totalSaleIncludedTax = $sale->sum('total_sale');
        $totalSalesReturn = $saleReturn->sum('total_sale_return');

        $totalPurchaseExcludedTax = $purchase->sum('total_purchase') - $purchase->sum('total_purchase_tax');
        $totalPurchaseIncludedTax = $purchase->sum('total_purchase');
        $totalPurchaseReturn = $purchase->sum('total_purchase_return');

        $totalSaleIncludedReturn = $totalSaleIncludedTax - $totalSalesReturn;
        $totalPurchaseIncludedReturn = $totalPurchaseIncludedTax - $totalPurchaseReturn;

        $saleMinusPurchase = $totalSaleIncludedReturn - $totalPurchaseIncludedReturn;

        return [
            'totalSaleExcludedTax' => $totalSaleExcludedTax,
            'totalSaleIncludedTax' => $totalSaleIncludedTax,
            'totalSalesReturn' => $totalSalesReturn,
            'totalPurchaseExcludedTax' => $totalPurchaseExcludedTax,
            'totalPurchaseIncludedTax' => $totalPurchaseIncludedTax,
            'totalPurchaseReturn' => $totalPurchaseReturn,
            'totalSaleIncludedReturn' => $totalSaleIncludedReturn,
            'totalPurchaseIncludedReturn' => $totalPurchaseIncludedReturn,
            'saleMinusPurchase' => $saleMinusPurchase,
        ];
    }
}
