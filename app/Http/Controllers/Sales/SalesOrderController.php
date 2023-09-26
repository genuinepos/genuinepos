<?php

namespace App\Http\Controllers\Sales;

use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Sales\SalesOrderService;
use App\Services\Sales\SaleProductService;

class SalesOrderController extends Controller
{
    public function __construct(
        private SalesOrderService $salesOrderService,
        private SaleProductService $saleProductService,
        private AccountService $accountService,
        private BranchService $branchService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_add_sale')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->salesOrderService->salesOrderListTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.add_sale.orders.index', compact('branches', 'customerAccounts'));
    }

    public function show($id)
    {
        $order = $this->salesOrderService->singleSalesOrder(id: $id, with: [
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

            'references:id,voucher_description_id,sale_id,amount',
            'references.voucherDescription:id,accounting_voucher_id',
            'references.voucherDescription.accountingVoucher:id,voucher_no,date,voucher_type',
            'references.voucherDescription.accountingVoucher.voucherDescriptions:id,accounting_voucher_id,account_id,payment_method_id',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.paymentMethod:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account:id,name,account_number,account_group_id,bank_id,bank_branch',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.bank:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.group:id,sub_sub_group_number',
        ]);

        $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $order->id);

        return view('sales.add_sale.orders.ajax_views.show', compact('order', 'customerCopySaleProducts'));
    }
}
