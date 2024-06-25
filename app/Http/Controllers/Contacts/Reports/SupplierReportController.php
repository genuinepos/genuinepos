<?php

namespace App\Http\Controllers\Contacts\Reports;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\Accounts\AccountService;
use App\Services\Contacts\Reports\SupplierReportService;

class SupplierReportController extends Controller
{
    public function __construct(
        private SupplierReportService $supplierReportService,
        private BranchService $branchService,
        private AccountService $accountService,
    ) {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('customer_report'), 403);

        if ($request->ajax()) {

            return $this->supplierReportService->suppliersReportTable(request: $request);
        }

        $supplierAccounts = $this->accountService->supplierAccounts();
        $branches = '';
        // if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) {
        if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == BooleanType::False->value) {

            $branches = $this->branchService->branches(with: ['parentBranch'])
                ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();
        }

        return view('contacts.reports.supplier_report.index', compact('branches', 'supplierAccounts'));
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

        $suppliers = $this->supplierReportService->supplierReportQuery(request: $request)->get();

        $filteredBranchName = $request->branch_name;
        $filteredSupplierName = $request->supplier_name;

        return view('contacts.reports.supplier_report.ajax_view.print', compact(
            'suppliers',
            'ownOrParentBranch',
            'filteredBranchName',
            'filteredSupplierName',
        ));
    }
}
