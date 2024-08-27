<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Products\PriceGroupService;
use App\Services\Contacts\CustomerGroupService;
use App\Http\Requests\Contacts\CustomerGroupEditRequest;
use App\Http\Requests\Contacts\CustomerGroupIndexRequest;
use App\Http\Requests\Contacts\CustomerGroupStoreRequest;
use App\Http\Requests\Contacts\CustomerGroupCreateRequest;
use App\Http\Requests\Contacts\CustomerGroupDeleteRequest;
use App\Http\Requests\Contacts\CustomerGroupUpdateRequest;

class CustomerGroupController extends Controller
{
    public function __construct(
        private CustomerGroupService $customerGroupService,
        private PriceGroupService $priceGroupService,
        private BranchService $branchService,
    ) {
    }

    public function index(CustomerGroupIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->customerGroupService->customerGroupsTable($request);
        }

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return view('contacts.customer_group.index', compact('branches'));
    }

    public function create(CustomerGroupCreateRequest $request)
    {
        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return view('contacts.customer_group.ajax_view.create', compact('priceGroups'));
    }

    public function store(CustomerGroupStoreRequest $request)
    {
        $this->customerGroupService->addCustomerGroup(request: $request);

        return response()->json(__('Customer group added successfully'));
    }

    public function edit($id, CustomerGroupEditRequest $request)
    {
        $customerGroup = $this->customerGroupService->singleCustomerGroup(id: $id);
        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return view('contacts.customer_group.ajax_view.edit', compact('customerGroup', 'priceGroups'));
    }

    public function update(CustomerGroupUpdateRequest $request, $id)
    {
        $this->customerGroupService->updateCustomerGroup(id: $id, request: $request);

        return response()->json(__('Customer group updated successfully'));
    }

    public function delete($id, CustomerGroupDeleteRequest $request)
    {
        $this->customerGroupService->deleteCustomerGroup(id: $id);

        return response()->json(__('Customer group deleted successfully'));
    }
}
