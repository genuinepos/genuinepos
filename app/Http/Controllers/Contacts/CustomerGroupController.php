<?php

namespace App\Http\Controllers\Contacts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\Products\PriceGroupService;
use App\Services\Contacts\CustomerGroupService;

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
        if ($request->ajax()) {

            return $this->customerGroupService->customerGroupsTable($request);
        }

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return view('contacts.customer_group.index', compact('branches'));
    }

    function create()
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
            'name' => 'required|unique:customer_groups,name,' . $id,
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
