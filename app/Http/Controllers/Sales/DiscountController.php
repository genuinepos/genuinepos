<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Services\Products\BrandService;
use App\Services\Products\CategoryService;
use App\Services\Products\PriceGroupService;
use App\Services\Products\ProductService;
use App\Services\Sales\DiscountProductService;
use App\Services\Sales\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    public function __construct(
        private DiscountService $discountService,
        private DiscountProductService $discountProductService,
        private ProductService $productService,
        private BrandService $brandService,
        private CategoryService $categoryService,
        private PriceGroupService $priceGroupService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->discountService->discountListTable($request);
        }

        return view('sales.discounts.index');
    }

    public function create()
    {
        $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $brands = $this->brandService->brands()->select('id', 'name')->get();
        $categories = $this->categoryService->categories()->where('parent_category_id', null)->select('id', 'name')->get();
        $products = $this->productService->branchProducts(branchId: $ownBranchIdOrParentBranchId);
        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return view('sales.discounts.ajax_view.create', compact('brands', 'categories', 'products', 'priceGroups'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'priority' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
            'discount_type' => 'required',
            'discount_amount' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $restrictions = $this->discountService->restrictions($request);
            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $addDiscount = $this->discountService->addDiscount(request: $request);

            if (isset($request->product_ids) && count($request->product_ids) > 0) {

                $this->discountProductService->addDiscountProducts(request: $request, discountId: $addDiscount->id);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Discount created successfully'));
    }

    public function edit($id)
    {
        $discount = $this->discountService->singleDiscount(id: $id, with: ['discountProducts']);

        $ownBranchIdOrParentBranchId = $discount?->branch?->parent_branch_id ? $discount?->branch?->parent_branch_id : $discount->branch_id;

        $brands = $this->brandService->brands()->select('id', 'name')->get();
        $categories = $this->categoryService->categories()->where('parent_category_id', null)->select('id', 'name')->get();
        $products = $this->productService->branchProducts(branchId: $ownBranchIdOrParentBranchId);
        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return view('sales.discounts.ajax_view.edit', compact('discount', 'brands', 'categories', 'products', 'priceGroups'));
    }

    public function update($id, Request $request)
    {
        try {

            DB::beginTransaction();

            $restrictions = $this->discountService->restrictions($request);
            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $updateDiscount = $this->discountService->updateDiscount(request: $request, id: $id);
            $this->discountProductService->updateDiscountProducts(request: $request, discount: $updateDiscount);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Discount updated successfully'));
    }

    public function delete($id)
    {
        $this->discountService->deleteDiscount(discountId: $id);

        return response()->json(__('Discount deleted successfully'));
    }

    public function changeStatus($id)
    {
        $addDiscount = $this->discountService->changeDiscountStatus(id: $id);

        return response()->json(__('Discount status has been changed successfully'));
    }
}
