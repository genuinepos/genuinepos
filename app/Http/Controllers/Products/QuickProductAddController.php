<?php

namespace App\Http\Controllers\Products;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use App\Http\Controllers\Controller;
use App\Services\Products\UnitService;
use App\Services\Setups\BranchService;
use App\Enums\ProductLedgerVoucherType;
use App\Services\Products\BrandService;
use App\Services\Accounts\AccountService;
use App\Services\Products\ProductService;
use App\Services\Setups\WarehouseService;
use App\Services\Products\CategoryService;
use App\Services\Products\WarrantyService;
use App\Services\Products\PriceGroupService;
use App\Services\Products\BulkVariantService;
use App\Services\Products\OpeningStockService;
use App\Services\Products\ProductStockService;
use App\Services\Products\ProductVariantService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Products\ProductAccessBranchService;

class QuickProductAddController extends Controller
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
        private ProductStockService $productStockService,
        private PurchaseProductService $purchaseProductService,
        private UserActivityLogUtil $userActivityLogUtil
    ) {
    }

    public function create()
    {
        $categories = $this->categoryService->categories()->where('parent_category_id', null)->get(['id', 'name']);
        $brands = $this->brandService->brands()->get(['id', 'name']);
        $units = $this->unitService->units()->get(['id', 'name', 'code_name']);
        $warranties = $this->warrantyService->warranties()->orderBy('id', 'desc')->get(['id', 'name']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', BooleanType::True->value)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        $lastProductSerialCode = $this->productService->getLastProductSerialCode();

        $warehouses = $this->warehouseService->warehouses(with: ['openingStockProduct'])
            ->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', BooleanType::True->value)
            ->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        return view('product.products.quick_add_product.create', compact('units', 'categories', 'brands', 'warranties', 'taxAccounts', 'branches', 'lastProductSerialCode', 'warehouses'));
    }

    function store(Request $request)
    {
        return $request->all();

        $this->validate(
            $request,
            [
                'name' => 'required',
                'code' => 'sometimes|unique:products,product_code',
                'unit_id' => 'required',
            ],
            [
                'unit_id.required' => 'Product unit field is required.',
            ]
        );

        try {
            DB::beginTransaction();

            $addProduct = $this->productService->addProduct($request);

            foreach ($request->branch_ids as $index => $branch_id) {

                $addOrEditOpeningStock = $this->openingStockService->addOrEditProductOpeningStock(request: $request, index: $index, productId: $addProduct->id);

                $this->productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::OpeningStock->value, date: $addOrEditOpeningStock->date, productId: $addOrEditOpeningStock->product_id, transId: $addOrEditOpeningStock->id, rate: $addOrEditOpeningStock->unit_cost_inc_tax, quantityType: 'in', quantity: $addOrEditOpeningStock->quantity, subtotal: $addOrEditOpeningStock->subtotal, variantId: $addOrEditOpeningStock->variant_id, branchId: auth()->user()->branch_id, warehouseId: $addOrEditOpeningStock->warehouse_id);

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
                'unit:id,name,code_name',
                'unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier'
            ]);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $product;
    }
}
