<?php

namespace App\Http\Controllers\Sales;

use App\Enums\SaleStatus;
use Illuminate\Http\Request;
use App\Enums\DayBookVoucherType;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Services\Sales\SaleService;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\CodeGenerationService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\WarehouseService;
use App\Services\Sales\SaleProductService;
use App\Services\Products\PriceGroupService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Products\ProductStockService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Interfaces\Sales\AddSaleControllerMethodContainersInterface;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class AddSalesController extends Controller
{
    public function __construct(
        private SaleService $saleService,
        private SaleProductService $saleProductService,
        private PurchaseProductService $purchaseProductService,
        private PriceGroupService $priceGroupService,
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
        if (!auth()->user()->can('view_add_sale')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->saleService->addSalesListTable($request);
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

    public function printChallan($id)
    {
        $sale = $this->saleService->singleSale(id: $id, with: [
            'customer:id,name,phone,address',
            'createdBy:id,prefix,name,last_name',
        ]);

        $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $sale->id);

        return view('sales.add_sale.ajax_views.print_challan', compact('sale', 'customerCopySaleProducts'));
    }

    public function create(AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        if (!auth()->user()->can('create_add_sale')) {

            abort(403, 'Access Forbidden.');
        }

        $createMethodContainer = $addSaleControllerMethodContainersInterface->createMethodContainer(
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            paymentMethodService: $this->paymentMethodService,
            warehouseService: $this->warehouseService,
            priceGroupService: $this->priceGroupService,
        );

        extract($createMethodContainer);

        return view('sales.add_sale.create', compact('customerAccounts', 'methods', 'accounts', 'saleAccounts', 'taxAccounts', 'priceGroups', 'warehouses', 'branchName'));
    }

    public function store(Request $request, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'status' => 'required',
            'date' => 'required|date',
            'sale_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'sale_account_id.required' => 'Sales A/c is required',
            'account_id.required' => 'Debit A/c is required',
        ]);

        try {

            DB::beginTransaction();

            $storeMethodContainer = $addSaleControllerMethodContainersInterface->storeMethodContainer(
                request: $request,
                branchSettingService: $this->branchSettingService,
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

            if ($request->status == SaleStatus::Final->value) {

                $changeAmount = 0;
                $receivedAmount = $request->received_amount;
                return view('sales.save_and_print_template.sale_print', compact('sale', 'receivedAmount', 'changeAmount', 'customerCopySaleProducts'));
            } elseif ($request->status == SaleStatus::Draft->value) {

                $draft = $sale;
                return view('sales.save_and_print_template.draft_print', compact('draft', 'customerCopySaleProducts'));
            } elseif ($request->status == SaleStatus::Quotation->value) {

                $quotation = $sale;
                return view('sales.save_and_print_template.quotation_print', compact('quotation', 'customerCopySaleProducts'));
            } elseif ($request->status == SaleStatus::Order->value) {

                $order = $sale;
                $receivedAmount = $request->received_amount;
                return view('sales.save_and_print_template.order_print', compact('order', 'receivedAmount', 'customerCopySaleProducts'));
            }
        } else {

            return response()->json(['saleFinalMsg' => __("Sale created successfully")]);
        }
    }

    public function edit($id, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        if (!auth()->user()->can('edit_add_sale')) {

            abort(403, 'Access Forbidden.');
        }

        $data = $addSaleControllerMethodContainersInterface->editMethodContainer(
            id: $id,
            saleService: $this->saleService,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            paymentMethodService: $this->paymentMethodService,
            warehouseService: $this->warehouseService,
            priceGroupService: $this->priceGroupService,
        );

        extract($data);

        return view('sales.add_sale.edit', compact('sale', 'customerAccounts', 'methods', 'accounts', 'saleAccounts', 'taxAccounts', 'priceGroups', 'warehouses', 'branchName'));
    }

    public function update($id, Request $request, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'status' => 'required',
            'date' => 'required|date',
            'sale_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'sale_account_id.required' => 'Sales A/c is required',
            'account_id.required' => 'Debit A/c is required',
        ]);

        try {

            DB::beginTransaction();

            $updateMethodContainer = $addSaleControllerMethodContainersInterface->updateMethodContainer(
                id: $id,
                request: $request,
                branchSettingService: $this->branchSettingService,
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

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Sale updated Successfully."));
    }

    public function delete($id, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
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
        $__voucherName = $voucherName == 'Final' ? 'Sale' : $voucherName;

        return response()->json(__("${$__voucherName} deleted Successfully."));
    }

    function searchByInvoiceId($keyWord)
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
