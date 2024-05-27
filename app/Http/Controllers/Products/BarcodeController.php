<?php

namespace App\Http\Controllers\Products;

use App\Models\Products\Product;
use Illuminate\Http\Request;
use App\Models\PurchaseProduct;
use App\Models\SupplierProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Setups\BarcodeSetting;
use App\Services\Accounts\AccountService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Setups\BarcodeSettingService;

class BarcodeController extends Controller
{
    public function __construct(
        private BarcodeSettingService $barcodeSettingService,
        private AccountService $accountService,
        private PurchaseProductService $purchaseProductService
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index()
    {
        abort_if(!auth()->user()->can('generate_barcode'), 403);

        $barcodeSettings = $this->barcodeSettingService->barcodeSettings()
            ->select('id', 'name', 'is_default')
            ->orderBy('is_continuous', 'desc')->get();

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
                'purchases.supplier_account_id',
                'supplier.name as supplier_name',
                'supplier.prefix as supplier_prefix',
                'tax.tax_percent',
                DB::raw('SUM(purchase_products.label_left_qty) as total_left_qty')
            )->groupBy([
                'products.id',
                'products.tax_ac_id',
                'products.tax_type',
                'products.name',
                'products.product_code',
                'products.product_price',
                'product_variants.id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_price',
                'purchases.supplier_account_id',
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

    public function emptyLabelQty($supplierAccountId, $productId, $variantId = null)
    {
        $supplierPurchasedProducts = $this->purchaseProductService->purchaseProducts()
            ->where('label_left_qty', '>', 0)
            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
            ->where('purchases.supplier_account_id', $supplierAccountId)
            ->where('purchase_products.product_id', $productId)
            ->where('purchase_products.variant_id', $variantId)
            ->where('purchases.branch_id', auth()->user()->branch_id)
            ->select(
                'purchase_products.id',
                'purchase_products.product_id',
                'purchase_products.variant_id',
                'purchase_products.purchase_id',
                'purchase_products.label_left_qty'
            )->get();

        foreach ($supplierPurchasedProducts as $supplierPurchasedProduct) {

            $supplierPurchasedProduct->label_left_qty = 0;
            $supplierPurchasedProduct->save();
        }

        return response()->json(__('Successfully completed barcode row is deleted.'));
    }
}
