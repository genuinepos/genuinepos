<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\DiscountStoreRequest;
use App\Http\Requests\Sales\DiscountUpdateRequest;
use App\Interfaces\Sales\DiscountControllerMethodContainersInterface;

class DiscountController extends Controller
{
    public function __construct() {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request, DiscountControllerMethodContainersInterface $discountControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('discounts'), 403);

        $indexMethodContainer = $discountControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;;
        }

        return view('sales.discounts.index');
    }

    public function create(DiscountControllerMethodContainersInterface $discountControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('discounts'), 403);

        $createMethodContainer = $discountControllerMethodContainersInterface->createMethodContainer();

        extract($createMethodContainer);

        return view('sales.discounts.ajax_view.create', compact('brands', 'categories', 'products', 'priceGroups'));
    }

    public function store(DiscountStoreRequest $request, DiscountControllerMethodContainersInterface $discountControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $discountControllerMethodContainersInterface->storeMethodContainer(request: $request);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Discount created successfully'));
    }

    public function edit($id, DiscountControllerMethodContainersInterface $discountControllerMethodContainersInterface)
    {
        $editMethodContainer = $discountControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('sales.discounts.ajax_view.edit', compact('discount', 'brands', 'categories', 'products', 'priceGroups'));
    }

    public function update($id, DiscountUpdateRequest $request, DiscountControllerMethodContainersInterface $discountControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $discountControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Discount updated successfully'));
    }

    public function delete($id, DiscountControllerMethodContainersInterface $discountControllerMethodContainersInterface)
    {
        $discountControllerMethodContainersInterface->deleteMethodContainer(id: $id);
        return response()->json(__('Discount deleted successfully'));
    }

    public function changeStatus($id, DiscountControllerMethodContainersInterface $discountControllerMethodContainersInterface)
    {
        $discountControllerMethodContainersInterface->changeStatusMethodContainer(id: $id);
        return response()->json(__('Discount status has been changed successfully'));
    }
}
