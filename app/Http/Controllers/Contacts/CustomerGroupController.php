<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Services\Contacts\CustomerGroupService;
use App\Services\Products\PriceGroupService;
use App\Services\Setups\BranchService;
use Illuminate\Http\Request;

class CustomerGroupController extends Controller
{
    public function __construct(
        private CustomerGroupService $customerGroupService,
        private PriceGroupService $priceGroupService,
        private BranchService $branchService,
    ) {
        $this->middleware('expireDate');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->customerGroupService->customerGroupsTable($request);
        }

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return view('contacts.customer_group.index', compact('branches'));
    }

    public function create()
    {
        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return view('contacts.customer_group.ajax_view.create', compact('priceGroups'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:customer_groups,name',
        ]);

        $this->customerGroupService->addCustomerGroup(request: $request);

        return response()->json(__('Customer group added successfully'));
    }

    public function edit($id)
    {
        $customerGroup = $this->customerGroupService->singleCustomerGroup(id: $id);
        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return view('contacts.customer_group.ajax_view.edit', compact('customerGroup', 'priceGroups'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:customer_groups,name,'.$id,
        ]);

        $this->customerGroupService->updateCustomerGroup(id: $id, request: $request);

        return response()->json(__('Customer group updated successfully'));
    }

    public function delete($id)
    {
        $this->customerGroupService->deleteCustomerGroup(id: $id);

        return response()->json(__('Customer group deleted successfully'));
    }
}
