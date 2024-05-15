<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Interfaces\Accounts\ReceiptControllerMethodContainersInterface;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Accounts\DayBookVoucherService;
use App\Services\Accounts\ReceiptService;
use App\Services\CodeGenerationService;
use App\Services\Purchases\PurchaseReturnService;
use App\Services\Purchases\PurchaseService;
use App\Services\Sales\SaleService;
use App\Services\Sales\SalesReturnService;
use App\Services\Setups\BranchService;
use App\Services\Setups\PaymentMethodService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    public function __construct(
        private ReceiptService $receiptService,
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
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
    ) {
    }

    public function index(Request $request, $creditAccountId = null)
    {
        abort_if(!auth()->user()->can('receipts_index'), 403);

        if ($request->ajax()) {

            return $this->receiptService->receiptsTable(request: $request, creditAccountId: $creditAccountId);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $creditAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('accounting.accounting_vouchers.receipts.index', compact('branches', 'creditAccounts'));
    }

    public function show(ReceiptControllerMethodContainersInterface $receiptControllerMethodContainersInterface, $id)
    {
        $showMethodContainer = $receiptControllerMethodContainersInterface->showMethodContainer(
            id: $id,
            accountingVoucherService: $this->accountingVoucherService,
        );

        extract($showMethodContainer);

        return view('accounting.accounting_vouchers.receipts.ajax_view.show', compact('receipt'));
    }

    public function print($id, Request $request, ReceiptControllerMethodContainersInterface $receiptControllerMethodContainersInterface)
    {
        $printMethodContainer = $receiptControllerMethodContainersInterface->printMethodContainer(
            id: $id,
            request: $request,
            accountingVoucherService: $this->accountingVoucherService,
        );

        extract($printMethodContainer);

        return view('accounting.accounting_vouchers.print_templates.print_receipt', compact('receipt', 'printPageSize'));
    }

    public function create(ReceiptControllerMethodContainersInterface $receiptControllerMethodContainersInterface, $creditAccountId = null)
    {
        abort_if(!auth()->user()->can('receipts_create'), 403);

        $createMethodContainer = $receiptControllerMethodContainersInterface->createMethodContainer(
            creditAccountId: $creditAccountId,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            dayBookVoucherService: $this->dayBookVoucherService,
            paymentMethodService: $this->paymentMethodService,
        );

        extract($createMethodContainer);

        return view('accounting.accounting_vouchers.receipts.ajax_view.create', compact('vouchers', 'account', 'accounts', 'methods', 'receivableAccounts'));
    }

    public function store(Request $request, ReceiptControllerMethodContainersInterface $receiptControllerMethodContainersInterface, CodeGenerationService $codeGenerator)
    {
        abort_if(!auth()->user()->can('receipts_create'), 403);

        $this->receiptService->receiptValidation(request: $request);

        try {
            DB::beginTransaction();

            $storeMethodContainer = $receiptControllerMethodContainersInterface->storeMethodContainer(
                request: $request,
                receiptService: $this->receiptService,
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
            $printPageSize = $request->print_page_size;
            return view('accounting.accounting_vouchers.print_templates.print_receipt', compact('receipt', 'printPageSize'));
        } else {

            return response()->json(['successMsg' => __('Receipt added successfully.')]);
        }
    }

    public function edit(ReceiptControllerMethodContainersInterface $receiptControllerMethodContainersInterface, $id, $creditAccountId = null)
    {
        abort_if(!auth()->user()->can('receipts_edit'), 403);

        $editMethodContainer = $receiptControllerMethodContainersInterface->editMethodContainer(
            id: $id,
            creditAccountId: $creditAccountId,
            accountingVoucherService: $this->accountingVoucherService,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            dayBookVoucherService: $this->dayBookVoucherService,
            paymentMethodService: $this->paymentMethodService,
        );

        extract($editMethodContainer);

        return view('accounting.accounting_vouchers.receipts.ajax_view.edit', compact('receipt', 'vouchers', 'account', 'accounts', 'methods', 'receivableAccounts'));
    }

    public function update(Request $request, ReceiptControllerMethodContainersInterface $receiptControllerMethodContainersInterface, $id)
    {
        abort_if(!auth()->user()->can('receipts_edit'), 403);

        $this->receiptService->receiptValidation(request: $request);

        try {
            DB::beginTransaction();

            $updateMethodContainer = $receiptControllerMethodContainersInterface->updateMethodContainer(
                id: $id,
                request: $request,
                receiptService: $this->receiptService,
                accountLedgerService: $this->accountLedgerService,
                accountingVoucherService: $this->accountingVoucherService,
                accountingVoucherDescriptionService: $this->accountingVoucherDescriptionService,
                accountingVoucherDescriptionReferenceService: $this->accountingVoucherDescriptionReferenceService,
                dayBookService: $this->dayBookService,
                saleService: $this->saleService,
                purchaseReturnService: $this->purchaseReturnService,
            );

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Receipt updated successfully.'));
    }

    public function delete(ReceiptControllerMethodContainersInterface $receiptControllerMethodContainersInterface, $id)
    {
        abort_if(!auth()->user()->can('receipts_delete'), 403);

        try {
            DB::beginTransaction();

            $receiptControllerMethodContainersInterface->deleteMethodContainer(
                id: $id,
                receiptService: $this->receiptService,
                saleService: $this->saleService,
                salesReturnService: $this->salesReturnService,
                purchaseService: $this->purchaseService,
                purchaseReturnService: $this->purchaseReturnService,
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Receipt deleted successfully.'));
    }
}
