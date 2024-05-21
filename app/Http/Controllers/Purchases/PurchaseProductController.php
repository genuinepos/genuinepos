<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountService;
use App\Services\Products\CategoryService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Setups\BranchService;
use Illuminate\Http\Request;

class PurchaseProductController extends Controller
{
    public function __construct(
        private PurchaseProductService $purchaseProductService,
        private BranchService $branchService,
        private AccountService $accountService,
        private CategoryService $categoryService,
    ) {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('purchased_product_list'), 403);

        if ($request->ajax()) {

            return $this->purchaseProductService->purchaseProductsTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $categories = $this->categoryService->categories()->where('parent_category_id', null)->get();

        return view('purchase.purchases.purchase_products.index', compact('branches', 'supplierAccounts', 'categories', 'ownBranchIdOrParentBranchId'));
    }

    public function purchaseProductsForPurchaseReturn($purchaseId)
    {
        $purchaseProducts = $this->purchaseProductService->purchaseProducts(with: [
            'purchase:id,warehouse_id',
            'purchase.warehouse:id,warehouse_name,warehouse_code',
            'product:id,name,product_code,unit_id',
            'product.unit:id,name,code_name',
            'product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'variant:id,variant_name,variant_code',
            'unit:id,name,base_unit_id,base_unit_multiplier',
            'unit.baseUnit:id,name,base_unit_id',
        ])->where('purchase_id', $purchaseId)->get();

        $itemUnitsArray = [];
        foreach ($purchaseProducts as $purchaseProduct) {

            if (isset($purchaseProduct->product_id)) {

                $itemUnitsArray[$purchaseProduct->product_id][] = [
                    'unit_id' => $purchaseProduct->product->unit->id,
                    'unit_name' => $purchaseProduct->product->unit->name,
                    'unit_code_name' => $purchaseProduct->product->unit->code_name,
                    'base_unit_multiplier' => 1,
                    'multiplier_details' => '',
                    'is_base_unit' => 1,
                ];
            }

            if (count($purchaseProduct?->product?->unit?->childUnits) > 0) {

                foreach ($purchaseProduct?->product?->unit?->childUnits as $unit) {

                    $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $purchaseProduct?->product?->unit?->name . ')';

                    array_push($itemUnitsArray[$purchaseProduct->product_id], [
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->name,
                        'unit_code_name' => $unit->code_name,
                        'base_unit_multiplier' => $unit->base_unit_multiplier,
                        'multiplier_details' => $multiplierDetails,
                        'is_base_unit' => 0,
                    ]);
                }
            }
        }

        $view = view('purchase.purchases.purchase_products.ajax_view.purchased_products_for_purchase_return', ['purchaseProducts' => $purchaseProducts])->render();

        return [
            'view' => $view,
            'units' => json_encode($itemUnitsArray),
        ];
    }
}
