<?php

namespace App\Http\Controllers\GeneralSearch;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\GeneralSearch\GeneralProductSearchService;
use App\Services\Products\ProductService;

class GeneralProductSearchController extends Controller
{
    public function __construct(
        private GeneralProductSearchService $generalProductSearchService,
        private ProductService $productService
    ) {
    }

    public function commonSearch($keyWord, $isShowNotForSaleItem = 1, $priceGroupId = null, $branchId = null)
    {
        $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;
        $__ownBranchIdOrParentBranchId = $branchId ? $branchId : $ownBranchIdOrParentBranchId;

        $keyWord = (string) $keyWord;
        $__keyWord = str_replace('~', '/', $keyWord);
        $__priceGroupId = ($priceGroupId && $priceGroupId != 'no_id') ? $priceGroupId : null;

        $product = '';

        $query = $this->productService->productByAnyCondition(with: [
            'variants',
            'variants.updateVariantCost',
            'tax:id,tax_percent',
            'unit:id,name,code_name',
            'unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'updateProductCost',
        ]);

        $query->leftJoin('product_access_branches', 'products.id', 'product_access_branches.product_id');

        $query->where('products.product_code', $__keyWord);
        $query->where('product_access_branches.branch_id', $__ownBranchIdOrParentBranchId);

        $query->select([
            'products.id',
            'products.name',
            'products.type',
            'products.product_code',
            'products.product_price',
            'products.profit',
            'products.product_cost',
            'products.product_cost_with_tax',
            'products.thumbnail_photo',
            'products.category_id',
            'products.brand_id',
            'products.unit_id',
            'products.tax_ac_id',
            'products.tax_type',
            'products.quantity',
            'products.is_show_emi_on_pos',
            'products.is_manage_stock',
            'products.is_for_sale',
        ])->first();

        return $this->generalProductSearchService->getProductByKeyword(product: $product, keyWord: $__keyWord, priceGroupId: $__priceGroupId, isShowNotForSaleItem: $isShowNotForSaleItem);
    }

    public function checkProductDiscount($productId, $priceGroupId)
    {
        return $this->generalProductSearchService->getProductDiscountById($productId, $priceGroupId);
    }

    public function checkProductDiscountWithStock($productId, $variantId, $priceGroupId)
    {
        return $this->generalProductSearchService->getProductDiscountByIdWithAvailableStock($productId, $variantId, $priceGroupId);
    }

    public function singleProductStock($productId, $warehouseId = null, $branchId = null)
    {
        $__branchId = $branchId ? $branchId : auth()->user()->branch_id;

        if ($warehouseId) {

            return $this->generalProductSearchService->singleProductWarehouseStock($productId, $warehouseId, $__branchId);
        } else {

            return $this->generalProductSearchService->singleProductBranchStock($productId, $__branchId);
        }
    }

    public function variantProductStock($productId, $variantId, $warehouseId = null, $branchId = null)
    {
        $__branchId = $branchId ? $branchId : auth()->user()->branch_id;

        if ($warehouseId) {

            return $this->generalProductSearchService->variantProductWarehouseStock($productId, $variantId, $warehouseId, $__branchId);
        } else {

            return $this->generalProductSearchService->variantProductBranchStock($productId, $variantId, $__branchId);
        }
    }

    public function productUnitAndMultiplierUnit($productId)
    {
        return $this->generalProductSearchService->getProductUnitAndMultiplierUnit($productId);
    }

    function productSearchByOnlyName($keyWord, $branchId = null) {

        $keyWord = (string) $keyWord;
        $__keyWord = str_replace('~', '/', $keyWord);

        $products = $this->generalProductSearchService->nameSearching(keyword: $keyWord, branchId: $branchId);

        return view('search_results_view.product_search_result_for_report_filter', [
            'products' => $products->getData()
        ]);
    }
}
