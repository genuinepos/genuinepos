<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Services\Products\PriceGroupService;
use Illuminate\Http\Request;

class PriceGroupController extends Controller
{
    public function __construct(
        private PriceGroupService $priceGroupService,
    ) {
        $this->middleware('expireDate');
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('selling_price_group_index')) {

            abort(403, __('Access Forbidden.'));
        }

        if ($request->ajax()) {

            return $this->priceGroupService->priceGroupsTable();
        }

        return view('product.price_group.index');
    }

    public function create()
    {
        if (! auth()->user()->can('selling_price_group_index')) {

            abort(403, __('Access Forbidden.'));
        }

        return view('product.price_group.ajax_view.create');
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('selling_price_group_index')) {

            abort(403, __('Access Forbidden.'));
        }

        $this->validate($request, [
            'name' => 'required|unique:price_groups,name',
        ]);

        $addPriceGroup = $this->priceGroupService->addPriceGroup($request);

        return $addPriceGroup;
    }

    public function edit($id)
    {
        if (! auth()->user()->can('selling_price_group_index')) {

            abort(403, __('Access Forbidden.'));
        }

        $priceGroup = $this->priceGroupService->singlePriceGroup(id: $id);

        return view('product.price_group.ajax_view.edit', compact('priceGroup'));
    }

    public function update($id, Request $request)
    {
        if (! auth()->user()->can('selling_price_group_index')) {

            abort(403, __('Access Forbidden.'));
        }

        $this->validate($request, [
            'name' => 'required|unique:price_groups,name,'.$id,
        ]);

        $this->priceGroupService->updatePriceGroup(id: $id, request: $request);

        return response()->json(__('Price group updated Successfully'));
    }

    public function delete($id, Request $request)
    {
        if (! auth()->user()->can('selling_price_group_index')) {

            abort(403, __('Access Forbidden.'));
        }

        $this->priceGroupService->deletePriceGroup(id: $id);

        return response()->json(__('Price group delete Successfully.'));
    }

    public function changeStatus($id)
    {
        if (! auth()->user()->can('selling_price_group_index')) {

            abort(403, __('Access Forbidden.'));
        }

        $changeStatus = $this->priceGroupService->changeStatus(id: $id);

        return response()->json($changeStatus['msg']);
    }
}
