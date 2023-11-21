<?php

namespace App\Http\Controllers\Accounts;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\AccountLedgerEntryService;

class AccountLedgerController extends Controller
{
    public function __construct(
        private AccountService $accountService,
        private BranchService $branchService,
        private AccountLedgerService $accountLedgerService,
        private AccountLedgerEntryService $accountLedgerEntryService,
    ) {
    }

    public function index(Request $request, $id, $fromDate = null, $toDate = null, $branchId = null)
    {
        if ($request->ajax()) {

            return $this->accountLedgerEntryService->ledgerTable(request: $request, id: $id);
        }

        $account = $this->accountService->singleAccountById(id: $id, with: ['group']);
        $branches = '';
        if ($account?->group?->is_global == BooleanType::True->value) {

            $branches = $this->branchService->branches(with: ['parentBranch'])
                ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();
        }

        return view('accounting.accounts.ledger.index', compact('account', 'branches', 'fromDate', 'toDate', 'branchId'));
    }

    function print(Request $request, $id) {

        $ownOrParentBranch = '';
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch;
            } else {

                $branchName = auth()->user()?->branch;
            }
        }

        $filteredBranchName = $request->branch_name;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $account = $this->accountService->singleAccountById(id: $id, with: ['group']);

        $entries = $this->accountLedgerEntryService->ledgerEntriesPrint(request: $request, id: $id);

        return view('accounting.accounts.ledger.ajax_view.print_ledger', compact('entries', 'request', 'fromDate', 'toDate', 'filteredBranchName', 'account'));
    }
}
