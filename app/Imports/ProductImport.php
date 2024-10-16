<?php

namespace App\Imports;

use App\Enums\BooleanType;
use App\Models\Products\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Enums\ProductImportExcelCol;
use Illuminate\Support\Facades\Cache;
use App\Enums\ProductLedgerVoucherType;
use App\Models\Products\ProductAccessBranch;
use App\Models\Products\ProductOpeningStock;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductImport implements ToCollection
{
    public function __construct(
        private $productService,
        private $productStockService,
        private $purchaseProductService,
        private $productLedgerService,
    ) {
    }

    public function collection(Collection $collection)
    {
        $generalSettings = config('generalSettings');
        $productCodePrefix = $generalSettings['product__product_code_prefix'];

        $index = 0;
        foreach ($collection as $c) {
            
            $autoGeneratedProductCode = $productCodePrefix . $this->productService->getLastProductSerialCode();
            if ($index != 0) {

                if (isset($c[ProductImportExcelCol::Name->value]) && isset($c[ProductImportExcelCol::UnitCode->value])) {

                    $addProduct = new Product();
                    $addProduct->type = 1;
                    $addProduct->name = $c[ProductImportExcelCol::Name->value];
                    $addProduct->product_code = isset($c[ProductImportExcelCol::ProductCode->value]) ? $c[ProductImportExcelCol::ProductCode->value] : $autoGeneratedProductCode;

                    if ($c[ProductImportExcelCol::UnitCode->value]) {

                        $unit = DB::table('units')->where('code', $c[ProductImportExcelCol::UnitCode->value])->first(['id']);
                        if (isset($unit)) {

                            $addProduct->unit_id =  $unit->id;
                        } else {

                            continue;
                        }
                    }

                    if (isset($c[ProductImportExcelCol::CategoryCode->value])) {

                        $category = DB::table('categories')
                            ->whereNull('parent_category_id')
                            ->where('code', $c[ProductImportExcelCol::CategoryCode->value])
                            ->first(['id']);
                        $addProduct->category_id = isset($category) ? $category->id : null;
                    }

                    if (isset($c[ProductImportExcelCol::SubcategoryCode->value])) {

                        $subcategory = DB::table('categories')
                            ->whereNotNull('parent_category_id')
                            ->where('code', $c[ProductImportExcelCol::SubcategoryCode->value])
                            ->first(['id']);
                        $addProduct->sub_category_id = isset($subcategory) ? $subcategory->id : null;
                    }

                    if (isset($c[ProductImportExcelCol::SubcategoryCode->value])) {

                        $brand = DB::table('brands')
                            ->where('code', $c[ProductImportExcelCol::BrandCode->value])
                            ->first(['id']);
                        $addProduct->brand_id = isset($brand) ? $brand->id : null;
                    }

                    if (isset($c[ProductImportExcelCol::WarrantyCode->value])) {

                        $warranty = DB::table('warranties')
                            ->where('code', $c[ProductImportExcelCol::WarrantyCode->value])
                            ->first(['id']);
                        $addProduct->warranty_id = isset($warranty) ? $warranty->id : null;
                    }

                    $isManageStock = 1;
                    if (isset($c[ProductImportExcelCol::StockType->value]) && $c[ProductImportExcelCol::StockType->value] == 1) {

                        $isManageStock = 1;
                    } else if (isset($c[ProductImportExcelCol::StockType->value]) && $c[ProductImportExcelCol::StockType->value] == 2) {

                        $isManageStock = 0;
                    }

                    $addProduct->is_manage_stock = $isManageStock;

                    $addProduct->barcode_type = 'CODE128';
                    $addProduct->alert_quantity = isset($c[ProductImportExcelCol::AlertQty->value]) ? (float) $c[ProductImportExcelCol::AlertQty->value] : 0;

                    $taxPercent = isset($c[ProductImportExcelCol::TaxPercent->value]) ? (float)$c[ProductImportExcelCol::TaxPercent->value] : null;

                    if (isset($taxPercent)) {

                        $taxAccount = DB::table('accounts')
                            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
                            ->where('account_groups.is_default_tax_calculator', BooleanType::True->value)
                            ->where('accounts.tax_percent', $taxPercent)
                            ->first(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

                        $addProduct->tax_ac_id = isset($taxAccount) ? $taxAccount->id : null;
                    }

                    $taxType = 1;
                    if (isset($c[ProductImportExcelCol::TaxType->value]) && $c[ProductImportExcelCol::TaxType->value] == 2) {

                        $taxType = 2;
                    }

                    $addProduct->tax_type = $taxType;

                    $productCostExcTax = isset($c[ProductImportExcelCol::UnitCostExcTax->value]) ? (float)$c[ProductImportExcelCol::UnitCostExcTax->value] : 0;
                    $addProduct->product_cost = $productCostExcTax;

                    $productCostIncTax = isset($c[ProductImportExcelCol::UnitCostIncTax->value]) ? (float)$c[ProductImportExcelCol::UnitCostIncTax->value] : 0;
                    $addProduct->product_cost_with_tax = $productCostIncTax;

                    $productPriceExcTax = isset($c[ProductImportExcelCol::SellingPrice->value]) ? (float)$c[ProductImportExcelCol::SellingPrice->value] : 0;

                    $addProduct->product_price = $productPriceExcTax;

                    $__profitMargin = 0;
                    if ($productPriceExcTax > 0) {

                        $profitAmount = $productPriceExcTax - $productCostExcTax;
                        $__unitCostExcTax = $productCostExcTax > 0 ? $productCostExcTax : $profitAmount;
                        $profitMargin = $profitAmount / $__unitCostExcTax * 100;
                        $__profitMargin = $profitMargin ? $profitMargin : 0;
                    }

                    $addProduct->profit = $__profitMargin;
                    $addProduct->save();

                    // Add product access branch
                    $addProductAccessBranch = new ProductAccessBranch();
                    $addProductAccessBranch->product_id = $addProduct->id;
                    $addProductAccessBranch->save();

                    if (auth()->user()->branch_id) {

                        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

                        $addProductAccessBranch = new ProductAccessBranch();
                        $addProductAccessBranch->product_id = $addProduct->id;
                        $addProductAccessBranch->branch_id = $ownBranchIdOrParentBranchId;
                        $addProductAccessBranch->save();
                    }

                    if (isset($c[ProductImportExcelCol::OpeningStock->value])) {

                        // Add product opening stock
                        $accountStartDate = $generalSettings['business_or_shop__account_start_date'];
                        $date = $accountStartDate;

                        $openingStock = (float) $c[ProductImportExcelCol::OpeningStock->value];
                        $addOpeningStock = new ProductOpeningStock();
                        $addOpeningStock->branch_id = auth()->user()->branch_id;
                        $addOpeningStock->warehouse_id = null;
                        $addOpeningStock->product_id = $addProduct->id;
                        $addOpeningStock->variant_id = null;
                        $addOpeningStock->quantity = $openingStock;
                        $addOpeningStock->unit_cost_inc_tax = $productCostIncTax;
                        $addOpeningStock->subtotal = ($productCostIncTax * $openingStock);
                        $addOpeningStock->date = $date;
                        $addOpeningStock->date_ts = date('Y-m-d H:i:s', strtotime($date . ' 01:00:00'));
                        $addOpeningStock->save();

                        // Add product ledger entry
                        $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::OpeningStock->value, date: $addOpeningStock->date, productId: $addOpeningStock->product_id, transId: $addOpeningStock->id, rate: $addOpeningStock->unit_cost_inc_tax, quantityType: 'in', quantity: $addOpeningStock->quantity, subtotal: $addOpeningStock->subtotal, variantId: $addOpeningStock->variant_id, branchId: auth()->user()->branch_id, warehouseId: $addOpeningStock->warehouse_id);

                        // Adjust product stock
                        $this->productStockService->adjustBranchAllStock(productId: $addOpeningStock->product_id, variantId: $addOpeningStock->variant_id, branchId: $addOpeningStock->branch_id);

                        $this->productStockService->adjustBranchStock($addOpeningStock->product_id, $addOpeningStock->variant_id, $addOpeningStock->branch_id);

                        // product to purchase_products table for sale purchase stock accounting method.
                        $this->purchaseProductService->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(transColName: 'opening_stock_id', transId: $addOpeningStock->id, branchId: auth()->user()->branch_id, productId: $addOpeningStock->product_id, variantId: $addOpeningStock->variant_id, quantity: $addOpeningStock->quantity, unitCostIncTax: $addOpeningStock->unit_cost_inc_tax, sellingPrice: 0, subTotal: $addOpeningStock->subtotal, createdAt: $addOpeningStock->date_ts);
                    }
                }
            }
            $index++;
        }
    }
}
