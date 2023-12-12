<?php

namespace App\Http\Controllers\Purchases\Reports;

use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountService;
use App\Services\Setups\BranchService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseReturnReportController extends Controller
{
    public function __construct(
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
    ) {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('purchase_return_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = config('generalSettings');
            $returns = '';
            $query = DB::table('purchase_returns')
                ->leftJoin('purchases', 'purchase_returns.purchase_id', 'purchases.id')
                ->leftJoin('branches', 'purchase_returns.branch_id', 'branches.id')
                ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
                ->leftJoin('accounts as suppliers', 'purchase_returns.supplier_account_id', 'suppliers.id')
                ->leftJoin('users as createdBy', 'purchase_returns.created_by_id', 'createdBy.id');

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

            $returns = $query->select(
                'purchase_returns.*',
                'purchases.invoice_id as parent_invoice_id',
                'branches.name as branch_name',
                'branches.area_name as branch_area_name',
                'branches.branch_code',
                'parentBranch.name as parent_branch_name',
                'suppliers.name as supplier_name',
                'createdBy.prefix as created_prefix',
                'createdBy.name as created_name',
                'createdBy.last_name as created_last_name',
            )->orderBy('purchase_returns.date_ts', 'desc');

            return DataTables::of($returns)
                ->editColumn('date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', $generalSettings['business__date_format']);

                    return date($__date_format, strtotime($row->date));
                })
                ->editColumn('voucher_no', function ($row) {

                    return '<a href="'.route('purchase.returns.show', $row->id).'" id="details_btn">'.$row->voucher_no.'</a>';
                })
                ->editColumn('parent_invoice_id', function ($row) {

                    if ($row->purchase_id) {

                        return '<a href="'.route('purchases.show', [$row->purchase_id]).'" id="details_btn">'.$row->parent_invoice_id.'</a>';
                    }
                })
                ->editColumn('branch', function ($row) use ($generalSettings) {

                    if ($row->branch_id) {

                        if ($row->parent_branch_name) {

                            return $row->parent_branch_name.'('.$row->area_name.')';
                        } else {

                            return $row->branch_name.'('.$row->area_name.')';
                        }
                    } else {

                        return $generalSettings['business__business_name'];
                    }
                })
                ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="'.$row->total_item.'">'.\App\Utils\Converter::format_in_bdt($row->total_item).'</span>')
                ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="'.$row->total_qty.'">'.\App\Utils\Converter::format_in_bdt($row->total_qty).'</span>')
                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="'.$row->net_total_amount.'">'.\App\Utils\Converter::format_in_bdt($row->net_total_amount).'</span>')
                ->editColumn('return_discount', fn ($row) => '<span class="return_discount" data-value="'.$row->return_discount.'">'.\App\Utils\Converter::format_in_bdt($row->return_discount).'</span>')
                ->editColumn('return_tax_amount', fn ($row) => '<span class="return_tax_amount" data-value="'.$row->return_tax_amount.'">'.\App\Utils\Converter::format_in_bdt($row->return_tax_amount).'</span>')
                ->editColumn('total_return_amount', fn ($row) => '<span class="total_return_amount" data-value="'.$row->total_return_amount.'">'.\App\Utils\Converter::format_in_bdt($row->total_return_amount).'</span>')
                ->editColumn('received_amount', fn ($row) => '<span class="received_amount" data-value="'.$row->received_amount.'">'.\App\Utils\Converter::format_in_bdt($row->received_amount).'</span>')
                ->editColumn('due', fn ($row) => '<span class="due" data-value="'.$row->due.'">'.\App\Utils\Converter::format_in_bdt($row->due).'</span>')
                ->editColumn('createdBy', function ($row) {

                    return $row->created_prefix.' '.$row->created_name.' '.$row->created_last_name;
                })
                ->rawColumns(['action', 'date', 'voucher_no', 'parent_invoice_id', 'branch', 'total_item', 'total_qty', 'net_total_amount', 'return_discount', 'return_tax_amount', 'total_return_amount', 'received_amount', 'due', 'createdBy'])
                ->make(true);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.reports.purchase_returns_report.index', compact('branches', 'supplierAccounts'));
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
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $returns = '';
        $query = DB::table('purchase_returns')
            ->leftJoin('purchases', 'purchase_returns.purchase_id', 'purchases.id')
            ->leftJoin('branches', 'purchase_returns.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('accounts as suppliers', 'purchase_returns.supplier_account_id', 'suppliers.id')
            ->leftJoin('users as createdBy', 'purchase_returns.created_by_id', 'createdBy.id');

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

        $returns = $query->select(
            'purchase_returns.*',
            'purchases.invoice_id as parent_invoice_id',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'suppliers.name as supplier_name',
            'createdBy.prefix as created_prefix',
            'createdBy.name as created_name',
            'createdBy.last_name as created_last_name',
        )->orderBy('purchase_returns.date_ts', 'desc')->get();

        return view('purchase.reports.purchase_returns_report.ajax_view.print', compact('returns', 'ownOrParentBranch', 'filteredBranchName', 'filteredSupplierName', 'fromDate', 'toDate'));
    }
}
