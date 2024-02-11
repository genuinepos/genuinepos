<?php

namespace App\Http\Controllers\Accounts\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\Reports\VatTax\VatTaxReportService;

class VatTaxReportController extends Controller
{
    public function __construct(
        private VatTaxReportService $vatTaxReportService,
        private BranchService $branchService,
        private AccountService $accountService,
    ) {
        $this->middleware('expireDate');
    }

    public function index()
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $contacts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('accounting.reports.vat_tax_report.index', compact('branches', 'taxAccounts', 'contacts'));
    }

    public function vatTaxInputTable(Request $request) {

        if ($request->ajax()) {

            return $this->vatTaxReportService->vatTaxInputTable(request: $request);
        }
    }

    public function vatTaxOutputTable(Request $request) {

        if ($request->ajax()) {

            return $this->vatTaxReportService->vatTaxOutputTable(request: $request);
        }
    }

    public function vatTaxAmounts(Request $request) {

        return $this->vatTaxReportService->VatTaxAmounts(request: $request);
    }

    public function printVatTax(Request $request) {

        $ownOrParentBranch = '';
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch;
            } else {

                $branchName = auth()->user()?->branch;
            }
        }

        $filteredBranchName = $request->branch_name;
        $filteredTaxAccountName = $request->tax_account_name;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $amounts = $this->vatTaxReportService->VatTaxAmounts(request: $request);

        return view('accounting.reports.vat_tax_report.ajax_view.print', compact('ownOrParentBranch', 'filteredBranchName', 'filteredTaxAccountName', 'fromDate', 'toDate', 'amounts'));
    }
}
