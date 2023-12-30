<?php

namespace App\Http\Controllers\Products;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\PurchaseProduct;
use App\Models\SupplierProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Setups\BarcodeSetting;
use App\Services\Accounts\AccountService;
use App\Services\Setups\BarcodeSettingService;

class BarcodeController extends Controller
{
    public function __construct(private BarcodeSettingService $barcodeSettingService, private AccountService $accountService)
    {
    }

    // Generate barcode index view
    public function index()
    {
        if (!auth()->user()->can('generate_barcode')) {

            abort(403, 'Access Forbidden.');
        }

        $barcodeSettings = $this->barcodeSettingService->barcodeSettings()->select('id', 'name', 'is_default')->orderBy('is_continuous', 'desc')->get();

        $purchasedProducts = DB::table('purchase_products')
            ->leftJoin('products', 'purchase_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_products.variant_id', 'product_variants.id')
            ->join('purchases', 'purchase_products.purchase_id', 'purchases.id')
            ->leftJoin('accounts as supplier_account', 'purchases.supplier_account_id', 'supplier_account.id')
            ->leftJoin('contacts as supplier', 'supplier_account.contact_id', 'supplier.id')
            ->leftJoin('accounts as tax', 'products.tax_ac_id', 'tax.id')
            ->where('purchases.branch_id', auth()->user()->branch_id)
            ->where('purchase_products.label_left_qty', '>', 0)
            ->select(
                'products.id as product_id',
                'products.tax_ac_id',
                'products.tax_type',
                'products.name as product_name',
                'products.product_code',
                'products.product_price',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_price',
                'supplier.id as supplier_account_id',
                'supplier.id as supplier_id',
                'supplier.name as supplier_name',
                'supplier.prefix as supplier_prefix',
                'tax.tax_percent',
                DB::raw('SUM(purchase_products.label_left_qty) as total_left_qty')
            )->groupBy([
                'products.id',
                'products.tax_ac_id',
                'products.name',
                'products.product_code',
                'products.product_price',
                'product_variants.id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_price',
                'supplier.id',
                'supplier.name',
                'supplier.prefix',
                'tax.tax_percent',
            ])->get();

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        return view('product.barcode.index', compact('barcodeSettings', 'purchasedProducts', 'taxAccounts'));
    }

    public function preview(Request $request)
    {
        $req = $request;
        if (!isset($req->product_ids)) {

            session()->flash('errorMsg', 'Product list is empty.');

            return redirect()->back();
        }

        $barcodeSetting = BarcodeSetting::where('id', $request->br_setting_id)->first();

        return view('product.barcode.preview', compact('barcodeSetting', 'req'));
    }

    // Get all supplier products
    public function supplierProduct()
    {
        $supplier_products = SupplierProduct::with(['supplier', 'product', 'product.tax', 'variant'])->where('label_qty', '>', 0)->get();
        return view('product.barcode.ajax_view.purchase_product_list', compact('supplier_products'));
    }

    public function multipleGenerateCompleted(Request $request)
    {
        $index = 0;
        foreach ($request->product_ids as $product_id) {

            $variant_id = $request->product_variant_ids[$index] != 'null' ? $request->product_variant_ids[$index] : null;

            $supplierProduct = SupplierProduct::where('supplier_id', $request->supplier_ids[$index])
                ->where('product_id', $product_id)
                ->where('variant_id', $variant_id)
                ->first();

            if ($supplierProduct) {

                $supplierProduct->label_qty = 0;
                $supplierProduct->save();
            }
            $index++;
        }

        return response()->json(['Successfully completed barcode row is deleted.']);
    }

    // Search product
    public function searchProduct($searchKeyword)
    {
        $products = Product::with(['product_purchased_variants'])
            ->where('name', 'like', $searchKeyword . '%')
            ->where('is_purchased', 1)->select(
                'id',
                'name',
                'product_code',
                'is_combo',
                'is_featured',
                'is_for_sale',
                'is_manage_stock',
                'is_purchased',
                'is_variant',
                'offer_price',
                'product_cost',
                'product_cost_with_tax',
                'product_price',
                'profit',
                'quantity',
                'tax_id',
                'tax_type',
                'type',
                'unit_id',
            )->limit(25)
            ->get();
        if ($products->count() > 0) {

            return response()->json($products);
        } else {

            $products = Product::with(['product_purchased_variants'])
                ->where('product_code', $searchKeyword)
                ->where('is_purchased', 1)
                ->get();

            return response()->json($products);
        }
    }

    // // Get selected product
    // public function getSelectedProduct($productId)
    // {
    //     $supplierProducts = SupplierProduct::with('supplier', 'product', 'product.tax', 'variant')->where('product_id', $productId)->get();
    //     return response()->json($supplierProducts);
    // }

    // // Get selected product variant
    // public function getSelectedProductVariant($productId, $variantId)
    // {
    //     $supplierProducts = SupplierProduct::with(
    //         'supplier',
    //         'product',
    //         'product.tax',
    //         'variant'
    //     )->where('product_id', $productId)->where('product_variant_id', $variantId)->get();

    //     return response()->json($supplierProducts);
    // }

    // // Generate specific product barcode view
    // public function generateProductBarcode($productId)
    // {
    //     $productId = $productId;
    //     $bc_settings = DB::table('barcode_settings')->orderBy('is_continuous', 'desc')->get(['id', 'name', 'is_default']);

    //     return view('product.barcode.specific_product_barcode', compact('productId', 'bc_settings'));
    // }

    // // Get specific product's supplier product
    // public function getSpecificSupplierProduct($productId)
    // {
    //     $supplierProducts = SupplierProduct::with('supplier', 'product', 'product.tax', 'variant')->where('product_id', $productId)->get();

    //     return response()->json($supplierProducts);
    // }

    // // Generate barcode on purchase view
    // public function onPurchaseBarcode($purchaseId)
    // {
    //     $purchaseId = $purchaseId;
    //     $bc_settings = DB::table('barcode_settings')->orderBy('is_continuous', 'desc')->get(['id', 'name', 'is_default']);

    //     return view('product.barcode.purchase_product_barcode_v2', compact('purchaseId', 'bc_settings'));
    // }

    // Get purchase products for generating barcode
    // public function getPurchaseProduct($purchaseId)
    // {
    //     $purchaseProducts = PurchaseProduct::with(['purchase', 'purchase.supplier', 'product', 'variant'])->where('purchase_id', $purchaseId)->get();
    //     return response()->json($purchaseProducts);
    // }
}
