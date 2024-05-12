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
        $this->middleware('subscriptionRestrictions');
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
}
