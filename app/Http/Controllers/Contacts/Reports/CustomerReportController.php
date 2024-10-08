<?php

namespace App\Http\Controllers\Contacts\Reports;

use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\Accounts\AccountService;
use App\Services\Contacts\Reports\CustomerReportService;
use App\Http\Requests\Contacts\Reports\CustomerReportIndexRequest;
use App\Http\Requests\Contacts\Reports\CustomerReportPrintRequest;

class CustomerReportController extends Controller
{
    public function __construct(
        private CustomerReportService $customerReportService,
        private BranchService $branchService,
        private AccountService $accountService,
    ) {
    }

    public function index(CustomerReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->customerReportService->customersReportTable(request: $request);
        }

        $branches = '';
        if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == BooleanType::False->value) {

            $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();
        }

        $customerAccounts = $this->accountService->customerAccounts(request: $request);

        return view('contacts.reports.customer_report.index', compact('branches', 'customerAccounts'));
    }

    public function print(CustomerReportPrintRequest $request)
    {
        $ownOrParentBranch = '';
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch;
            } else {

                $branchName = auth()->user()?->branch;
            }
        }

        $customers = $this->customerReportService->customerReportQuery(request: $request)->get();

        $filteredBranchName = $request->branch_name;
        $filteredCustomerName = $request->customer_name;

        return view('contacts.reports.customer_report.ajax_view.print', compact(
            'customers',
            'ownOrParentBranch',
            'filteredBranchName',
            'filteredCustomerName',
        ));
    }
}
