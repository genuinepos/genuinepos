<?php

namespace App\Http\Controllers\Purchases\Reports;

use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountService;
use App\Services\Setups\BranchService;
use App\Services\Setups\WarehouseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PurchaseReturnProductReportController extends Controller
{
    public function __construct(
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
    ) {
    }

    // Index view of supplier report
    public function index(Request $request)
    {
        if (! auth()->user()->can('purchase_returned_product_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = config('generalSettings');
            $purchaseReturnProducts = '';
            $query = DB::table('purchase_return_products')
                ->leftJoin('purchase_returns', 'purchase_return_products.purchase_return_id', '=', 'purchase_returns.id')
                ->leftJoin('branches', 'purchase_returns.branch_id', 'branches.id')
                ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
                ->leftJoin('products', 'purchase_return_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'purchase_return_products.variant_id', 'product_variants.id')
                ->leftJoin('accounts as suppliers', 'purchase_returns.supplier_account_id', 'suppliers.id')
                ->leftJoin('units', 'purchase_return_products.unit_id', 'units.id')
                ->leftJoin('warehouses', 'purchase_return_products.warehouse_id', 'warehouses.id');

            if ($request->product_id) {

                $query->where('purchase_return_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {

                $query->where('purchase_return_products.variant_id', $request->variant_id);
            }

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('purchase_returns.branch_id', null);
                } else {

                    $query->where('purchase_returns.branch_id', $request->branch_id);
                }
            }

            if ($request->supplier_account_id) {

                $query->where('purchase_returns.supplier_account_id', $request->supplier_account_id);
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('purchase_returns.date_ts', $date_range); // Final
            }

            if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

                $query->where('purchase_returns.branch_id', auth()->user()->branch_id);
            }

            $purchaseReturnProducts = $query->select(
                'purchase_return_products.purchase_return_id',
                'purchase_return_products.product_id',
                'purchase_return_products.variant_id',
                'purchase_return_products.unit_cost_exc_tax',
                'purchase_return_products.unit_discount_amount',
                'purchase_return_products.unit_tax_percent',
                'purchase_return_products.unit_tax_amount',
                'purchase_return_products.unit_cost_inc_tax',
                'purchase_return_products.return_qty',
                'purchase_return_products.return_subtotal',
                'units.code_name as unit_code',
                'purchase_returns.id as return_id',
                'purchase_returns.branch_id',
                'purchase_returns.voucher_no',
                'purchase_returns.date',
                'purchase_returns.date_ts',
                'products.name',
                'products.product_code',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_price',
                'suppliers.name as supplier_name',
                'branches.name as branch_name',
                'branches.area_name as branch_area_name',
                'branches.branch_code',
                'parentBranch.name as parent_branch_name',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->orderBy('purchase_returns.date_ts', 'desc');

            return DataTables::of($purchaseReturnProducts)
                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - '.$row->variant_name : '';

                    return Str::limit($row->name, 35, '').$variant;
                })
                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date($generalSettings['business__date_format'], strtotime($row->date));
                })
                ->editColumn('branch', function ($row) use ($generalSettings) {

                    if ($row->branch_id) {

                        if ($row->parent_branch_name) {

                            return $row->parent_branch_name.'('.$row->branch_area_name.')';
                        } else {

                            return $row->branch_name.'('.$row->branch_area_name.')';
                        }
                    } else {

                        return $generalSettings['business__business_name'];
                    }
                })
                ->editColumn('stock_location', function ($row) use ($generalSettings) {

                    if ($row->warehouse_name) {

                        return $row->warehouse_name.'/'.$row->warehouse_code;
                    } else {

                        if ($row->branch_id) {

                            if ($row->parent_branch_name) {

                                return $row->parent_branch_name.'('.$row->branch_area_name.')';
                            } else {

                                return $row->branch_name.'('.$row->branch_area_name.')';
                            }
                        } else {

                            return $generalSettings['business__business_name'];
                        }
                    }
                })
                ->editColumn('voucher_no', fn ($row) => '<a href="'.route('purchase.returns.show', [$row->return_id]).'" class="text-hover" id="details_btn" title="View">'.$row->voucher_no.'</a>')
                ->editColumn('return_qty', fn ($row) => \App\Utils\Converter::format_in_bdt($row->return_qty).'/<span class="return_qty" data-value="'.$row->return_qty.'">'.$row->unit_code.'</span>')
                ->editColumn('unit_cost_exc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt($row->unit_cost_exc_tax))
                ->editColumn('unit_discount_amount', fn ($row) => \App\Utils\Converter::format_in_bdt($row->unit_discount_amount))
                ->editColumn('unit_tax_amount', fn ($row) => '('.\App\Utils\Converter::format_in_bdt($row->unit_tax_percent).'%)='.\App\Utils\Converter::format_in_bdt($row->unit_tax_amount))
                ->editColumn('unit_cost_inc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt($row->unit_cost_inc_tax))
                ->editColumn('return_subtotal', fn ($row) => '<span class="return_subtotal" data-value="'.$row->return_subtotal.'">'.\App\Utils\Converter::format_in_bdt($row->return_subtotal).'</span>')

                ->rawColumns(['product', 'product_code', 'date', 'branch', 'stock_location', 'return_qty', 'voucher_no', 'unit_cost_exc_tax', 'unit_discount_amount', 'unit_tax_amount', 'unit_cost_inc_tax', 'return_subtotal'])
                ->make(true);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.reports.purchase_returned_products_report.index', compact('branches', 'supplierAccounts', 'ownBranchIdOrParentBranchId'));
    }

    public function print(Request $request)
    {
        $ownOrParentBranch = '';
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch;
            } else {

                $branchName = auth()->user()?->branch;
            }
        }

        $filteredBranchName = $request->branch_name;
        $filteredSupplierName = $request->supplier_name;
        $filteredProductName = $request->search_product;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $purchaseReturnProducts = '';
        $query = DB::table('purchase_return_products')
            ->leftJoin('purchase_returns', 'purchase_return_products.purchase_return_id', '=', 'purchase_returns.id')
            ->leftJoin('branches', 'purchase_returns.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('products', 'purchase_return_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_return_products.variant_id', 'product_variants.id')
            ->leftJoin('accounts as suppliers', 'purchase_returns.supplier_account_id', 'suppliers.id')
            ->leftJoin('units', 'purchase_return_products.unit_id', 'units.id')
            ->leftJoin('warehouses', 'purchase_return_products.warehouse_id', 'warehouses.id');

        if ($request->product_id) {

            $query->where('purchase_return_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('purchase_return_products.variant_id', $request->variant_id);
        }

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('purchase_returns.branch_id', null);
            } else {

                $query->where('purchase_returns.branch_id', $request->branch_id);
            }
        }

        if ($request->supplier_account_id) {

            $query->where('purchase_returns.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchase_returns.date_ts', $date_range); // Final
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $query->where('purchase_returns.branch_id', auth()->user()->branch_id);
        }

        $purchaseReturnProducts = $query->select(
            'purchase_return_products.purchase_return_id',
            'purchase_return_products.product_id',
            'purchase_return_products.variant_id',
            'purchase_return_products.unit_cost_exc_tax',
            'purchase_return_products.unit_discount_amount',
            'purchase_return_products.unit_tax_percent',
            'purchase_return_products.unit_tax_amount',
            'purchase_return_products.unit_cost_inc_tax',
            'purchase_return_products.return_qty',
            'purchase_return_products.return_subtotal',
            'units.code_name as unit_code',
            'purchase_returns.id as return_id',
            'purchase_returns.branch_id',
            'purchase_returns.voucher_no',
            'purchase_returns.date',
            'purchase_returns.date_ts',
            'products.name',
            'products.product_code',
            'products.product_price',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_price',
            'suppliers.name as supplier_name',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
        )->orderBy('purchase_returns.date_ts', 'desc')->get();

        return view('purchase.reports.purchase_returned_products_report.ajax_view.print', compact('purchaseReturnProducts', 'fromDate', 'toDate', 'filteredBranchName', 'filteredSupplierName', 'filteredProductName', 'ownOrParentBranch'));
    }
}
