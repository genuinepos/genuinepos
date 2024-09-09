<?php

namespace App\Http\Controllers\Sales;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Sales\CashRegisterService;
use App\Services\Setups\CashCounterService;
use App\Http\Requests\Sales\CashRegisterCloseRequest;
use App\Http\Requests\Sales\CashRegisterIndexRequest;
use App\Http\Requests\Sales\CashRegisterStoreRequest;
use App\Http\Requests\Sales\CashRegisterClosedRequest;

class CashRegisterController extends Controller
{
    public function __construct(
        private CashRegisterService $cashRegisterService,
        private CashCounterService $cashCounterService,
        private AccountService $accountService,
        private BranchService $branchService,
        private UserService $userService,
    ) {}

    public function index(CashRegisterIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->cashRegisterService->cashRegistersTable($request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $users = null;
        $usersQuery = $this->userService->users()->select('id', 'prefix', 'name', 'last_name');

        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $usersQuery->where('branch_id', auth()->user()->branch_id);
        }

        $users = $usersQuery->get();

        $cashCounters = $this->cashCounterService->cashCounters()->get();

        return view('sales.cash_register.index', compact('branches', 'users', 'cashCounters'));
    }

    public function create($saleId = null, $jobCardId = null, $saleScreenType = null)
    {
        $cashCounters = $this->cashCounterService->cashCounters()
            ->where('branch_id', auth()->user()->branch_id)
            ->get(['id', 'counter_name', 'short_name']);

        $saleAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $openedCashRegister = $this->cashRegisterService->singleCashRegister(with: ['branch', 'user'])
            ->where('user_id', auth()->user()->id)
            ->where('branch_id', auth()->user()->branch)
            ->where('status', BooleanType::True->value)
            ->first();

        $cashAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name')
            ->get();

        $branchName = $this->branchService->branchName();

        if (!$openedCashRegister) {

            return view('sales.cash_register.create', compact('cashCounters', 'saleAccounts', 'cashAccounts', 'branchName', 'saleId', 'jobCardId', 'saleScreenType'));
        } else {

            return redirect()->route('sales.pos.create', $saleScreenType);
        }
    }

    public function store(CashRegisterStoreRequest $request)
    {
        $addCashRegister = $this->cashRegisterService->addCashRegister(request: $request);

        if (isset($addCashRegister['pass']) && $addCashRegister['pass'] == false) {

            session()->flash('errorMsg', $addCashRegister['msg']);
            return redirect()->back()->withInput();
        }

        if ($request->sale_id) {

            return redirect()->route('sales.pos.edit', [$request->sale_id, $request->sale_screen_type]);
        } else {

            return redirect()->route('sales.pos.create', [$request->job_card_id, $request->sale_screen_type]);
        }
    }

    public function show($id)
    {
        $openedCashRegister = $this->cashRegisterService->singleCashRegister(with: [
            'user',
            'branch',
            'branch.parentBranch',
            'cashCounter',
        ])->where('id', $id)->first();

        $cashRegisterData = $this->cashRegisterService->cashRegisterData(id: $id);

        return view('sales.cash_register.ajax_view.show', compact('openedCashRegister', 'cashRegisterData'));
    }

    public function close($id, CashRegisterCloseRequest $request)
    {
        $openedCashRegister = $this->cashRegisterService->singleCashRegister(with: [
            'user',
            'branch',
            'branch.parentBranch',
            'cashCounter',
        ])->where('id', $id)->first();

        $cashRegisterData = $this->cashRegisterService->cashRegisterData(id: $id);

        return view('sales.cash_register.ajax_view.close_cash_register', compact('openedCashRegister', 'cashRegisterData'));
    }

    public function closed($id, CashRegisterClosedRequest $request)
    {
        $this->cashRegisterService->closeCashRegister(id: $id, request: $request);

        return response()->json('Success');
    }
}
