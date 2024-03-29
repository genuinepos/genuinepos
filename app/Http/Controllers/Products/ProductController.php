<?php

namespace App\Http\Controllers\Products;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Products\UnitService;
use App\Services\Setups\BranchService;
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
use App\Http\Requests\Products\ProductStoreRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Services\Products\ProductAccessBranchService;

class ProductController extends Controller
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
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request, $isForCreatePage = 0)
    {
        abort_if(!auth()->user()->can('product_all'), 403);

        if ($request->ajax()) {

            return $this->productService->productListTable($request, $isForCreatePage);
        }

        $categories = $this->categoryService->categories()->where('parent_category_id', null)->get(['id', 'name']);
        $brands = $this->brandService->brands()->get(['id', 'name']);
        $units = $this->unitService->units()->get(['id', 'name', 'code_name']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', BooleanType::True->value)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return view('product.products.index', compact('categories', 'brands', 'units', 'taxAccounts', 'branches'));
    }

    public function show($id)
    {
        $productShowQueries = $this->productService->productShowQueries(id: $id);
        extract($productShowQueries);

        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return view('product.products.ajax_view.show', compact('product', 'ownBranchAndWarehouseStocks', 'globalWareHouseStocks', 'priceGroups'));
    }

    public function create(Request $request, $id = null)
    {
        abort_if(!auth()->user()->can('product_add'), 403);

        if ($request->ajax()) {

            return $this->productService->createProductListOfProducts();
        }

        $categories = $this->categoryService->categories()->where('parent_category_id', null)->get(['id', 'name']);
        $brands = $this->brandService->brands()->get(['id', 'name']);
        $units = $this->unitService->units()->get(['id', 'name', 'code_name']);
        $warranties = $this->warrantyService->warranties()->orderBy('id', 'desc')->get(['id', 'name']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', BooleanType::True->value)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();
        $bulkVariants = $this->bulkVariantService->bulkVariants(with: ['bulkVariantChild:id,bulk_variant_id,name'])->get();
        $lastProductSerialCode = $this->productService->getLastProductSerialCode();

        $product = null;
        if ($id) {

            $product = $this->productService->singleProduct(id: $id, with: ['variants', 'category', 'category.subcategories']);
        }

        return view('product.products.create', compact('units', 'categories', 'brands', 'warranties', 'taxAccounts', 'branches', 'bulkVariants', 'lastProductSerialCode', 'product'));
    }

    public function store(ProductStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $restrictions = $this->productService->restrictions($request);

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
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

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addProduct;
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('product_edit'), 403);

        $product = $this->productService->singleProduct(id: $id, with: ['productUnits', 'productUnits.baseUnit', 'variants', 'variants.variantUnits', 'variants.variantUnits.assignedUnit', 'variants.productLedgers', 'productAccessBranches']);

        $categories = $this->categoryService->categories()->where('parent_category_id', null)->get(['id', 'name']);
        $subCategories = $this->categoryService->categories()->where('parent_category_id', ($product->category_id ? $product->category_id : 0))->get(['id', 'name']);
        $brands = $this->brandService->brands()->get(['id', 'name']);
        $units = $this->unitService->units()->get(['id', 'name', 'code_name']);
        $warranties = $this->warrantyService->warranties()->orderBy('id', 'desc')->get(['id', 'name']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', BooleanType::True->value)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();
        $bulkVariants = $this->bulkVariantService->bulkVariants(with: ['bulkVariantChild:id,bulk_variant_id,name'])->get();
        $lastProductSerialCode = $this->productService->getLastProductSerialCode();

        return view('product.products.edit', compact('units', 'categories', 'subCategories', 'brands', 'warranties', 'taxAccounts', 'branches', 'bulkVariants', 'lastProductSerialCode', 'product'));
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $restrictions = $this->productService->restrictions($request);

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
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

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Product updated successfully.'));
    }

    public function formPart($type)
    {
        $type = $type;
        $generalSettings = config('generalSettings');
        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', BooleanType::True->value)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $bulkVariants = $this->bulkVariantService->bulkVariants(with: ['bulkVariantChild:id,bulk_variant_id,name'])->get();
        $units = $this->unitService->units()->get(['id', 'name', 'code_name']);
        $defaultUnitId = $generalSettings['product__default_unit_id'] ? $generalSettings['product__default_unit_id'] : null;
        $defaultUnit = $this->unitService->singleUnit(id: $defaultUnitId);
        $defaultUnitName = isset($defaultUnit) ? $defaultUnit->name : null;

        return view('product.products.ajax_view.form_part', compact('type', 'taxAccounts', 'bulkVariants', 'units', 'defaultUnitId', 'defaultUnitName'));
    }

    public function changeStatus($id)
    {
        $changeStatus = $this->productService->changeProductStatus(id: $id);

        return response()->json($changeStatus['msg']);
    }

    public function delete($id)
    {
        abort_if(!auth()->user()->can('product_delete'), 403);

        try {
            DB::beginTransaction();

            $deleteProduct = $this->productService->deleteProduct(id: $id);
            if (isset($deleteProduct['pass']) && $deleteProduct['pass'] == false) {

                return response()->json(['errorMsg' => $deleteProduct['msg']]);
            }

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::Product->value, dataObj: $deleteProduct);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Product deleted successfully.'));
    }

    public function getLastProductId()
    {
        return $this->productService->getLastProductSerialCode();
    }
}
