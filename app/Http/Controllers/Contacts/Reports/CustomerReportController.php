<?php

namespace App\Http\Controllers\Contacts\Reports;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\Accounts\AccountService;
use App\Services\Contacts\Reports\CustomerReportService;

class CustomerReportController extends Controller
{
    public function __construct(
        private CustomerReportService $customerReportService,
        private BranchService $branchService,
        private AccountService $accountService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('customer_report'), 403);
        
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
