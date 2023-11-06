<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Interfaces\Sales\DraftControllerMethodContainersInterface;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\CodeGenerationService;
use App\Services\Products\ManagePriceGroupService;
use App\Services\Products\PriceGroupService;
use App\Services\Products\ProductLedgerService;
use App\Services\Products\ProductStockService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Sales\DraftProductService;
use App\Services\Sales\DraftService;
use App\Services\Sales\SaleProductService;
use App\Services\Sales\SaleService;
use App\Services\Setups\BranchService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Setups\WarehouseService;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DraftController extends Controller
{
    public function __construct(
        private DraftService $draftService,
        private SaleService $saleService,
        private DayBookService $dayBookService,
        private BranchSettingService $branchSettingService,
        private DraftProductService $draftProductService,
        private SaleProductService $saleProductService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private AccountLedgerService $accountLedgerService,
        private PaymentMethodService $paymentMethodService,
        private BranchService $branchService,
        private ProductStockService $productStockService,
        private ProductLedgerService $productLedgerService,
        private PriceGroupService $priceGroupService,
        private PurchaseProductService $purchaseProductService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private WarehouseService $warehouseService,
        private ManagePriceGroupService $managePriceGroupService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('sale_draft')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->draftService->draftListTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.add_sale.drafts.index', compact('branches', 'customerAccounts'));
    }

    public function show($id, DraftControllerMethodContainersInterface $draftControllerMethodContainersInterface)
    {
        if (! auth()->user()->can('sale_draft')) {

            abort(403, 'Access Forbidden.');
        }

        $showMethodContainer = $draftControllerMethodContainersInterface->showMethodContainer(
            id: $id,
            quotationService: $this->draftService,
            saleProductService: $this->saleProductService
        );

        extract($showMethodContainer);

        return view('sales.add_sale.drafts.ajax_views.show', compact('draft', 'customerCopySaleProducts'));
    }

    public function edit($id, DraftControllerMethodContainersInterface $draftControllerMethodContainersInterface)
    {

        $editMethodContainer = $draftControllerMethodContainersInterface->editMethodContainer(
            id: $id,
            draftService: $this->draftService,
            branchService: $this->branchService,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            paymentMethodService: $this->paymentMethodService,
            priceGroupService: $this->priceGroupService,
            warehouseService: $this->warehouseService,
            managePriceGroupService: $this->managePriceGroupService
        );

        extract($editMethodContainer);

        return view('sales.add_sale.drafts.edit', compact('draft', 'customerAccounts', 'accounts', 'methods', 'warehouses', 'saleAccounts', 'taxAccounts', 'priceGroups', 'priceGroupProducts', 'branchName'));
    }

    public function update($id, Request $request, DraftControllerMethodContainersInterface $draftControllerMethodContainersInterface, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'status' => 'required',
            'date' => 'required|date',
        ]);

        try {

            DB::beginTransaction();

            $updateMethodContainer = $draftControllerMethodContainersInterface->updateMethodContainer(
                id: $id,
                request: $request,
                saleService: $this->saleService,
                draftService: $this->draftService,
                draftProductService: $this->draftProductService,
                branchSettingService: $this->branchSettingService,
                dayBookService: $this->dayBookService,
                accountLedgerService: $this->accountLedgerService,
                productStockService: $this->productStockService,
                productLedgerService: $this->productLedgerService,
                purchaseProductService: $this->purchaseProductService,
                accountService: $this->accountService,
                accountingVoucherService: $this->accountingVoucherService,
                accountingVoucherDescriptionService: $this->accountingVoucherDescriptionService,
                accountingVoucherDescriptionReferenceService: $this->accountingVoucherDescriptionReferenceService,
                userActivityLogUtil: $this->userActivityLogUtil,
                codeGenerator: $codeGenerator,
            );

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Draft updated Successfully.'));
    }
}
