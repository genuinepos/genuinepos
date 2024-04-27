<?php

namespace App\Services\Purchases;

use Carbon\Carbon;
use App\Enums\RoleType;
use App\Enums\BooleanType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Enums\StockAccountingMethod;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Purchases\PurchaseProduct;

class PurchaseProductService
{
    public function purchaseProductsTable($request)
    {
        $generalSettings = config('generalSettings');
        $purchaseProducts = '';
        $query = DB::table('purchase_products')
            ->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('products', 'purchase_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_products.variant_id', 'product_variants.id')
            ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
            ->leftJoin('units', 'purchase_products.unit_id', 'units.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as sub_cate', 'products.sub_category_id', 'sub_cate.id')
            ->whereNotNull('purchase_products.purchase_id');

        if ($request->product_id) {

            $query->where('purchase_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('purchase_products.variant_id', $request->variant_id);
        }

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('purchases.branch_id', null);
            } else {

                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if ($request->supplier_account_id) {

            $query->where('purchases.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->sub_category_id) {

            $query->where('products.sub_category_id', $request->sub_category_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        // if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            // $query->where('purchases.is_purchased', BooleanType::True->value)->where('purchases.branch_id', auth()->user()->branch_id);
            $query->where('purchases.branch_id', auth()->user()->branch_id);
        }

        $purchaseProducts = $query->select(
            'purchase_products.purchase_id',
            'purchase_products.product_id',
            'purchase_products.variant_id',
            'purchase_products.net_unit_cost',
            'purchase_products.quantity',
            'units.code_name as unit_code',
            'purchase_products.line_total',
            'purchase_products.selling_price',
            'purchases.id',
            'purchases.branch_id',
            'purchases.supplier_account_id',
            'purchases.date',
            'purchases.invoice_id',
            'products.name',
            'products.product_code',
            'products.product_price',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_price',
            'suppliers.name as supplier_name',
            'branches.name as branch_name',
            'branches.area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
        )->orderBy('purchases.report_date', 'desc');

        return DataTables::of($purchaseProducts)
            ->editColumn('product', function ($row) {

                $variant = $row->variant_name ? ' - ' . $row->variant_name : '';

                return Str::limit($row->name, 35, '') . $variant;
            })
            ->editColumn('date', function ($row) {

                return date('d/m/Y', strtotime($row->date));
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })
            ->editColumn('quantity', function ($row) {

                return \App\Utils\Converter::format_in_bdt($row->quantity) . '/<span class="qty" data-value="' . $row->quantity . '">' . $row->unit_code . '</span>';
            })
            ->editColumn('invoice_id', fn ($row) => '<a href="' . route('purchases.show', [$row->purchase_id]) . '" class="text-hover" id="details_btn" title="View">' . $row->invoice_id . '</a>')

            ->editColumn('net_unit_cost', fn ($row) => \App\Utils\Converter::format_in_bdt($row->net_unit_cost))
            ->editColumn('price', function ($row) {
                if ($row->selling_price > 0) {

                    return \App\Utils\Converter::format_in_bdt($row->selling_price);
                } else {

                    if ($row->variant_name) {

                        return \App\Utils\Converter::format_in_bdt($row->variant_price);
                    } else {

                        return \App\Utils\Converter::format_in_bdt($row->product_price);
                    }
                }

                return \App\Utils\Converter::format_in_bdt($row->net_unit_cost);
            })
            ->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="' . $row->line_total . '">' . \App\Utils\Converter::format_in_bdt($row->line_total) . '</span>')

            ->rawColumns(['product', 'product_code', 'date', 'quantity', 'invoice_id', 'branch', 'net_unit_cost', 'price', 'subtotal'])
            ->make(true);
    }

    public function addPurchaseProduct($request, $isEditProductPrice, $purchaseId, $index)
    {
        $warehouse_id = isset($request->warehouse_count) ? $request->warehouse_id : null;

        $addPurchaseProduct = new PurchaseProduct();
        $addPurchaseProduct->purchase_id = $purchaseId;
        $addPurchaseProduct->product_id = $request->product_ids[$index];
        $addPurchaseProduct->variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $addPurchaseProduct->description = $request->descriptions[$index];
        $addPurchaseProduct->quantity = $request->quantities[$index];
        $addPurchaseProduct->label_left_qty = $request->quantities[$index];
        $addPurchaseProduct->left_qty = $request->quantities[$index];
        $addPurchaseProduct->unit_id = $request->unit_ids[$index];
        $addPurchaseProduct->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
        $addPurchaseProduct->unit_discount = $request->unit_discounts[$index];
        $addPurchaseProduct->unit_cost_with_discount = $request->unit_costs_with_discount[$index];
        $addPurchaseProduct->subtotal = $request->subtotals[$index];
        $addPurchaseProduct->tax_type = $request->tax_types[$index];
        $addPurchaseProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addPurchaseProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addPurchaseProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addPurchaseProduct->net_unit_cost = $request->net_unit_costs[$index];
        $addPurchaseProduct->line_total = $request->linetotals[$index];
        $addPurchaseProduct->branch_id = auth()->user()->branch_id;

        if ($isEditProductPrice == '1') {

            $addPurchaseProduct->profit_margin = $request->profits[$index];
            $addPurchaseProduct->selling_price = $request->selling_prices[$index];
        }

        if (isset($request->lot_number)) {

            $addPurchaseProduct->lot_no = $request->lot_number[$index];
        }

        $addPurchaseProduct->batch_number = $request->batch_numbers[$index];
        $addPurchaseProduct->expire_date = isset($request->expire_dates[$index]) ? date('Y-m-d', strtotime($request->expire_dates[$index])) : null;
        $addPurchaseProduct->created_at = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));

        $addPurchaseProduct->save();

        return $addPurchaseProduct;
    }

    public function addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
        string $transColName,
        int $transId,
        ?int $branchId,
        int $productId,
        ?int $variantId,
        float $quantity,
        float $unitCostIncTax,
        float $sellingPrice,
        float $subTotal,
        string $createdAt,
        float $xMargin = 0
    ) {

        $purchaseProduct = PurchaseProduct::where($transColName, $transId)
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if ($purchaseProduct) {

            $purchaseProduct->net_unit_cost = $unitCostIncTax;
            $purchaseProduct->quantity = $quantity;
            $purchaseProduct->line_total = $subTotal;
            $purchaseProduct->profit_margin = $xMargin;
            $purchaseProduct->selling_price = $sellingPrice;
            $purchaseProduct->created_at = $purchaseProduct->created_at == null ? $createdAt : $purchaseProduct->created_at;
            $purchaseProduct->save();
            (new \App\Services\Products\StockChainService())->adjustPurchaseProductOutLeftQty($purchaseProduct);
        } else {

            $addRowInPurchaseProductTable = new PurchaseProduct();
            $addRowInPurchaseProductTable->branch_id = $branchId;
            $addRowInPurchaseProductTable->{$transColName} = $transId;
            $addRowInPurchaseProductTable->product_id = $productId;
            $addRowInPurchaseProductTable->variant_id = $variantId;
            $addRowInPurchaseProductTable->net_unit_cost = $unitCostIncTax;
            $addRowInPurchaseProductTable->quantity = $quantity;
            $addRowInPurchaseProductTable->left_qty = $quantity;
            $addRowInPurchaseProductTable->line_total = $subTotal;
            $addRowInPurchaseProductTable->profit_margin = $xMargin;
            $addRowInPurchaseProductTable->selling_price = $sellingPrice;
            $addRowInPurchaseProductTable->created_at = $createdAt;
            $addRowInPurchaseProductTable->save();
        }
    }

    public function updatePurchaseProduct($request, $purchaseId, $isEditProductPrice, $index)
    {
        $filterVariantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $updateOrAddPurchaseProduct = '';
        $purchaseProduct = PurchaseProduct::where('purchase_id', $purchaseId)->where('id', $request->purchase_product_ids[$index])->first();
        $currentUnitTaxAcId = $purchaseProduct ? $purchaseProduct->tax_ac_id : null;

        if ($purchaseProduct) {

            $updateOrAddPurchaseProduct = $purchaseProduct;
        } else {

            $updateOrAddPurchaseProduct = new PurchaseProduct();
        }

        $updateOrAddPurchaseProduct->purchase_id = $purchaseId;
        $updateOrAddPurchaseProduct->product_id = $request->product_ids[$index];
        $updateOrAddPurchaseProduct->variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $updateOrAddPurchaseProduct->description = $request->descriptions[$index];
        $updateOrAddPurchaseProduct->quantity = $request->quantities[$index];
        $updateOrAddPurchaseProduct->label_left_qty = $updateOrAddPurchaseProduct->label_left_qty > 0 ? $request->quantities[$index] : 0;
        $updateOrAddPurchaseProduct->left_qty = $request->quantities[$index];
        $updateOrAddPurchaseProduct->unit_id = $request->unit_ids[$index];
        $updateOrAddPurchaseProduct->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
        $updateOrAddPurchaseProduct->unit_discount = $request->unit_discounts[$index];
        $updateOrAddPurchaseProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $updateOrAddPurchaseProduct->unit_discount_type = $request->unit_discount_types[$index];
        $updateOrAddPurchaseProduct->unit_cost_with_discount = $request->unit_costs_with_discount[$index];
        $updateOrAddPurchaseProduct->subtotal = $request->subtotals[$index];
        $updateOrAddPurchaseProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $updateOrAddPurchaseProduct->tax_type = $request->tax_types[$index];
        $updateOrAddPurchaseProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $updateOrAddPurchaseProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $updateOrAddPurchaseProduct->net_unit_cost = $request->net_unit_costs[$index];
        $updateOrAddPurchaseProduct->line_total = $request->linetotals[$index];

        if ($isEditProductPrice == '1') {

            $updateOrAddPurchaseProduct->profit_margin = $request->profits[$index];
            $updateOrAddPurchaseProduct->selling_price = $request->selling_prices[$index];
        }

        if (isset($request->lot_numbers)) {

            $updateOrAddPurchaseProduct->lot_no = $request->lot_numbers[$index];
        }

        $updateOrAddPurchaseProduct->batch_number = $request->batch_numbers[$index];
        $updateOrAddPurchaseProduct->expire_date = isset($request->expire_dates[$index]) ? date('Y-m-d', strtotime($request->expire_dates[$index])) : null;

        $updateOrAddPurchaseProduct->delete_in_update = 0;
        $updateOrAddPurchaseProduct->created_at = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $updateOrAddPurchaseProduct->save();

        (new \App\Services\Products\StockChainService())->adjustPurchaseProductOutLeftQty($updateOrAddPurchaseProduct);

        $updateOrAddPurchaseProduct->current_tax_ac_id = $currentUnitTaxAcId;

        return $updateOrAddPurchaseProduct;
    }

    public function singlePurchaseProduct(array $with = null): ?object
    {
        $query = PurchaseProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function purchaseProducts(array $with = null): ?object
    {
        $query = PurchaseProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function deleteUnusedPurchaseProduct(string $transColName, int $transColValue, int $productId, int $variantId = null): void
    {
        $deletePurchaseProduct = $this->singlePurchaseProduct()->where($transColName, $transColValue)
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if (!is_null($deletePurchaseProduct)) {

            $deletePurchaseProduct->delete();
        }
    }
}
