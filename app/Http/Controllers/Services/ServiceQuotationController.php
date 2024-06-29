<?php

namespace App\Http\Controllers\Services;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Enums\SaleScreenType;
use Illuminate\Support\Facades\DB;
use App\Services\Sales\SaleService;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Enums\UserActivityLogActionType;
use App\Services\Sales\QuotationService;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Sales\SaleProductService;
use App\Services\Products\PriceGroupService;
use App\Services\Products\ProductStockService;
use App\Services\Users\UserActivityLogService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Sales\QuotationProductService;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Services\Products\ManagePriceGroupService;
use App\Services\Services\ServiceQuotationService;
use App\Http\Requests\Services\ServiceQuotationEditRequest;
use App\Http\Requests\Services\ServiceQuotationIndexRequest;
use App\Http\Requests\Services\ServiceQuotationStoreRequest;
use App\Http\Requests\Services\ServiceQuotationCreateRequest;
use App\Http\Requests\Services\ServiceQuotationDeleteRequest;
use App\Http\Requests\Services\ServiceQuotationUpdateRequest;

class ServiceQuotationController extends Controller
{
    public function __construct(
        private ServiceQuotationService $serviceQuotationService,
        private QuotationService $quotationService,
        private SaleService $saleService,
        private SaleProductService $saleProductService,
        private QuotationProductService $quotationProductService,
        private PriceGroupService $priceGroupService,
        private ManagePriceGroupService $managePriceGroupService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
        private ProductStockService $productStockService,
        private UserActivityLogService $userActivityLogService,
    ) {
    }

    public function index(ServiceQuotationIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->serviceQuotationService->serviceQuotationListTable(request: $request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('services.quotations.index', compact('branches', 'customerAccounts'));
    }

    public function create(ServiceQuotationCreateRequest $request)
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branchName = $this->branchService->branchName();

        $saleAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $priceGroupProducts = $this->managePriceGroupService->priceGroupProducts();

        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return view('services.quotations.create', compact('customerAccounts', 'saleAccounts', 'taxAccounts', 'priceGroups', 'priceGroupProducts', 'branchName'));
    }

    public function store(ServiceQuotationStoreRequest $request, CodeGenerationServiceInterface $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $generalSettings = config('generalSettings');

            $quotationPrefix = $generalSettings['prefix__quotation_prefix'] ? $generalSettings['prefix__quotation_prefix'] : 'Q';

            $restrictions = $this->saleService->restrictions(request: $request, accountService: $this->accountService);
            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $addSale = $this->saleService->addSale(request: $request, saleScreenType: SaleScreenType::ServiceQuotation->value, codeGenerator: $codeGenerator, invoicePrefix: null, quotationPrefix: $quotationPrefix, salesOrderPrefix: null);

            foreach ($request->product_ids as $index => $productId) {

                $addSaleProduct = $this->saleProductService->addSaleProduct(request: $request, sale: $addSale, index: $index);
            }

            $sale = $this->saleService->singleSale(
                id: $addSale->id,
                with: [
                    'branch',
                    'branch.parentBranch',
                    'customer',
                    'saleProducts',
                    'saleProducts.product',
                ]
            );

            $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $sale->id);

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Quotation->value, dataObj: $sale);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            $printPageSize = $request->print_page_size;
            $quotation = $sale;
            return view('sales.print_templates.quotation_print', compact('quotation', 'customerCopySaleProducts', 'printPageSize'));
        } else {

            return response()->json(['successMsg' => __('Service Quotation created successfully')]);
        }
    }

    public function edit($id, ServiceQuotationEditRequest $request)
    {
        $quotation = $this->quotationService->singleQuotation(id: $id, with: [
            'customer',
            'customer.group',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.unit:id,name,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.unit.baseUnit:id,name,code_name,base_unit_id',
        ]);

        $ownBranchIdOrParentBranchId = $quotation?->branch?->parent_branch_id ? $quotation?->branch?->parent_branch_id : $quotation->branch_id;

        $saleAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', $quotation->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);
        $priceGroupProducts = $this->managePriceGroupService->priceGroupProducts();

        return view('services.quotations.edit', compact('quotation', 'customerAccounts', 'saleAccounts', 'taxAccounts', 'priceGroups', 'priceGroupProducts'));
    }

    public function update($id, ServiceQuotationUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $quotation = $this->quotationService->singleQuotation(id: $id, with: ['saleProducts', 'references']);

            $restrictions = $this->saleService->restrictions(request: $request, accountService: $this->accountService, checkCustomerChangeRestriction: true, saleId: $id);

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $updateQuotation = $this->quotationService->updateQuotation(request: $request, updateQuotation: $quotation);

            $updateQuotationProducts = $this->quotationProductService->updateQuotationProducts(request: $request, quotation: $updateQuotation);

            $quotation = $this->quotationService->singleQuotation(id: $id, with: ['saleProducts']);

            $deletedUnusedQuotationProducts = $quotation->saleProducts()->where('is_delete_in_update', BooleanType::True->value)->get();

            if (count($deletedUnusedQuotationProducts) > 0) {

                foreach ($deletedUnusedQuotationProducts as $deletedUnusedQuotationProduct) {

                    $deletedUnusedQuotationProduct->delete();
                }
            }

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::Quotation->value, dataObj: $quotation);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Service Quotation updated successfully'));
    }

    public function delete($id, ServiceQuotationDeleteRequest $request)
    {
        try {
            DB::beginTransaction();

            $this->saleService->deleteSale(id: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Service Quotation deleted successfully'));
    }
}
