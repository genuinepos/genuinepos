<?php

namespace App\Services\Sales\MethodContainerServices;

use App\Services\Products\BrandService;
use App\Services\Sales\DiscountService;
use App\Services\Products\ProductService;
use App\Services\Products\CategoryService;
use App\Services\Products\PriceGroupService;
use App\Services\Sales\DiscountProductService;
use App\Interfaces\Sales\DiscountControllerMethodContainersInterface;

class DiscountControllerMethodContainersService implements DiscountControllerMethodContainersInterface
{
    public function __construct(
        private DiscountService $discountService,
        private DiscountProductService $discountProductService,
        private ProductService $productService,
        private BrandService $brandService,
        private CategoryService $categoryService,
        private PriceGroupService $priceGroupService,
    ) {
    }

    public function indexMethodContainer(object $request): ?object
    {
        if ($request->ajax()) {

            return $this->discountService->discountListTable($request);
        }

        return null;
    }

    public function createMethodContainer(): array
    {
        $data = [];
        $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $data['brands'] = $this->brandService->brands()->select('id', 'name')->get();
        $data['categories'] = $this->categoryService->categories()->where('parent_category_id', null)->select('id', 'name')->get();
        $data['products'] = $this->productService->branchProducts(branchId: $ownBranchIdOrParentBranchId);
        $data['priceGroups'] = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return $data;
    }

    public function storeMethodContainer(object $request): ?array
    {
        $restrictions = $this->discountService->restrictions($request);
        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $addDiscount = $this->discountService->addDiscount(request: $request);

        if (isset($request->product_ids) && count($request->product_ids) > 0) {

            $this->discountProductService->addDiscountProducts(request: $request, discountId: $addDiscount->id);
        }

        return null;
    }

    public function editMethodContainer(int $id): array
    {
        $data = [];
        $discount = $this->discountService->singleDiscount(id: $id, with: ['discountProducts']);

        $ownBranchIdOrParentBranchId = $discount?->branch?->parent_branch_id ? $discount?->branch?->parent_branch_id : $discount->branch_id;

        $data['brands'] = $this->brandService->brands()->select('id', 'name')->get();
        $data['categories'] = $this->categoryService->categories()->where('parent_category_id', null)->select('id', 'name')->get();
        $data['products'] = $this->productService->branchProducts(branchId: $ownBranchIdOrParentBranchId);
        $data['priceGroups'] = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        $data['discount'] =  $discount;

        return $data;
    }

    public function updateMethodContainer(int $id, object $request): ?array
    {
        $restrictions = $this->discountService->restrictions($request);
        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $updateDiscount = $this->discountService->updateDiscount(request: $request, id: $id);
        $this->discountProductService->updateDiscountProducts(request: $request, discount: $updateDiscount);

        return null;
    }

    public function deleteMethodContainer(int $id): void
    {
        $this->discountService->deleteDiscount(discountId: $id);
    }

    public function changeStatusMethodContainer(int $id): void
    {
        $this->discountService->changeDiscountStatus(id: $id);
    }
}
