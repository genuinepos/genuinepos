<?php

namespace App\Http\Controllers\Sales;

use App\Enums\BooleanType;
use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountService;
use App\Services\Sales\CashRegisterService;
use App\Services\Setups\BranchService;
use App\Services\Setups\CashCounterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashRegisterController extends Controller
{
    public function __construct(
        private CashRegisterService $cashRegisterService,
        private CashCounterService $cashCounterService,
        private AccountService $accountService,
        private BranchService $branchService,
    ) {
    }

    public function create($saleId = null)
    {
        if (! auth()->user()->can('pos_add')) {

            abort(403, 'Access Forbidden.');
        }

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
            ->where('status', BooleanType::True->value)
            ->first();

        $cashAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name')
            ->get();

        $branchName = $this->branchService->branchName();

        if (! $openedCashRegister) {

            return view('sales.cash_register.create', compact('cashCounters', 'saleAccounts', 'cashAccounts', 'branchName', 'saleId'));
        } else {

            return redirect()->route('sales.pos.create');
        }
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('pos_add')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'cash_counter_id' => 'required',
            'sale_account_id' => 'required',
            'cash_account_id' => 'required',
        ], [
            'sale_account_id.required' => 'Sale A/c is required',
            'cash_account_id.required' => 'Cash A/c is required',
        ]);

        $this->cashRegisterService->addCashRegister(request: $request);

        return redirect()->route('sales.pos.create');
    }

    public function show($id)
    {
        if (! auth()->user()->can('register_view')) {

            return 'Access Forbidden';
        }

        $openedCashRegister = $this->cashRegisterService->singleCashRegister(with: [
            'user',
            'branch',
            'branch.parentBranch',
            'cashCounter',
        ])->where('id', $id)->first();

        $cashRegisterData = $this->cashRegisterService->cashRegisterData(id: $id);

        return view('sales.cash_register.ajax_view.show', compact('openedCashRegister', 'cashRegisterData'));
    }

    public function close($id)
    {
       if (! auth()->user()->can('register_close')) {

            return 'Access Forbidden';
        }

        $openedCashRegister = $this->cashRegisterService->singleCashRegister(with: [
            'user',
            'branch',
            'branch.parentBranch',
            'cashCounter',
        ])->where('id', $id)->first();

        $cashRegisterData = $this->cashRegisterService->cashRegisterData(id: $id);

        return view('sales.cash_register.ajax_view.close_cash_register', compact('openedCashRegister', 'cashRegisterData'));
    }

    public function closed($id, Request $request)
    {
        $this->validate($request, [
            'closing_cash' => 'required',
        ]);

        $this->cashRegisterService->closeCashRegister(id: $id, request: $request);

        return redirect()->back();
    }
}
