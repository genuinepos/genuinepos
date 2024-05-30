<?php

namespace App\Http\Controllers\Products;

use Exception;
use App\Imports\ProductImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Products\ProductService;
use App\Services\Products\ProductStockService;
use App\Services\Products\ProductLedgerService;
use App\Services\Purchases\PurchaseProductService;
use App\Http\Requests\Products\ProductImportRequest;

class ProductImportController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private ProductStockService $productStockService,
        private PurchaseProductService $purchaseProductService,
        private ProductLedgerService $productLedgerService,
    ) {
    }

    public function create()
    {
        abort_if(!auth()->user()->can('product_import'), 403);
        return view('product.import.create');
    }

    public function store(ProductImportRequest $request)
    {
        try {
            DB::beginTransaction();

            Excel::import(
                new ProductImport(
                    productService: $this->productService,
                    productStockService: $this->productStockService,
                    purchaseProductService: $this->purchaseProductService,
                    productLedgerService: $this->productLedgerService,
                ),
                $request->import_file
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
            return __('Something went wrong. Please check again the imported file.') . ' <a href="' . url()->previous() . '">' . __('Back') . '</a>';
        }

        return redirect()->back()->with('successMsg', __('Product created Successfully'));
    }
}
