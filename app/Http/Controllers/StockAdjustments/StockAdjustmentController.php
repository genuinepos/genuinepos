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
        abort_if(!auth()->user()->can('stock_adjustment_all'), 403);

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

    public function print($id, Request $request, StockAdjustmentControllerMethodContainersInterface $stockAdjustmentControllerMethodContainersInterface)
    {
        $printMethodContainer = $stockAdjustmentControllerMethodContainersInterface->printMethodContainer(
            id: $id,
            request: $request,
            stockAdjustmentService: $this->stockAdjustmentService,
        );

        extract($printMethodContainer);

        return view('stock_adjustments.print_templates.print_stock_adjustment', compact('adjustment', 'printPageSize'));
    }

    public function create(StockAdjustmentControllerMethodContainersInterface $stockAdjustmentControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('stock_adjustment_add'), 403);

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
        abort_if(!auth()->user()->can('stock_adjustment_add'), 403);

        $this->stockAdjustmentService->stockAdjustmentValidation(request: $request);

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
        abort_if(!auth()->user()->can('stock_adjustment_delete'), 403);

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
