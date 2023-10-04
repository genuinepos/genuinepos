<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Services\Sales\SaleService;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\CodeGenerationService;
use App\Services\Sales\QuotationService;
use App\Services\Accounts\AccountService;
use App\Services\Sales\SalesOrderService;
use App\Services\Sales\SaleProductService;
use App\Services\Products\PriceGroupService;
use App\Services\Setups\BranchSettingService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Sales\QuotationProductService;
use App\Interfaces\Sales\QuotationControllerMethodContainersInterface;

class QuotationController extends Controller
{
    public function __construct(
        private QuotationService $quotationService,
        private QuotationProductService $quotationProductService,
        private SaleService $saleService,
        private SalesOrderService $salesOrderService,
        private SaleProductService $saleProductService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
        private BranchSettingService $branchSettingService,
        private PriceGroupService $priceGroupService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('sale_quotation')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->quotationService->quotationListTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.add_sale.quotations.index', compact('branches', 'customerAccounts'));
    }

    public function show($id, QuotationControllerMethodContainersInterface $quotationControllerMethodContainersInterface)
    {
        if (!auth()->user()->can('sale_quotation')) {

            abort(403, 'Access Forbidden.');
        }

        $showMethodContainer = $quotationControllerMethodContainersInterface->showMethodContainer(
            id: $id,
            quotationService: $this->quotationService,
            saleProductService: $this->saleProductService
        );

        extract($showMethodContainer);

        return view('sales.add_sale.quotations.ajax_views.show', compact('quotation', 'customerCopySaleProducts'));
    }

    function edit($id, QuotationControllerMethodContainersInterface $quotationControllerMethodContainersInterface)
    {
        $editMethodContainer = $quotationControllerMethodContainersInterface->editMethodContainer(
            id: $id,
            quotationService: $this->quotationService,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            priceGroupService: $this->priceGroupService
        );

        extract($editMethodContainer);

        return view('sales.add_sale.quotations.edit', compact('quotation', 'customerAccounts', 'accounts', 'saleAccounts', 'taxAccounts', 'priceGroups'));
    }

    function update($id, Request $request, QuotationControllerMethodContainersInterface $quotationControllerMethodContainersInterface, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'status' => 'required',
            'date' => 'required|date',
        ]);

        try {

            DB::beginTransaction();

            $updateMethodContainer = $quotationControllerMethodContainersInterface->updateMethodContainer(
                id: $id,
                request: $request,
                branchSettingService: $this->branchSettingService,
                saleService: $this->saleService,
                quotationService: $this->quotationService,
                salesOrderService: $this->salesOrderService,
                quotationProductService: $this->quotationProductService,
                accountService: $this->accountService,
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

        return response()->json(__("Quotation updated Successfully."));
    }

    public function editStatus($id)
    {
        $quotation = $this->quotationService->singleQuotation(id: $id);

        return view('sales.add_sale.quotations.ajax_views.change_quotation_status', compact('quotation'));
    }

    public function updateStatus($id, Request $request, CodeGenerationService $codeGenerator)
    {
        $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
        $salesOrderPrefix = isset($branchSetting) && $branchSetting?->sales_order_prefix ? $branchSetting?->sales_order_prefix : 'OR';

        $updateQuotationStatus = $this->quotationService->updateQuotationStatus(request: $request, id: $id, codeGenerator: $codeGenerator, salesOrderPrefix: $salesOrderPrefix);

        if ($updateQuotationStatus['pass'] == false) {

            return response()->json(['errorMsg' => $updateQuotationStatus['msg']]);
        }

        return response()->json('Quotation status is updated successfully');
    }
}
