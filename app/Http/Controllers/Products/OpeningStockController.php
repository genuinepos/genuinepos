<?php

namespace App\Http\Controllers\Products;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\ProductLedgerVoucherType;
use App\Services\Products\ProductService;
use App\Services\Setups\WarehouseService;
use App\Services\Products\OpeningStockService;
use App\Services\Products\ProductStockService;
use App\Services\Products\ProductLedgerService;
use App\Services\Purchases\PurchaseProductService;

class OpeningStockController extends Controller
{
    public function __construct(
        private OpeningStockService $openingStockService,
        private ProductStockService $productStockService,
        private PurchaseProductService $purchaseProductService,
        private ProductService $productService,
        private ProductLedgerService $productLedgerService,
        private WarehouseService $warehouseService,
    ) {
    }

    public function createOrEdit($productId)
    {
        $product = $this->productService->singleProduct(id: $productId, with: ['variants']);

        $warehouses = $this->warehouseService->warehouses(with: ['openingStockProduct'])
            ->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', BooleanType::True->value)
            ->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        return view('product.products.opening_stock.create_or_edit', compact('product', 'warehouses'));
    }

    public function storeOrUpdate(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->branch_ids as $index => $branch_id) {

                $addOrEditOpeningStock = $this->openingStockService->addOrEditProductOpeningStock(request: $request, index: $index);

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

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Opening stock is added successfully.'));
    }
}
