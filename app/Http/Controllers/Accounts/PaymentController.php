<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Models\Setups\Branch;
use Illuminate\Support\Facades\DB;
use App\Services\Sales\SaleService;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\CodeGenerationService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Accounts\PaymentService;
use App\Services\Sales\SalesReturnService;
use App\Services\Purchases\PurchaseService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\DayBookVoucherService;
use App\Services\Purchases\PurchaseReturnService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Interfaces\Accounts\PaymentControllerMethodContainersInterface;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private SaleService $saleService,
        private SalesReturnService $salesReturnService,
        private PurchaseService $purchaseService,
        private PurchaseReturnService $purchaseReturnService,
        private BranchService $branchService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private AccountLedgerService $accountLedgerService,
        private PaymentMethodService $paymentMethodService,
        private DayBookService $dayBookService,
        private DayBookVoucherService $dayBookVoucherService,
        private BranchSettingService $branchSettingService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
    ) {
    }

    public function index(Request $request, $debitAccountId = null)
    {
        if ($request->ajax()) {

            return $this->paymentService->paymentsTable(request: $request, debitAccountId: $debitAccountId);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $debitAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('accounting.accounting_vouchers.payments.index', compact('branches', 'debitAccounts'));
    }

    function show(PaymentControllerMethodContainersInterface $paymentControllerMethodContainersInterface, $id)
    {
        $showMethodContainer = $paymentControllerMethodContainersInterface->showMethodContainer(
            id: $id,
            accountingVoucherService: $this->accountingVoucherService,
        );

        extract($showMethodContainer);

        return view('accounting.accounting_vouchers.payments.ajax_view.show', compact('payment'));
    }

    public function create(PaymentControllerMethodContainersInterface $paymentControllerMethodContainersInterface, $debitAccountId = null)
    {
        $createMethodContainer = $paymentControllerMethodContainersInterface->createMethodContainer(
            debitAccountId: $debitAccountId,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            dayBookVoucherService: $this->dayBookVoucherService,
            paymentMethodService: $this->paymentMethodService,
        );

        extract($createMethodContainer);

        return view('accounting.accounting_vouchers.payments.ajax_view.create', compact('vouchers', 'account', 'accounts', 'methods', 'receivableAccounts'));
    }

    public function store(Request $request, PaymentControllerMethodContainersInterface $paymentControllerMethodContainersInterface, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'paying_amount' => 'required',
            'payment_method_id' => 'required',
            'debit_account_id' => 'required',
            'credit_account_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $storeMethodContainer = $paymentControllerMethodContainersInterface->storeMethodContainer(
                request: $request,
                paymentService: $this->paymentService,
                branchSettingService: $this->branchSettingService,
                accountLedgerService: $this->accountLedgerService,
                accountingVoucherService: $this->accountingVoucherService,
                accountingVoucherDescriptionService: $this->accountingVoucherDescriptionService,
                accountingVoucherDescriptionReferenceService: $this->accountingVoucherDescriptionReferenceService,
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

            return view('accounting.accounting_vouchers.save_and_print_template.print_payment', compact('payment'));
        } else {

            return response()->json(['successMsg' => __("Payment added successfully.")]);
        }
    }

    function edit(PaymentControllerMethodContainersInterface $paymentControllerMethodContainersInterface, $id, $debitAccountId = null)
    {
        $editMethodContainer = $paymentControllerMethodContainersInterface->editMethodContainer(
            id: $id,
            debitAccountId: $debitAccountId,
            accountingVoucherService: $this->accountingVoucherService,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            dayBookVoucherService: $this->dayBookVoucherService,
            paymentMethodService: $this->paymentMethodService,
        );

        extract($editMethodContainer);

        return view('accounting.accounting_vouchers.payments.ajax_view.edit', compact('payment', 'vouchers', 'account', 'accounts', 'methods', 'payableAccounts'));
    }

    public function update(Request $request, PaymentControllerMethodContainersInterface $paymentControllerMethodContainersInterface, $id)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'paying_amount' => 'required',
            'payment_method_id' => 'required',
            'debit_account_id' => 'required',
            'credit_account_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $updateMethodContainer = $paymentControllerMethodContainersInterface->updateMethodContainer(
                id: $id,
                request: $request,
                paymentService: $this->paymentService,
                branchSettingService: $this->branchSettingService,
                accountLedgerService: $this->accountLedgerService,
                accountingVoucherService: $this->accountingVoucherService,
                accountingVoucherDescriptionService: $this->accountingVoucherDescriptionService,
                accountingVoucherDescriptionReferenceService: $this->accountingVoucherDescriptionReferenceService,
                dayBookService: $this->dayBookService,
                purchaseService: $this->purchaseService,
                salesReturnService: $this->salesReturnService,
            );

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Payment updated successfully."));
    }

    public function delete(PaymentControllerMethodContainersInterface $paymentControllerMethodContainersInterface, $id)
    {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $paymentControllerMethodContainersInterface->deleteMethodContainer(
                id: $id,
                paymentService: $this->paymentService,
                saleService: $this->saleService,
                salesReturnService: $this->salesReturnService,
                purchaseService: $this->purchaseService,
                purchaseReturnService: $this->purchaseReturnService,
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Payment deleted successfully."));
    }
}