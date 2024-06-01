<?php

namespace App\Http\Controllers\Contacts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Products\PriceGroupService;
use App\Services\Contacts\CustomerGroupService;
use App\Http\Requests\Contacts\CustomerGroupStoreRequest;
use App\Http\Requests\Contacts\CustomerGroupUpdateRequest;

class CustomerGroupController extends Controller
{
    public function __construct(
        private CustomerGroupService $customerGroupService,
        private PriceGroupService $priceGroupService,
        private BranchService $branchService,
    ) {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('customer_group') || config('generalSettings')['subscription']->features['contacts'] == 0, 403);

        if ($request->ajax()) {

            return $this->customerGroupService->customerGroupsTable($request);
        }

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return view('contacts.customer_group.index', compact('branches'));
    }

    public function create()
    {
        abort_if(!auth()->user()->can('customer_group') || config('generalSettings')['subscription']->features['contacts'] == 0, 403);

        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return view('contacts.customer_group.ajax_view.create', compact('priceGroups'));
    }

    public function store(CustomerGroupStoreRequest $request)
    {
        $this->customerGroupService->addCustomerGroup(request: $request);

        return response()->json(__('Customer group added successfully'));
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('customer_group') || config('generalSettings')['subscription']->features['contacts'] == 0, 403);

        $customerGroup = $this->customerGroupService->singleCustomerGroup(id: $id);
        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return view('contacts.customer_group.ajax_view.edit', compact('customerGroup', 'priceGroups'));
    }

    public function update(CustomerGroupUpdateRequest $request, $id)
    {
        $this->customerGroupService->updateCustomerGroup(id: $id, request: $request);

        return response()->json(__('Customer group updated successfully'));
    }

    public function delete($id)
    {
        abort_if(!auth()->user()->can('customer_group') || config('generalSettings')['subscription']->features['contacts'] == 0, 403);

        $this->customerGroupService->deleteCustomerGroup(id: $id);

        return response()->json(__('Customer group deleted successfully'));
    }
}
