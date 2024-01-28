<?php

namespace App\Http\Controllers\Sales;

use App\Enums\SaleStatus;
use App\Http\Controllers\Controller;
use App\Interfaces\Sales\AddSaleControllerMethodContainersInterface;
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
use App\Services\Sales\SaleProductService;
use App\Services\Sales\SaleService;
use App\Services\Setups\BranchService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Setups\WarehouseService;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddSalesController extends Controller
{
    public function __construct(
        private SaleService $saleService,
        private SaleProductService $saleProductService,
        private PurchaseProductService $purchaseProductService,
        private PriceGroupService $priceGroupService,
        private ManagePriceGroupService $managePriceGroupService,
        private PaymentMethodService $paymentMethodService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
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

    public function index(Request $request, $customerAccountId = null, $saleScreen = null)
    {
        abort_if(!auth()->user()->can('view_add_sale'), 403);

        if ($request->ajax()) {

            $customerAccountId = $customerAccountId == 'null' ? null : $customerAccountId;
            return $this->saleService->salesListTable(request: $request, customerAccountId: $customerAccountId, saleScreen: $saleScreen);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.add_sale.index', compact('branches', 'customerAccounts'));
    }

    public function show($id, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        $showMethodContainer = $addSaleControllerMethodContainersInterface->showMethodContainer(
            id: $id,
            saleService: $this->saleService,
            saleProductService: $this->saleProductService,
        );

        extract($showMethodContainer);

        return view('sales.add_sale.ajax_views.show', compact('sale', 'customerCopySaleProducts'));
    }

    public function create(AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('create_add_sale'), 403);

        $createMethodContainer = $addSaleControllerMethodContainersInterface->createMethodContainer(
            branchService: $this->branchService,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            paymentMethodService: $this->paymentMethodService,
            warehouseService: $this->warehouseService,
            priceGroupService: $this->priceGroupService,
            managePriceGroupService: $this->managePriceGroupService,
        );

        extract($createMethodContainer);

        return view('sales.add_sale.create', compact('customerAccounts', 'methods', 'accounts', 'saleAccounts', 'taxAccounts', 'priceGroups', 'priceGroupProducts', 'warehouses', 'branchName'));
    }

    public function store(Request $request, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface, CodeGenerationService $codeGenerator)
    {
        abort_if(!auth()->user()->can('create_add_sale'), 403);

        $this->saleService->addSaleValidation(request: $request);

        try {
            DB::beginTransaction();

            $storeMethodContainer = $addSaleControllerMethodContainersInterface->storeMethodContainer(
                request: $request,
                saleService: $this->saleService,
                saleProductService: $this->saleProductService,
                dayBookService: $this->dayBookService,
                accountService: $this->accountService,
                accountLedgerService: $this->accountLedgerService,
                productStockService: $this->productStockService,
                productLedgerService: $this->productLedgerService,
                purchaseProductService: $this->purchaseProductService,
                accountingVoucherService: $this->accountingVoucherService,
                accountingVoucherDescriptionService: $this->accountingVoucherDescriptionService,
                accountingVoucherDescriptionReferenceService: $this->accountingVoucherDescriptionReferenceService,
                userActivityLogUtil: $this->userActivityLogUtil,
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

            return $this->saleService->printTemplateBySaleStatus(request: $request, sale: $sale, customerCopySaleProducts: $customerCopySaleProducts);
        } else {

            return response()->json(['saleFinalMsg' => __('Sale created successfully')]);
        }
    }

    public function edit($id, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('edit_add_sale'), 403);

        $editMethodContainer = $addSaleControllerMethodContainersInterface->editMethodContainer(
            id: $id,
            branchService: $this->branchService,
            saleService: $this->saleService,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            paymentMethodService: $this->paymentMethodService,
            warehouseService: $this->warehouseService,
            priceGroupService: $this->priceGroupService,
            managePriceGroupService: $this->managePriceGroupService,
        );

        extract($editMethodContainer);

        return view('sales.add_sale.edit', compact('sale', 'customerAccounts', 'methods', 'accounts', 'saleAccounts', 'taxAccounts', 'priceGroups', 'priceGroupProducts', 'warehouses', 'branchName'));
    }

    public function update($id, Request $request, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface, CodeGenerationService $codeGenerator)
    {
        abort_if(!auth()->user()->can('edit_add_sale'), 403);

        $this->saleService->addSaleValidation(request: $request);

        try {
            DB::beginTransaction();

            $updateMethodContainer = $addSaleControllerMethodContainersInterface->updateMethodContainer(
                id: $id,
                request: $request,
                saleService: $this->saleService,
                saleProductService: $this->saleProductService,
                dayBookService: $this->dayBookService,
                accountService: $this->accountService,
                accountLedgerService: $this->accountLedgerService,
                productStockService: $this->productStockService,
                productLedgerService: $this->productLedgerService,
                purchaseProductService: $this->purchaseProductService,
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

        return response()->json(__('Sale updated Successfully.'));
    }

    public function delete($id, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('delete_add_sale'), 403);

        try {
            DB::beginTransaction();

            $deleteMethodContainer = $addSaleControllerMethodContainersInterface->deleteMethodContainer(
                id: $id,
                saleService: $this->saleService,
                productStockService: $this->productStockService,
                purchaseProductService: $this->purchaseProductService,
                userActivityLogUtil: $this->userActivityLogUtil,
            );

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        $voucherName = SaleStatus::tryFrom($deleteMethodContainer->status)->name;
        $__voucherName = $voucherName == 'Final' ? __('Sale') : $voucherName;

        return response()->json(__("${__voucherName} deleted Successfully."));
    }

    public function searchByInvoiceId($keyWord)
    {
        $sales = DB::table('sales')
            ->where('sales.invoice_id', 'like', "%{$keyWord}%")
            ->where('sales.branch_id', auth()->user()->branch_id)
            ->where('sales.status', 1)
            ->select('sales.id as sale_id', 'sales.invoice_id', 'sales.customer_account_id')->limit(35)->get();

        if (count($sales) > 0) {

            return view('search_results_view.sale_invoice_search_result_list', compact('sales'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }
}
