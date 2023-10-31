<?php

namespace App\Http\Controllers\Sales;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Sales\CashRegisterService;
use App\Services\Setups\CashCounterService;

class CashRegisterController extends Controller
{
    public function __construct(
        private CashRegisterService $cashRegisterService,
        private CashCounterService $cashCounterService,
        private AccountService $accountService,
        private BranchService $branchService,
    ) {
    }

    public function create()
    {
        if (!auth()->user()->can('pos_add')) {

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

        if (!$openedCashRegister) {

            return view('sales.cash_register.create', compact('cashCounters', 'saleAccounts', 'cashAccounts', 'branchName'));
        } else {

            return redirect()->route('sales.pos.create');
        }
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('pos_add')) {

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

    // cash register Details
    public function show()
    {
        if (!auth()->user()->can('register_view')) {

            return 'Access Forbidden';
        }

        $queries = $this->detailsRegisterQuery();
        $activeCashRegister = $queries['activeCashRegister'];
        $paymentMethodPayments = $queries['paymentMethodPayments'];
        $accountPayments = $queries['accountPayments'];
        $totalCredit = $queries['totalCredit'];

        return view(
            'sales.cash_register.ajax_view.cash_register_details',
            compact(

                'activeCashRegister',
                'paymentMethodPayments',
                'accountPayments',
                'totalCredit'
            )
        );
    }

    // Cash Register Details For Report
    public function cashRegisterDetailsForReport($crId)
    {
        if (!auth()->user()->can('register_view')) {

            return 'Access Forbidden';
        }

        $queries = $this->detailsRegisterQuery($crId);

        $activeCashRegister = $queries['activeCashRegister'];
        $paymentMethodPayments = $queries['paymentMethodPayments'];
        $accountPayments = $queries['accountPayments'];
        $totalCredit = $queries['totalCredit'];

        return view(
            'sales.cash_register.ajax_view.cash_register_details',
            compact(
                'activeCashRegister',
                'paymentMethodPayments',
                'accountPayments',
                'totalCredit'
            )
        );
    }

    // get closing cash register details
    public function close()
    {
        $queries = $this->detailsRegisterQuery();

        $activeCashRegister = $queries['activeCashRegister'];
        $paymentMethodPayments = $queries['paymentMethodPayments'];
        $accountPayments = $queries['accountPayments'];
        $totalCredit = $queries['totalCredit'];

        return view(
            'sales.cash_register.ajax_view.close_cash_register_view',
            compact(
                'activeCashRegister',
                'paymentMethodPayments',
                'accountPayments',
                'totalCredit'
            )
        );
    }

    // Close cash register
    public function closed(Request $request)
    {
        $this->validate($request, [
            'closed_amount' => 'required',
        ]);

        $closeCashRegister = CashRegister::where('admin_id', auth()->user()->id)->where('status', 1)->first();
        $closeCashRegister->closed_amount = $request->closed_amount;
        $closeCashRegister->closing_note = $request->closing_note;
        $closeCashRegister->closed_at = Carbon::now()->format('Y-m-d H:i:s');
        $closeCashRegister->status = 0;
        $closeCashRegister->save();

        return redirect()->back();
    }

    private function detailsRegisterQuery($crId = null)
    {
        $activeCashRegister = '';
        $activeCashRegisterQuery = DB::table('cash_registers')
            ->leftJoin('branches', 'cash_registers.branch_id', 'branches.id')
            ->leftJoin('users', 'cash_registers.admin_id', 'users.id')
            ->leftJoin('cash_counters', 'cash_registers.cash_counter_id', 'cash_counters.id')
            ->select(
                'cash_registers.id',
                'cash_registers.created_at',
                'cash_registers.closed_at',
                'cash_registers.cash_in_hand',
                'users.prefix as u_prefix',
                'users.name as u_first_name',
                'users.last_name as u_last_name',
                'users.username',
                'users.email as u_email',
                'cash_counters.counter_name',
                'cash_counters.short_name as cc_s_name',
                'branches.name as b_name',
                'branches.branch_code as b_name',
            );

        if (!$crId) {

            $activeCashRegister = $activeCashRegisterQuery
                ->where('users.id', auth()->user()->id)
                ->where('cash_registers.status', 1)->first();
        } else {

            $activeCashRegister = $activeCashRegisterQuery
                ->where('cash_registers.id', $crId)->first();
        }

        $paymentMethodPayments = DB::table('sale_payments')
            ->leftJoin('sales', 'sale_payments.sale_id', 'sales.id')
            ->leftJoin('payment_methods', 'sale_payments.payment_method_id', 'payment_methods.id')
            ->leftJoin('cash_register_transactions', 'sales.id', 'cash_register_transactions.sale_id')
            ->where('cash_register_transactions.cash_register_id', $activeCashRegister->id)
            ->select('payment_methods.name', DB::raw('SUM(paid_amount) as total_paid'))
            ->groupBy('sale_payments.payment_method_id')->groupBy('payment_methods.name')->get();

        $accountPayments = DB::table('sale_payments')
            ->leftJoin('sales', 'sale_payments.sale_id', 'sales.id')
            ->leftJoin('accounts', 'sale_payments.account_id', 'accounts.id')
            ->leftJoin('cash_register_transactions', 'sales.id', 'cash_register_transactions.sale_id')
            ->where('cash_register_transactions.cash_register_id', $activeCashRegister->id)
            ->select('accounts.account_type', DB::raw('SUM(paid_amount) as total_paid'))
            ->groupBy('accounts.account_type')->groupBy('accounts.account_type')->get();

        $totalCredit = DB::table('sales')
            ->leftJoin('cash_register_transactions', 'sales.id', 'cash_register_transactions.sale_id')
            ->where('cash_register_transactions.cash_register_id', $activeCashRegister->id)
            ->select(DB::raw('SUM(sales.due) as total_due'))
            ->groupBy('cash_register_transactions.cash_register_id')
            ->get();

        return [
            'activeCashRegister' => $activeCashRegister,
            'paymentMethodPayments' => $paymentMethodPayments,
            'accountPayments' => $accountPayments,
            'totalCredit' => $totalCredit,
        ];
    }
}
