<?php

namespace App\Services\Products\MethodContainerServices;

use App\Enums\BooleanType;
use App\Services\Products\UnitService;
use App\Services\Branches\BranchService;
use App\Enums\ProductLedgerVoucherType;
use App\Services\Products\BrandService;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Products\ProductService;
use App\Services\Setups\WarehouseService;
use App\Services\Products\CategoryService;
use App\Services\Products\WarrantyService;
use App\Services\Products\PriceGroupService;
use App\Services\Products\BulkVariantService;
use App\Services\Products\OpeningStockService;
use App\Services\Products\ProductStockService;
use App\Services\Users\UserActivityLogService;
use App\Services\Products\ProductLedgerService;
use App\Services\Products\ProductVariantService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Products\ProductAccessBranchService;
use App\Interfaces\Products\QuickProductAddControllerMethodContainersInterface;

class QuickProductAddControllerMethodContainersService implements QuickProductAddControllerMethodContainersInterface
{
    public function __construct(
        private ProductService $productService,
        private UnitService $unitService,
        private CategoryService $categoryService,
        private BrandService $brandService,
        private BulkVariantService $bulkVariantService,
        private WarrantyService $warrantyService,
        private ProductVariantService $productVariantService,
        private ProductAccessBranchService $productAccessBranchService,
        private AccountService $accountService,
        private BranchService $branchService,
        private PriceGroupService $priceGroupService,
        private WarehouseService $warehouseService,
        private OpeningStockService $openingStockService,
        private ProductLedgerService $productLedgerService,
        private ProductStockService $productStockService,
        private PurchaseProductService $purchaseProductService,
        private UserActivityLogService $userActivityLogService
    ) {}

    public function createMethodContainer(): array
    {
        $data = [];
        $data['categories'] = $this->categoryService->categories()->where('parent_category_id', null)->get(['id', 'name']);
        $data['brands'] = $this->brandService->brands()->get(['id', 'name']);
        $data['units'] = $this->unitService->units()->get(['id', 'name', 'code_name']);
        $data['warranties'] = $this->warrantyService->warranties()->orderBy('id', 'desc')->get(['id', 'name']);

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', BooleanType::True->value)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $data['branches'] = $this->branchService->branches()->where('parent_branch_id', null)->get();

        $data['lastProductSerialCode'] = $this->productService->getLastProductSerialCode();

        $data['warehouses'] = $this->warehouseService->warehouses(with: ['openingStockProduct'])
            ->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', BooleanType::True->value)
            ->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        return $data;
    }

    public function storeMethodContainer(object $request): object
    {
        $addProduct = $this->productService->addProduct(request: $request);

        $this->productAccessBranchService->addProductAccessBranches(request: $request, productId: $addProduct->id);

        foreach ($request->branch_ids as $index => $branch_id) {

            $addOrEditOpeningStock = $this->openingStockService->addOrEditProductOpeningStock(request: $request, index: $index, productId: $addProduct->id);

            $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::OpeningStock->value, date: $addOrEditOpeningStock->date, productId: $addOrEditOpeningStock->product_id, transId: $addOrEditOpeningStock->id, rate: $addOrEditOpeningStock->unit_cost_inc_tax, quantityType: 'in', quantity: $addOrEditOpeningStock->quantity, subtotal: $addOrEditOpeningStock->subtotal, variantId: $addOrEditOpeningStock->variant_id, branchId: auth()->user()->branch_id, warehouseId: $addOrEditOpeningStock->warehouse_id);

            $this->productStockService->adjustMainProductAndVariantStock(productId: $addOrEditOpeningStock->product_id, variantId: $addOrEditOpeningStock->variant_id);

            $this->productStockService->adjustBranchAllStock(productId: $addOrEditOpeningStock->product_id, variantId: $addOrEditOpeningStock->variant_id, branchId: $addOrEditOpeningStock->branch_id);

            if (isset($addOrEditOpeningStock->warehouse_id)) {

                $this->productStockService->adjustWarehouseStock(productId: $addOrEditOpeningStock->product_id, variantId: $addOrEditOpeningStock->variant_id, warehouseId: $addOrEditOpeningStock->warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock($addOrEditOpeningStock->product_id, $addOrEditOpeningStock->variant_id, $addOrEditOpeningStock->branch_id);
            }

            $this->purchaseProductService->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(transColName: 'opening_stock_id', transId: $addOrEditOpeningStock->id, branchId: auth()->user()->branch_id, productId: $addOrEditOpeningStock->product_id, variantId: $addOrEditOpeningStock->variant_id, quantity: $addOrEditOpeningStock->quantity, unitCostIncTax: $addOrEditOpeningStock->unit_cost_inc_tax, sellingPrice: 0, subTotal: $addOrEditOpeningStock->subtotal, createdAt: $addOrEditOpeningStock->date_ts);
        }

        $product = $this->productService->singleProduct(id: $addProduct->id, with: [
            'tax',
            'unit:id,name,code_name',
            'unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'productBranchStock',
        ]);

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Product->value, dataObj: $addProduct);

        return $product;
    }
}
