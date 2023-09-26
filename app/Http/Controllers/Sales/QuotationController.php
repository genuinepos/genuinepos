<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Sales\QuotationService;
use App\Services\Accounts\AccountService;
use App\Services\CodeGenerationService;
use App\Services\Sales\SaleProductService;
use App\Services\Setups\BranchSettingService;

class QuotationController extends Controller
{
    public function __construct(
        private QuotationService $quotationService,
        private SaleProductService $saleProductService,
        private AccountService $accountService,
        private BranchService $branchService,
        private BranchSettingService $branchSettingService,
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

    public function show($id)
    {
        if (!auth()->user()->can('sale_quotation')) {

            abort(403, 'Access Forbidden.');
        }

        $quotation = $this->quotationService->singleQuotation(id: $id, with: [
            'customer:id,name,phone,address',
            'createdBy:id,prefix,name,last_name',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.branch:id,name,branch_code,area_name,parent_branch_id',
            'saleProducts.branch.parentBranch:id,name,branch_code,area_name',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.unit.baseUnit:id,base_unit_id,code_name',
        ]);

        $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $quotation->id);

        return view('sales.add_sale.quotations.ajax_views.show', compact('quotation', 'customerCopySaleProducts'));
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
