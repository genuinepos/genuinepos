<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Products\ProductService;
use App\Services\Products\ProductLedgerEntryService;

class ProductLedgerController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private BranchService $branchService,
        private ProductLedgerEntryService $productLedgerEntryService,
    ) {
    }

    public function index($id, Request $request)
    {
        if ($request->ajax()) {

            return $this->productLedgerEntryService->ledgerTable(request: $request, id: $id);
        }

        $product = $this->productService->singleProduct(id: $id, with: ['unit', 'brand', 'category', 'subcategory', 'variants']);

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('product.products.ledger.index', compact('product', 'branches'));
    }

    public function print($id, Request $request)
    {
        $ownOrParentBranch = '';
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch;
            } else {

                $branchName = auth()->user()?->branch;
            }
        }

        $filteredBranchName = $request->branch_name;
        $filteredWarehouseName = $request->warehouse_name;
        $filteredVariantName = $request->variant_name;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $product = $this->productService->singleProduct(id: $id, with: ['unit', 'category', 'subcategory', 'brand']);

        $entries = $this->productLedgerEntryService->ledgerEntriesPrint(request: $request, id: $id);

        return view('product.products.ledger.ajax_view.print', compact('entries', 'request', 'fromDate', 'toDate', 'filteredBranchName', 'filteredWarehouseName', 'filteredVariantName', 'product'));
    }
}
