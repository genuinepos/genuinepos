<?php

namespace App\Services\Products;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class ProductLedgerEntryService
{
    public function ledgerTable(object $request, int $id): ?object
    {
        $ledgers = '';
        $generalSettings = config('generalSettings');
        $accountStartDate = date('Y-m-d', strtotime($generalSettings['business_or_shop__account_start_date']));

        $ledgers = $this->ledgerEntriesQuery(request: $request, id: $id);

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $this->generateOpeningStock(id: $id, ledgers: $ledgers, fromDateYmd: $fromDateYmd, request: $request, generalSettings: $generalSettings);
        }

        $runningIn = 0;
        $runningOut = 0;
        foreach ($ledgers as $ledger) {

            $runningIn += $ledger->in;
            $runningOut += $ledger->out;

            $runningStock = $runningIn - $runningOut;
            $ledger->running_stock = $runningStock;
        }

        return DataTables::of($ledgers)
            ->editColumn('date', function ($row) use ($generalSettings) {

                $dateFormat = $generalSettings['business_or_shop__date_format'];
                return $row->date_ts ? date($dateFormat, strtotime($row->date_ts)) : '';
            })

            ->editColumn('branch', function ($row) use ($generalSettings) {

                $branchName = null;
                $areaName = $row->area_name ? '(' . $row->area_name . ')' : '';
                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        $branchName = $row->parent_branch_name;
                    } else {

                        $branchName = $row->branch_name;
                    }
                } else {

                    $branchName = $generalSettings['business_or_shop__business_name'];
                }

                return $branchName . $areaName;
            })

            ->editColumn('warehouse', function ($row) use ($generalSettings) {

                $warehouseCode = $row->warehouse_code ? '-('.$row->warehouse_code.')' : '';
               return $row->warehouse_name.$warehouseCode;
            })

            ->editColumn('voucher_type', function ($row) {

                $productLedgerService = new \App\Services\Products\ProductLedgerService();
                $type = $productLedgerService->voucherType($row->voucher_type);

                return '<strong>' . $type['name'] . '</strong>';
            })

            ->editColumn('variant', function ($row) {

                return isset($row->variant_name) ? $row->variant_name : '';
            })

            ->editColumn('voucher_no', function ($row) {

                $productLedgerService = new \App\Services\Products\ProductLedgerService();
                $type = $productLedgerService->voucherType($row->voucher_type);

                return '<a href="' . (!empty($type['link']) ? route($type['link'], $row->{$type['details_id']}) : '#') . '" id="details_btn" class="fw-bold">' . $row->{$type['voucher_no']} . '</a>';
            })
            ->editColumn('in', fn ($row) => '<span class="in fw-bold" data-value="' . $row->in . '">' . ($row->in > 0 ? \App\Utils\Converter::format_in_bdt($row->in) : '') . '</span>')
            ->editColumn('out', fn ($row) => '<span class="out fw-bold" data-value="' . $row->out . '">' . ($row->out > 0 ? \App\Utils\Converter::format_in_bdt($row->out) : '') . '</span>')
            ->editColumn('running_stock', function ($row) {

                if ($row->running_stock < 0) {

                    return '(<span class="running_stock text-danger fw-bold">' . \App\Utils\Converter::format_in_bdt(abs($row->running_stock)) . '</span>)';
                } else if ($row->running_stock >= 0) {

                    return '<span class="running_stock fw-bold">' . \App\Utils\Converter::format_in_bdt(abs($row->running_stock)) . '</span>';
                }
            })
            ->rawColumns(['date', 'variant', 'branch', 'warehouse', 'voucher_type', 'voucher_no', 'in', 'out', 'running_stock'])
            ->make(true);
    }

    public function ledgerEntriesQuery(object $request, int $id): ?object
    {
        $query = DB::table('product_ledgers')
            ->whereRaw('concat(product_ledgers.in,product_ledgers.out) > 0')
            ->where('product_ledgers.product_id', $id);

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('product_ledgers.branch_id', null);
            } else {

                $query->where('product_ledgers.branch_id', $request->branch_id);
            }
        }

        if ($request->warehouse_id) {

            $query->where('product_ledgers.warehouse_id', $request->warehouse_id);
        }

        if ($request->variant_id) {

            $query->where('product_ledgers.variant_id', $request->variant_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('product_ledgers.date_ts', $date_range);
        }

        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('product_ledgers.branch_id', auth()->user()->branch_id);
        }

        $query->leftJoin('branches', 'product_ledgers.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')

            ->leftJoin('warehouses', 'product_ledgers.warehouse_id', 'warehouses.id')

            ->leftJoin('product_variants', 'product_ledgers.variant_id', 'product_variants.id')

            ->leftJoin('sale_products', 'product_ledgers.sale_product_id', 'sale_products.id')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')

            ->leftJoin('sale_return_products', 'product_ledgers.sale_return_product_id', 'sale_return_products.id')
            ->leftJoin('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')

            ->leftJoin('purchase_products', 'product_ledgers.purchase_product_id', 'purchase_products.id')
            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')

            ->leftJoin('purchase_return_products', 'product_ledgers.purchase_return_product_id', 'purchase_return_products.id')
            ->leftJoin('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')

            ->leftJoin('product_opening_stocks', 'product_ledgers.opening_stock_product_id', 'product_opening_stocks.id')

            ->leftJoin('stock_adjustment_products', 'product_ledgers.stock_adjustment_product_id', 'stock_adjustment_products.id')
            ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')

            ->leftJoin('productions', 'product_ledgers.production_id', 'productions.id')

            ->leftJoin('transfer_stock_products', 'product_ledgers.transfer_stock_product_id', 'transfer_stock_products.id')
            ->leftJoin('transfer_stocks', 'transfer_stock_products.transfer_stock_id', 'transfer_stocks.id')

            ->leftJoin('stock_issue_products', 'product_ledgers.stock_issue_product_id', 'stock_issue_products.id')
            ->leftJoin('stock_issues', 'stock_issue_products.stock_issue_id', 'stock_issues.id')

            ->select(
                'product_ledgers.branch_id',
                'product_ledgers.date',
                'product_ledgers.date_ts',
                'product_ledgers.voucher_type',
                'product_ledgers.product_id',
                'product_ledgers.variant_id',
                'product_ledgers.in',
                'product_ledgers.out',

                'branches.name as branch_name',
                'branches.area_name',
                'branches.branch_code',
                'parentBranch.name as parent_branch_name',

                'warehouses.warehouse_name',
                'warehouses.warehouse_code',

                'sales.id as sale_id',
                'sales.invoice_id as sales_voucher',
                'sale_returns.id as sale_return_id',
                'sale_returns.voucher_no as sale_return_voucher',
                'purchases.id as purchase_id',
                'purchases.invoice_id as purchase_voucher',
                'purchase_returns.id as purchase_return_id',
                'purchase_returns.voucher_no as purchase_return_voucher',
                'stock_adjustments.id as stock_adjustment_id',
                'stock_adjustments.voucher_no as stock_adjustment_voucher',
                'productions.id as production_id',
                'productions.voucher_no as production_voucher',
                'transfer_stocks.id as transfer_stock_id',
                'transfer_stocks.voucher_no as transfer_stock_voucher',
                'stock_issues.id as stock_issue_id',
                'stock_issues.voucher_no as stock_issue_voucher_no',
                'product_variants.variant_name',
            );

        return $query->orderBy('product_ledgers.date_ts', 'asc')->orderBy('product_ledgers.id', 'asc')->get();
    }

    public function ledgerEntriesPrint(object $request, int $id): ?object
    {
        $ledgers = '';
        $generalSettings = config('generalSettings');
        $accountStartDate = date('Y-m-d', strtotime($generalSettings['business_or_shop__account_start_date']));

        $ledgers = $this->ledgerEntriesQuery(request: $request, id: $id);

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $this->generateOpeningStock(id: $id, ledgers: $ledgers, fromDateYmd: $fromDateYmd, request: $request, generalSettings: $generalSettings);
        }

        $runningIn = 0;
        $runningOut = 0;
        foreach ($ledgers as $ledger) {

            $runningIn += $ledger->in;
            $runningOut += $ledger->out;

            $runningStock = $runningIn - $runningOut;
            $ledger->running_stock = $runningStock;
        }

        return $ledgers;
    }

    private function generateOpeningStock(int $id, object $ledgers, string $fromDateYmd, object $request, array $generalSettings): void
    {
        $productOpeningStock = '';
        $productOpeningStockQ = DB::table('product_ledgers')->where('product_ledgers.product_id', $id);

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $productOpeningStockQ->where('product_ledgers.branch_id', null);
            } else {

                $productOpeningStockQ->where('product_ledgers.branch_id', $request->branch_id);
            }
        }

        if ($request->warehouse_id) {

            $productOpeningStockQ->where('product_ledgers.warehouse_id', $request->warehouse_id);
        }

        if ($request->variant_id) {

            $productOpeningStockQ->where('product_ledgers.variant_id', $request->variant_id);
        }

        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $productOpeningStockQ->where('product_ledgers.branch_id', auth()->user()->branch_id);
        }

        $productOpeningStock = $productOpeningStockQ->select(
            DB::raw("IFNULL(SUM(case when timestamp(product_ledgers.date_ts) < '$fromDateYmd' then product_ledgers.in end), 0) as opening_stock_in"),
            DB::raw("IFNULL(SUM(case when timestamp(product_ledgers.date_ts) < '$fromDateYmd' then product_ledgers.out end), 0) as opening_stock_out"),
        )->groupBy('product_ledgers.product_id', 'product_ledgers.variant_id')->get();

        $openingStockIn = $productOpeningStock->sum('opening_stock_in');
        $openingStockOut = $productOpeningStock->sum('opening_stock_out');

        $openingStock = $openingStockIn - $openingStockOut;

        $branchName = null;
        $branchCode = null;
        $branchAreaName = null;
        $parentBranchName = null;

        if ($request->branch_name) {

            $branchName = $request->branch_name;
        } else {

            if (auth()->user()?->branch) {

                if (auth()->user()?->branch?->parentBranch) {

                    $parentBranchName = auth()->user()?->branch?->parentBranch->name;
                    $branchCode = auth()->user()?->branch->branch_code;
                    $branchAreaName = auth()->user()?->branch?->area_name;
                } else {

                    $branchName = auth()->user()?->branch?->name;
                    $branchCode = auth()->user()?->branch->branch_code;
                    $branchAreaName = auth()->user()?->branch?->area_name;
                }
            } else {

                $branchName = $generalSettings['business_or_shop__business_name'] . '(' . __('Business') . ')';
            }
        }

        $warehouseName = $request->warehouse_name != 'All' ? $request->warehouse_name: '';

        $arr = [
            'id' => 0,
            'branch_id' => 'branch_id',
            'branch_name' => $branchName,
            'area_name' => $branchAreaName,
            'branch_code' => $branchCode,
            'parent_branch_name' => $parentBranchName,
            'warehouse_name' => $warehouseName,
            'warehouse_code' => null,
            'voucher_type' => 0,
            'sales_voucher' => null,
            'variant_name' => null,
            'date' => null,
            'date_ts' => null,
            'product_id' => $id,
            'in' => $openingStock >= 0 ? $openingStock : 0.00,
            'out' => $openingStock < 0 ? $openingStock : 0.00,
            'running_stock' => $openingStock,
        ];

        $stdArr = (object) $arr;

        $ledgers->prepend($stdArr);
    }
}
