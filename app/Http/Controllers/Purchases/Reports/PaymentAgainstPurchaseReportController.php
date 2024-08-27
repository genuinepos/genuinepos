<?php

namespace App\Http\Controllers\Purchases\Reports;

use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Purchases\Reports\PaymentAgainstPurchaseReportService;
use App\Http\Requests\Purchases\Reports\PaymentAgainstPurchaseReportIndexRequest;
use App\Http\Requests\Purchases\Reports\PaymentAgainstPurchaseReportPrintRequest;

class PaymentAgainstPurchaseReportController extends Controller
{
    public function __construct(
        private PaymentAgainstPurchaseReportService $paymentAgainstPurchaseReportService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
    ) {}

    public function index(PaymentAgainstPurchaseReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->paymentAgainstPurchaseReportService->paymentAgainstPurchaseReportTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId, sortingByGroupNumber: 'desc');

        return view('purchase.reports.payments_against_purchase_report.index', compact('branches', 'supplierAccounts'));
    }

    public function print(PaymentAgainstPurchaseReportPrintRequest $request)
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

        $paidAgainstPurchases = $this->paymentAgainstPurchaseReportService->query(request: $request)->get();

        return view('purchase.reports.payments_against_purchase_report.ajax_view.print', compact('paidAgainstPurchases', 'ownOrParentBranch', 'filteredBranchName', 'filteredSupplierName', 'fromDate', 'toDate'));
    }
}
