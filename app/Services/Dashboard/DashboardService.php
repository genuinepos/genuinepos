<?php

namespace App\Services\Dashboard;

use Carbon\Carbon;
use App\Enums\RoleType;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Enums\PurchaseStatus;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use Yajra\DataTables\Facades\DataTables;

class DashboardService
{
    public function dashboardCardData(object $request): array
    {
        $totalSales = 0;
        $totalSaleDue = 0;
        $totalSaleDiscount = 0;
        $totalPurchase = 0;
        $totalPurchaseDue = 0;
        $totalExpense = 0;

        $purchases = '';
        $sales = '';
        $expenses = '';
        $products = '';
        $users = '';
        $adjustments = '';

        $userQuery = DB::table('users');
        $productQuery = DB::table('product_access_branches');
        $purchaseQuery = DB::table('purchases');
        $saleQuery = DB::table('sales');
        $expenseQuery = DB::table('accounting_vouchers')->where('accounting_vouchers.voucher_type', AccountingVoucherType::Expense->value);
        $adjustmentQuery = DB::table('stock_adjustments');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $purchaseQuery->where('purchases.branch_id', null);
                $saleQuery->where('sales.branch_id', null);
                $expenseQuery->where('accounting_vouchers.branch_id', null);
                $userQuery->where('users.branch_id', null);
                $adjustmentQuery->where('stock_adjustments.branch_id', null);
                $productQuery->where('branch_id', null);
            } else {

                $purchaseQuery->where('purchases.branch_id', $request->branch_id);
                $saleQuery->where('sales.branch_id', $request->branch_id);
                $expenseQuery->where('accounting_vouchers.branch_id', $request->branch_id);
                $userQuery->where('users.branch_id', $request->branch_id);
                $productQuery->where('branch_id', $request->branch_id);
                $adjustmentQuery->where('stock_adjustments.branch_id', $request->branch_id);
            }
        }

        if ($request->date_range != 'all_time') {

            if (isset($request->date_range)) {

                $dateRange = explode('~', $request->date_range);
                $formDate = date('Y-m-d', strtotime($dateRange[0]));
                $toDate = date('Y-m-d', strtotime($dateRange[1]));

                $range = [Carbon::parse($formDate), Carbon::parse($toDate)->endOfDay()];

                $saleQuery->whereBetween('sales.sale_date_ts', $range); // Final
                $purchaseQuery->whereBetween('purchases.report_date', $range);
                $expenseQuery->whereBetween('accounting_vouchers.date_ts', $range);
                $adjustmentQuery->whereBetween('stock_adjustments.date_ts', $range);
            }
        }

        if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $sales = $saleQuery->where('sales.branch_id', auth()->user()->branch_id);
            $purchases = $purchaseQuery->where('purchases.branch_id', auth()->user()->branch_id);
            $expenses = $expenseQuery->where('accounting_vouchers.branch_id', auth()->user()->branch_id);
            $users = $userQuery->where('users.branch_id', auth()->user()->branch_id)->count();
            $productQuery->where('branch_id', auth()->user()->branch_id);
            $adjustments = $adjustmentQuery->where('stock_adjustments.branch_id', auth()->user()->branch_id);
        }

        $purchaseQuery->select(
            DB::raw('sum(total_purchase_amount) as total_purchase'),
            DB::raw('sum(due) as total_due'),
        );

        $saleQuery->select(
            DB::raw('sum(total_invoice_amount) as total_sale'),
            DB::raw('sum(due) as total_due'),
        );

        $expenseQuery->select(
            DB::raw('sum(total_amount) as total_expense'),
        );

        $adjustmentQuery->select(
            DB::raw('sum(net_total_amount) as total_adjustment'),
        );

        $sales = $saleQuery->where('sales.status', SaleStatus::Final->value)->get();
        $purchases = $purchaseQuery->get();
        $expenses = $expenseQuery->get();
        $users = $userQuery->count();
        $adjustments = $adjustmentQuery->get();

        $totalSales = $sales->sum('total_sale');
        $totalSaleDue = $sales->sum('total_due');

        $totalPurchase = $purchases->sum('total_purchase');
        $totalPurchaseDue = $purchases->sum('total_due');

        $totalExpense = $expenses->sum('total_expense');
        $products = $productQuery->distinct('product_id')->count();
        $totalAdjustment = $adjustments->sum('total_adjustment');

        return [
            'total_sale' => \App\Utils\Converter::format_in_bdt($totalSales),
            'totalSaleDue' => \App\Utils\Converter::format_in_bdt($totalSaleDue),
            'totalPurchase' => \App\Utils\Converter::format_in_bdt($totalPurchase),
            'totalPurchaseDue' => \App\Utils\Converter::format_in_bdt($totalPurchaseDue),
            'totalExpense' => \App\Utils\Converter::format_in_bdt($totalExpense),
            'users' => \App\Utils\Converter::format_in_bdt($users),
            'products' => \App\Utils\Converter::format_in_bdt($products),
            'totalAdjustment' => \App\Utils\Converter::format_in_bdt($totalAdjustment),
        ];
    }

    public function stockAlertProductsTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $alertQtyProducts = '';
        $query = DB::table('product_stocks')
            ->leftJoin('products', 'product_stocks.product_id', 'products.id')
            ->leftJoin('product_variants', 'product_stocks.variant_id', 'product_variants.id')
            ->leftJoin('branches', 'product_stocks.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->where('products.is_manage_stock', BooleanType::True->value)
            ->where('warehouse_id', null)
            ->whereColumn('products.alert_quantity', '>=', 'product_stocks.all_stock');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('product_stocks.branch_id', NULL);
            } else {

                $query->where('product_stocks.branch_id', $request->branch_id);
            }
        }

        if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('product_stocks.branch_id', auth()->user()->branch_id);
        }

        $alertQtyProducts = $query->select(
            'products.id',
            'products.name',
            'products.product_code',
            'products.alert_quantity',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'branches.name as branch_name',
            'branches.area_name',
            'parentBranch.name as parent_branch_name',
            'product_stocks.branch_id',
            'product_stocks.all_stock',
            'units.code_name as unit_code_name',
        )->orderBy('products.name', 'asc');

        return DataTables::of($alertQtyProducts)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {

                return $row->name . ($row->variant_name ? '-' . $row->variant_name : '');
            })
            ->editColumn('code', function ($row) {

                return $row->variant_code ? $row->variant_code : $row->product_code;
            })
            ->editColumn('branch', function ($row)  use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')';
                    }
                } else {

                    return $generalSettings['business__business_name'];
                }
            })
            ->editColumn('alert_quantity', function ($row) {

                return \App\Utils\Converter::format_in_bdt($row->alert_quantity);
            })
            ->editColumn('stock', function ($row) {

                return  \App\Utils\Converter::format_in_bdt($row->all_stock) . '/' . $row->unit_code_name;
            })
            ->rawColumns(['name', 'code', 'branch', 'alert_quantity', 'stock', 'stock'])->make(true);
    }

    public function salesOrderTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $orders = '';

        $query = DB::table('sales')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->where('sales.order_status', BooleanType::True->value);

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('sales.branch_id', NULL);
            } else {

                $query->where('sales.branch_id', $request->branch_id);
            }
        }

        if ($request->date_range != 'all_time') {

            if (isset($request->date_range)) {

                $dateRange = explode('~', $request->date_range);
                $formDate = date('Y-m-d', strtotime($dateRange[0]));
                $toDate = date('Y-m-d', strtotime($dateRange[1]));

                $range = [Carbon::parse($formDate), Carbon::parse($toDate)->endOfDay()];

                $query->whereBetween('sales.date_ts', $range); // Final
            }
        }

        if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }

        $orders = $query->select(
            'sales.id',
            'sales.branch_id',
            'sales.order_id',
            'sales.date',
            'sales.total_invoice_amount',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'customers.name as customer_name',
        )->orderBy('sales.date_ts', 'desc');

        return DataTables::of($orders)
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business__date_format']);

                return date($__date_format, strtotime($row->date));
            })
            ->editColumn('order_id', function ($row) {

                return '<a href="' . route('sale.orders.show', [$row->id]) . '" id="details_btn">' . $row->order_id . '</a>';
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')';
                    }
                } else {

                    return $generalSettings['business__business_name'];
                }
            })
            ->editColumn('customer', fn ($row) => $row->customer_name)
            ->editColumn('delivery_status', fn ($row) => __('Pending'))

            ->editColumn('total_invoice_amount', fn ($row) => '<span class="total_invoice_amount" data-value="' . $row->total_invoice_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_invoice_amount) . '</span>')

            ->rawColumns(['date',   'total_invoice_amount', 'order_id', 'branch', 'customer'])
            ->make(true);
    }

    public function salesDueInvoicesTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $sales = '';

        $query = DB::table('sales')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->where('sales.status', SaleStatus::Final->value)
            ->where('sales.due', '>', 0);

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('sales.branch_id', NULL);
            } else {

                $query->where('sales.branch_id', $request->branch_id);
            }
        }

        if ($request->date_range != 'all_time') {

            if (isset($request->date_range)) {

                $dateRange = explode('~', $request->date_range);
                $formDate = date('Y-m-d', strtotime($dateRange[0]));
                $toDate = date('Y-m-d', strtotime($dateRange[1]));

                $range = [Carbon::parse($formDate), Carbon::parse($toDate)->endOfDay()];

                $query->whereBetween('sales.sale_date_ts', $range); // Final
            }
        }

        if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }

        $sales = $query->select(
            'sales.id',
            'sales.branch_id',
            'sales.invoice_id',
            'sales.date',
            'sales.total_invoice_amount',
            'sales.due',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'customers.name as customer_name',
        )->orderBy('sales.date_ts', 'desc');

        return DataTables::of($sales)
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business__date_format']);
                return date($__date_format, strtotime($row->date));
            })
            ->editColumn('invoice_id', function ($row) {

                return '<a href="' . route('sales.show', [$row->id]) . '" id="details_btn">' . $row->invoice_id . '</a>';
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')';
                    }
                } else {

                    return $generalSettings['business__business_name'];
                }
            })
            ->editColumn('customer', fn ($row) => $row->customer_name)

            ->editColumn('total_invoice_amount', fn ($row) => \App\Utils\Converter::format_in_bdt($row->total_invoice_amount))

            ->editColumn('due', fn ($row) => \App\Utils\Converter::format_in_bdt($row->due))

            ->rawColumns(['date', 'total_invoice_amount', 'due', 'invoice_id', 'branch', 'customer'])
            ->make(true);
    }

    public function purchaseDueInvoicesTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $purchases = '';

        $query = DB::table('purchases')
            ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->where('purchases.purchase_status', PurchaseStatus::Purchase->value)
            ->where('purchases.due', '>', 0);

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('purchases.branch_id', NULL);
            } else {

                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if ($request->date_range != 'all_time') {

            if (isset($request->date_range)) {

                $dateRange = explode('~', $request->date_range);
                $formDate = date('Y-m-d', strtotime($dateRange[0]));
                $toDate = date('Y-m-d', strtotime($dateRange[1]));

                $range = [Carbon::parse($formDate), Carbon::parse($toDate)->endOfDay()];

                $query->whereBetween('purchases.report_date', $range); // Final
            }
        }

        if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('purchases.branch_id', auth()->user()->branch_id);
        }

        $purchases = $query->select(
            'purchases.id',
            'purchases.branch_id',
            'purchases.invoice_id',
            'purchases.date',
            'purchases.total_purchase_amount',
            'purchases.due',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'suppliers.name as supplier_name',
        )->orderBy('purchases.report_date', 'desc');

        return DataTables::of($purchases)
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business__date_format']);
                return date($__date_format, strtotime($row->date));
            })
            ->editColumn('invoice_id', function ($row) {

                return '<a href="' . route('purchases.show', [$row->id]) . '" id="details_btn">' . $row->invoice_id . '</a>';
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')';
                    }
                } else {

                    return $generalSettings['business__business_name'];
                }
            })
            ->editColumn('supplier', fn ($row) => $row->supplier_name)

            ->editColumn('total_purchase_amount', fn ($row) => \App\Utils\Converter::format_in_bdt($row->total_purchase_amount))

            ->editColumn('due', fn ($row) => \App\Utils\Converter::format_in_bdt($row->due))

            ->rawColumns(['date', 'total_purchase_amount', 'due', 'invoice_id', 'branch', 'supplier'])
            ->make(true);
    }
}
