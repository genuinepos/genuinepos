<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Services\CodeGenerationService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Accounts\ExpenseService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Interfaces\Accounts\ExpenseControllerMethodContainersInterface;

class ExpenseController extends Controller
{
    public function __construct(
        private ExpenseService $expenseService,
        private BranchService $branchService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private AccountLedgerService $accountLedgerService,
        private PaymentMethodService $paymentMethodService,
        private DayBookService $dayBookService,
        private BranchSettingService $branchSettingService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
    ) {
    }

    function index(Request $request)
    {
        if (!auth()->user()->can('view_expense')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->expenseService->expensesTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('accounting.accounting_vouchers.expenses.index', compact('branches'));
    }

    function show(ExpenseControllerMethodContainersInterface $expenseControllerMethodContainersInterface, $id)
    {
        $showMethodContainer = $expenseControllerMethodContainersInterface->showMethodContainer(
            id: $id,
            accountingVoucherService: $this->accountingVoucherService,
        );

        extract($showMethodContainer);

        return view('accounting.accounting_vouchers.expenses.ajax_view.show', compact('expense'));
    }

    function create(ExpenseControllerMethodContainersInterface $expenseControllerMethodContainersInterface)
    {
        if (!auth()->user()->can('add_expense')) {

            abort(403, 'Access Forbidden.');
        }

        $createMethodContainer = $expenseControllerMethodContainersInterface->createMethodContainer(
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            paymentMethodService: $this->paymentMethodService,
        );

        extract($createMethodContainer);

        return view('accounting.accounting_vouchers.expenses.ajax_view.create', compact('accounts', 'methods', 'expenseAccounts'));
    }

    public function store(Request $request, ExpenseControllerMethodContainersInterface $expenseControllerMethodContainersInterface, CodeGenerationService $codeGenerator)
    {
        if (!auth()->user()->can('add_expense')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'date' => 'required|date',
            'total_amount' => 'required',
            'payment_method_id' => 'required',
            'credit_account_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $storeMethodContainer = $expenseControllerMethodContainersInterface->storeMethodContainer(
                request: $request,
                expenseService: $this->expenseService,
                branchSettingService: $this->branchSettingService,
                accountLedgerService: $this->accountLedgerService,
                accountingVoucherService: $this->accountingVoucherService,
                accountingVoucherDescriptionService: $this->accountingVoucherDescriptionService,
                dayBookService: $this->dayBookService,
                codeGenerator: $codeGenerator,
            );

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('accounting.accounting_vouchers.save_and_print_template.print_expense', compact('expense'));
        } else {

            return response()->json(['successMsg' => __("Expense added successfully.")]);
        }
    }

    public function edit(ExpenseControllerMethodContainersInterface $expenseControllerMethodContainersInterface, $id)
    {
        if (!auth()->user()->can('edit_expense')) {

            abort(403, 'Access Forbidden.');
        }

        $editMethodContainer = $expenseControllerMethodContainersInterface->editMethodContainer(
            id: $id,
            accountingVoucherService: $this->accountingVoucherService,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            paymentMethodService: $this->paymentMethodService,
        );

        extract($editMethodContainer);

        return view('accounting.accounting_vouchers.expenses.ajax_view.edit', compact('expense', 'accounts', 'methods', 'expenseAccounts'));
    }

    public function update(Request $request, ExpenseControllerMethodContainersInterface $expenseControllerMethodContainersInterface, $id)
    {
        if (!auth()->user()->can('add_expense')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'date' => 'required|date',
            'total_amount' => 'required',
            'payment_method_id' => 'required',
            'credit_account_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $updateMethodContainer = $expenseControllerMethodContainersInterface->updateMethodContainer(
                id: $id,
                request: $request,
                expenseService: $this->expenseService,
                branchSettingService: $this->branchSettingService,
                accountLedgerService: $this->accountLedgerService,
                accountingVoucherService: $this->accountingVoucherService,
                accountingVoucherDescriptionService: $this->accountingVoucherDescriptionService,
                dayBookService: $this->dayBookService,
            );

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Expense updated successfully."));
    }

    function delete($id)
    {
        if (!auth()->user()->can('delete_expense')) {

            abort(403, 'Access Forbidden.');
        }

        try {
            DB::beginTransaction();

            $deleteExpense = $this->expenseService->deleteExpense(id: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Expense deleted successfully."));
    }
}
