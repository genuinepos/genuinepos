<?php

namespace App\Http\Controllers\Sales;

use App\Models\Discount;
use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Products\BrandService;
use App\Services\Sales\DiscountService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\Products\ProductService;
use App\Services\Products\CategoryService;
use App\Services\Products\PriceGroupService;
use App\Services\Sales\DiscountProductService;

class DiscountController extends Controller
{
    public function __construct(
        private DiscountService $discountService,
        private DiscountProductService $discountProductService,
        private ProductService $productService,
        private BrandService $brandService,
        private CategoryService $categoryService,
        private BranchService $branchService,
        private PriceGroupService $priceGroupService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
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

    public function edit($discountId)
    {
        $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $discount = $this->discountService->singleDiscount(id: $id);

        $brands = $this->brandService->brands()->select('id', 'name')->get();
        $categories = $this->categoryService->categories()->where('parent_category_id', null)->select('id', 'name')->get();
        $products = $this->productService->branchProducts(branchId: $ownBranchIdOrParentBranchId);
        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return view('sales.discounts.ajax_view.edit', compact('brands', 'categories', 'products', 'priceGroups'));
    }

    public function update(Request $request, $discountId)
    {
        $updateDiscount = Discount::with('discountProducts')->where('id', $discountId)->first();

        foreach ($updateDiscount->discountProducts as $discountProduct) {

            $discountProduct->is_delete_in_update = 1;
            $discountProduct->save();
        }

        $updateDiscount->branch_id = auth()->user()->branch_id;
        $updateDiscount->name = $request->name;
        $updateDiscount->priority = $request->priority;
        $updateDiscount->start_at = date('Y-m-d', strtotime($request->start_at));
        $updateDiscount->end_at = date('Y-m-d', strtotime($request->end_at));
        $updateDiscount->discount_type = $request->discount_type;
        $updateDiscount->discount_amount = $request->discount_amount;
        $updateDiscount->price_group_id = $request->price_group_id;
        $updateDiscount->is_active = isset($request->is_active) ? 1 : 0;
        $updateDiscount->apply_in_customer_group = isset($request->apply_in_customer_group) ? 1 : 0;
        $updateDiscount->save();

        if (isset($request->product_ids) && count($request->product_ids) > 0) {

            $updateDiscount->brand_id = null;
            $updateDiscount->category_id = null;

            foreach ($request->product_ids as $product_id) {

                $discountProduct = DiscountProduct::where('discount_id', $updateDiscount->id)
                    ->where('product_id', $product_id)->first();

                if ($discountProduct) {

                    $discountProduct->is_delete_in_update = 0;
                    $discountProduct->save();
                } else {

                    $addDiscountProduct = new DiscountProduct();
                    $addDiscountProduct->discount_id = $updateDiscount->id;
                    $addDiscountProduct->product_id = $product_id;
                    $addDiscountProduct->save();
                }
            }
        } else {

            foreach ($updateDiscount->discountProducts as $discountProduct) {

                $discountProduct->delete();
            }

            $updateDiscount->brand_id = $request->brand_id;
            $updateDiscount->category_id = $request->category_id;
            $updateDiscount->save();
        }

        // Unused discount product
        $deleteDiscountProducts = DiscountProduct::where('discount_id', $updateDiscount->id)->where('is_delete_in_update', 1)->get();

        foreach ($deleteDiscountProducts as $deleteDiscountProduct) {

            $deleteDiscountProduct->delete();
        }

        return response()->json('Offer updated successfully');
    }

    public function delete($discountId)
    {
        $deleteDiscount = Discount::where('id', $discountId)->first();

        if (!is_null($deleteDiscount)) {

            $deleteDiscount->delete();
        }

        return response()->json('Offer deleted successfully');
    }

    public function changeStatus($id)
    {
        $addDiscount = $this->discountService->changeDiscountStatus(id: $id);
        return response()->json(__('Discount status has been changed successfully'));
    }
}
