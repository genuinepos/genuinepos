<?php

namespace App\Services\Products\MethodContainerServices;

use App\Enums\BooleanType;
use App\Services\Products\UnitService;
use App\Services\Branches\BranchService;
use App\Services\Products\BrandService;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Products\ProductService;
use App\Services\Products\CategoryService;
use App\Services\Products\WarrantyService;
use App\Services\Products\PriceGroupService;
use App\Services\Products\BulkVariantService;
use App\Services\Products\ProductUnitService;
use App\Services\Users\UserActivityLogService;
use App\Services\Products\ProductVariantService;
use App\Services\Products\ProductAccessBranchService;
use App\Interfaces\Products\ProductControllerMethodContainersInterface;

class ProductControllerMethodContainersService implements ProductControllerMethodContainersInterface
{
    public function __construct(
        private ProductService $productService,
        private ProductUnitService $productUnitService,
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
        private UserActivityLogService $userActivityLogService,
    ) {}

    public function indexMethodContainer(object $request, int $isForCreatePage = 0): array|object
    {
        $data = [];
        if ($request->ajax()) {

            return $this->productService->productListTable($request, $isForCreatePage);
        }

        $data['categories'] = $this->categoryService->categories()->where('parent_category_id', null)->get(['id', 'name']);
        $data['brands'] = $this->brandService->brands()->get(['id', 'name']);
        $data['units'] = $this->unitService->units()->get(['id', 'name', 'code_name']);

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', BooleanType::True->value)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $data['branches'] = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return $data;
    }

    public function showMethodContainer(int $id): array
    {
        $data = [];
        $productShowQueries = $this->productService->productShowQueries(id: $id);
        extract($productShowQueries);

        $data['product'] = $product;
        $data['ownAndOtherBranchAndWarehouseStocks'] = $ownAndOtherBranchAndWarehouseStocks;
        // $data['otherBranchAndWarehouseStocks'] = $otherBranchAndWarehouseStocks;
        // $data['globalWareHouseStocks'] = $globalWareHouseStocks;

        $data['priceGroups'] = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return $data;
    }

    public function createMethodContainer(object $request, ?int $id): array|object
    {
        $data = [];
        if ($request->ajax()) {

            return $this->productService->createProductListOfProducts();
        }

        $data['categories'] = $this->categoryService->categories()->where('parent_category_id', null)->get(['id', 'name']);
        $data['brands'] = $this->brandService->brands()->get(['id', 'name']);
        $data['units'] = $this->unitService->units()->get(['id', 'name', 'code_name']);
        $data['warranties'] = $this->warrantyService->warranties()->orderBy('id', 'desc')->get(['id', 'name']);

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', BooleanType::True->value)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $data['branches'] = $this->branchService->branches()->where('parent_branch_id', null)->get();
        $data['bulkVariants'] = $this->bulkVariantService->bulkVariants(with: ['bulkVariantChild:id,bulk_variant_id,name'])->get();
        $data['lastProductSerialCode'] = $this->productService->getLastProductSerialCode();

        $product = null;
        if (isset($id)) {

            $product = $this->productService->singleProduct(id: $id, with: ['variants', 'category', 'category.subcategories']);
        }

        $data['product'] = $product;

        return $data;
    }

    public function storeMethodContainer(object $request): array|object
    {
        $restrictions = $this->productService->restrictions($request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $addProduct = $this->productService->addProduct($request);

        if ($request->has_multiple_unit == BooleanType::True->value && isset($request->base_unit_ids)) {

            $this->productUnitService->addProductUnits(request: $request, productId: $addProduct->id);
        }

        if ($request->type == 1 && $request->is_variant == BooleanType::True->value) {

            foreach ($request->variant_combinations as $index => $variantCombination) {

                $addVariant = $this->productVariantService->addProductVariant(request: $request, productId: $addProduct->id, index: $index);

                if ($request->has_multiple_unit == BooleanType::True->value && isset($request->variant_base_unit_ids)) {

                    $this->productUnitService->addProductVariantUnits(request: $request, productId: $addProduct->id, variantId: $addVariant->id, variantIndexNumber: $request->index_numbers[$index]);
                }
            }
        }

        $this->productAccessBranchService->addProductAccessBranches(request: $request, productId: $addProduct->id);

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Product->value, dataObj: $addProduct);

        return $addProduct;
    }

    public function editMethodContainer(int $id): array
    {
        $data = [];
        $product = $this->productService->singleProduct(id: $id, with: ['productUnits', 'productUnits.baseUnit', 'variants', 'variants.variantUnits', 'variants.variantUnits.assignedUnit', 'variants.productLedgers', 'productAccessBranches']);

        $data['categories'] = $this->categoryService->categories()->where('parent_category_id', null)->get(['id', 'name']);
        $data['subCategories'] = $this->categoryService->categories()->where('parent_category_id', ($product->category_id ? $product->category_id : 0))->get(['id', 'name']);
        $data['brands'] = $this->brandService->brands()->get(['id', 'name']);
        $data['units'] = $this->unitService->units()->get(['id', 'name', 'code_name']);
        $data['warranties'] = $this->warrantyService->warranties()->orderBy('id', 'desc')->get(['id', 'name']);

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', BooleanType::True->value)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $data['branches'] = $this->branchService->branches()->where('parent_branch_id', null)->get();
        $data['bulkVariants'] = $this->bulkVariantService->bulkVariants(with: ['bulkVariantChild:id,bulk_variant_id,name'])->get();
        $data['lastProductSerialCode'] = $this->productService->getLastProductSerialCode();

        $data['product'] = $product;

        return $data;
    }

    public function updateMethodContainer(int $id, object $request): ?array
    {
        $restrictions = $this->productService->restrictions($request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $updateProduct = $this->productService->updateProduct(request: $request, productId: $id);

        if ($updateProduct->has_multiple_unit == BooleanType::True->value) {

            $this->productUnitService->updateProductUnits(request: $request, product: $updateProduct);
        }

        if ($request->type == 1 && $request->is_variant == BooleanType::True->value) {

            foreach ($request->variant_combinations as $index => $variantCombination) {

                $updateProductVariant = $this->productVariantService->updateProductVariant(request: $request, productId: $updateProduct->id, index: $index);

                if ($request->has_multiple_unit == BooleanType::True->value && isset($request->variant_base_unit_ids)) {

                    $this->productUnitService->updateProductVariantUnits(request: $request, productId: $updateProduct->id, variantId: $updateProductVariant->id, variantIndexNumber: $request->index_numbers[$index]);
                }
            }
        }

        $this->productVariantService->deleteUnusedProductVariants(productId: $updateProduct->id);
        $this->productUnitService->deleteUnusedProductAndVariantUnits(productId: $updateProduct->id);

        $this->productAccessBranchService->updateProductAccessBranches(request: $request, product: $updateProduct);

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::Product->value, dataObj: $updateProduct);

        return null;
    }

    public function formPartMethodContainer(int $type): array
    {
        $data = [];
        $data['type'] = $type;
        $generalSettings = config('generalSettings');

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', BooleanType::True->value)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $data['bulkVariants'] = $this->bulkVariantService->bulkVariants(with: ['bulkVariantChild:id,bulk_variant_id,name'])->get();
        $data['units'] = $this->unitService->units()->get(['id', 'name', 'code_name']);
        $defaultUnitId = $generalSettings['product__default_unit_id'] ? $generalSettings['product__default_unit_id'] : null;
        $defaultUnit = $this->unitService->singleUnit(id: $defaultUnitId);
        $data['defaultUnitName'] = isset($defaultUnit) ? $defaultUnit->name : null;
        $data['defaultUnit'] = $defaultUnit;
        $data['defaultUnitId'] = $defaultUnitId;
        return $data;
    }

    public function changeStatusMethodContainer(int $id): array
    {
        return $this->productService->changeProductStatus(id: $id);
    }

    public function deleteMethodContainer(int $id): ?array
    {
        $deleteProduct = $this->productService->deleteProduct(id: $id);
        if (isset($deleteProduct['pass']) && $deleteProduct['pass'] == false) {

            return ['pass' => false, 'msg' => $deleteProduct['msg']];
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::Product->value, dataObj: $deleteProduct);

        return null;
    }

    public function getLastProductIdMethodContainer(): string
    {
        return $this->productService->getLastProductSerialCode();
    }
}
