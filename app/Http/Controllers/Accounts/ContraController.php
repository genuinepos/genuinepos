<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\CodeGenerationService;
use App\Services\Accounts\ContraService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Interfaces\Accounts\ContraControllerMethodContainersInterface;

class ContraController extends Controller
{
    public function __construct(
        private ContraService $contraService,
        private BranchService $branchService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private AccountLedgerService $accountLedgerService,
        private PaymentMethodService $paymentMethodService,
        private DayBookService $dayBookService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
    ) {
        $this->middleware('expireDate');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('contras_index'), 403);

        if ($request->ajax()) {

            return $this->contraService->contraTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('accounting.accounting_vouchers.contras.index', compact('branches'));
    }

    public function show(ContraControllerMethodContainersInterface $contraControllerMethodContainersInterface, $id)
    {
        $showMethodContainer = $contraControllerMethodContainersInterface->showMethodContainer(
            id: $id,
            accountingVoucherService: $this->accountingVoucherService,
        );

        extract($showMethodContainer);

        return view('accounting.accounting_vouchers.contras.ajax_view.show', compact('contra'));
    }

    public function print($id, Request $request, ContraControllerMethodContainersInterface $contraControllerMethodContainersInterface)
    {
        $printMethodContainer = $contraControllerMethodContainersInterface->printMethodContainer(
            id: $id,
            request: $request,
            accountingVoucherService: $this->accountingVoucherService,
        );

        extract($printMethodContainer);

        return view('accounting.accounting_vouchers.print_templates.print_contra', compact('contra', 'printPageSize'));
    }

    public function create(ContraControllerMethodContainersInterface $contraControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('contras_create'), 403);

        $createMethodContainer = $contraControllerMethodContainersInterface->createMethodContainer(
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            paymentMethodService: $this->paymentMethodService,
        );

        extract($createMethodContainer);

        return view('accounting.accounting_vouchers.contras.ajax_view.create', compact('accounts', 'methods'));
    }

    public function store(Request $request, ContraControllerMethodContainersInterface $contraControllerMethodContainersInterface, CodeGenerationService $codeGenerator)
    {
        abort_if(!auth()->user()->can('contras_create'), 403);

        $this->contraService->contraValidation(request: $request);

        try {
            DB::beginTransaction();

            $storeMethodContainer = $contraControllerMethodContainersInterface->storeMethodContainer(
                request: $request,
                contraService: $this->contraService,
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

            $printPageSize = $request->print_page_size;
            return view('accounting.accounting_vouchers.print_templates.print_contra', compact('contra', 'printPageSize'));
        } else {

            return response()->json(['successMsg' => __('Contra added successfully.')]);
        }
    }

    public function edit($id, ContraControllerMethodContainersInterface $contraControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('contras_edit'), 403);

        $editMethodContainer = $contraControllerMethodContainersInterface->editMethodContainer(
            id: $id,
            accountingVoucherService: $this->accountingVoucherService,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            paymentMethodService: $this->paymentMethodService,
        );

        extract($editMethodContainer);

        return view('accounting.accounting_vouchers.contras.ajax_view.edit', compact('accounts', 'methods', 'contra'));
    }

    public function update($id, Request $request, ContraControllerMethodContainersInterface $contraControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('contras_edit'), 403);

        $this->contraService->contraValidation(request: $request);

        try {
            DB::beginTransaction();

            $updateMethodContainer = $contraControllerMethodContainersInterface->updateMethodContainer(
                id: $id,
                request: $request,
                contraService: $this->contraService,
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

        return response()->json(__('Contra updated successfully.'));
    }

    public function delete($id)
    {
        abort_if(!auth()->user()->can('contras_delete'), 403);

        try {
            DB::beginTransaction();

            $deleteContra = $this->contraService->deleteContra(id: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Contra deleted successfully.'));
    }
}
