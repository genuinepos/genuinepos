<?php

namespace App\Http\Controllers\StockAdjustments;

use App\Http\Controllers\Controller;
use App\Interfaces\StockAdjustments\StockAdjustmentControllerMethodContainersInterface;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\CodeGenerationService;
use App\Services\Products\ProductLedgerService;
use App\Services\Products\ProductStockService;
use App\Services\Setups\BranchService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Setups\WarehouseService;
use App\Services\StockAdjustments\StockAdjustmentProductService;
use App\Services\StockAdjustments\StockAdjustmentService;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    public function __construct(
        private StockAdjustmentService $stockAdjustmentService,
        private StockAdjustmentProductService $stockAdjustmentProductService,
        private PaymentMethodService $paymentMethodService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
        private BranchSettingService $branchSettingService,
        private ProductStockService $productStockService,
        private DayBookService $dayBookService,
        private AccountLedgerService $accountLedgerService,
        private ProductLedgerService $productLedgerService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('stock_adjustment_all')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->stockAdjustmentService->stockAdjustmentsTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('stock_adjustments.index', compact('branches'));
    }

    public function show($id, StockAdjustmentControllerMethodContainersInterface $stockAdjustmentControllerMethodContainersInterface)
    {
        $showMethodContainer = $stockAdjustmentControllerMethodContainersInterface->showMethodContainer(
            id: $id,
            stockAdjustmentService: $this->stockAdjustmentService,
        );

        extract($showMethodContainer);

        return view('stock_adjustments.ajax_view.show', compact('adjustment'));
    }

    public function create(StockAdjustmentControllerMethodContainersInterface $stockAdjustmentControllerMethodContainersInterface)
    {
        if (! auth()->user()->can('stock_adjustment_add')) {

            abort(403, 'Access Forbidden.');
        }

        $createMethodContainer = $stockAdjustmentControllerMethodContainersInterface->createMethodContainer(
            branchService: $this->branchService,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            paymentMethodService: $this->paymentMethodService,
            warehouseService: $this->warehouseService,
        );

        extract($createMethodContainer);

        return view('stock_adjustments.create', compact('expenseAccounts', 'accounts', 'warehouses', 'methods', 'branchName'));
    }

    public function store(
        Request $request,
        StockAdjustmentControllerMethodContainersInterface $stockAdjustmentControllerMethodContainersInterface,
        CodeGenerationService $codeGenerator
    ) {
        if (! auth()->user()->can('stock_adjustment_add')) {

            return response()->json('Access Denied.');
        }

        $this->validate($request, [
            'date' => 'required',
            'type' => 'required',
            'expense_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'expense_account_id.required' => __('Expense Ledger A/c is required.'),
            'account_id.required' => __('Debit A/c is required.'),
        ]);

        try {
            DB::beginTransaction();

            $storeMethodContainer = $stockAdjustmentControllerMethodContainersInterface->storeMethodContainer(
                request: $request,
                branchSettingService: $this->branchSettingService,
                stockAdjustmentService: $this->stockAdjustmentService,
                stockAdjustmentProductService: $this->stockAdjustmentProductService,
                dayBookService: $this->dayBookService,
                accountLedgerService: $this->accountLedgerService,
                productStockService: $this->productStockService,
                productLedgerService: $this->productLedgerService,
                accountingVoucherService: $this->accountingVoucherService,
                accountingVoucherDescriptionService: $this->accountingVoucherDescriptionService,
                accountingVoucherDescriptionReferenceService: $this->accountingVoucherDescriptionReferenceService,
                userActivityLogUtil: $this->userActivityLogUtil,
                codeGenerator: $codeGenerator
            );

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', __('Stock adjustment created successfully'));

        return response()->json(__('Stock adjustment created successfully'));
    }

    public function delete($id, StockAdjustmentControllerMethodContainersInterface $stockAdjustmentControllerMethodContainersInterface)
    {
        if (! auth()->user()->can('stock_adjustment_delete')) {

            return response()->json('Access Denied.');
        }

        try {
            DB::beginTransaction();

            $deleteMethodContainer = $stockAdjustmentControllerMethodContainersInterface->deleteMethodContainer(
                id: $id,
                stockAdjustmentService: $this->stockAdjustmentService,
                productStockService: $this->productStockService,
                userActivityLogUtil: $this->userActivityLogUtil,
            );

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Stock adjustment deleted successfully.'));
    }
}
