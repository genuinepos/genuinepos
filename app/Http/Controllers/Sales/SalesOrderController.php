<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Interfaces\Sales\SalesOrderControllerMethodContainersInterface;
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
use App\Services\Sales\SaleProductService;
use App\Services\Sales\SaleService;
use App\Services\Sales\SalesOrderProductService;
use App\Services\Sales\SalesOrderService;
use App\Services\Setups\BranchService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{
    public function __construct(
        private SaleService $saleService,
        private SalesOrderService $salesOrderService,
        private SaleProductService $saleProductService,
        private SalesOrderProductService $salesOrderProductService,
        private DayBookService $dayBookService,
        private AccountService $accountService,
        private AccountLedgerService $accountLedgerService,
        private AccountFilterService $accountFilterService,
        private PaymentMethodService $paymentMethodService,
        private BranchService $branchService,
        private BranchSettingService $branchSettingService,
        private PriceGroupService $priceGroupService,
        private ManagePriceGroupService $managePriceGroupService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function index(Request $request, $customerAccountId = null)
    {
        if (! auth()->user()->can('view_add_sale')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->salesOrderService->salesOrderListTable(request: $request, customerAccountId: $customerAccountId);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.add_sale.orders.index', compact('branches', 'customerAccounts'));
    }

    public function show($id, SalesOrderControllerMethodContainersInterface $salesOrderControllerMethodContainersInterface)
    {
        $showMethodContainer = $salesOrderControllerMethodContainersInterface->showMethodContainer(
            id: $id,
            salesOrderService: $this->salesOrderService,
            saleProductService: $this->saleProductService
        );

        extract($showMethodContainer);

        return view('sales.add_sale.orders.ajax_views.show', compact('order', 'customerCopySaleProducts'));
    }

    public function edit($id, SalesOrderControllerMethodContainersInterface $salesOrderControllerMethodContainersInterface)
    {
        $editMethodContainer = $salesOrderControllerMethodContainersInterface->editMethodContainer(
            id: $id,
            salesOrderService: $this->salesOrderService,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            paymentMethodService: $this->paymentMethodService,
            priceGroupService: $this->priceGroupService,
            managePriceGroupService: $this->managePriceGroupService
        );

        extract($editMethodContainer);

        return view('sales.add_sale.orders.edit', compact('order', 'customerAccounts', 'methods', 'accounts', 'saleAccounts', 'taxAccounts', 'priceGroups', 'priceGroupProducts'));
    }

    public function update($id, Request $request, SalesOrderControllerMethodContainersInterface $salesOrderControllerMethodContainersInterface, CodeGenerationService $codeGenerator)
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

            $updateMethodContainer = $salesOrderControllerMethodContainersInterface->updateMethodContainer(
                id: $id,
                request: $request,
                branchSettingService: $this->branchSettingService,
                saleService: $this->saleService,
                salesOrderService: $this->salesOrderService,
                salesOrderProductService: $this->salesOrderProductService,
                dayBookService: $this->dayBookService,
                accountService: $this->accountService,
                accountLedgerService: $this->accountLedgerService,
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

        return response()->json(__('Sales Order updated Successfully.'));
    }
}
