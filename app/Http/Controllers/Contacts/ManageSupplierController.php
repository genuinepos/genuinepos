<?php

namespace App\Http\Controllers\Contacts;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Contacts\ContactService;
use App\Services\Contacts\ManageSupplierService;

class ManageSupplierController extends Controller
{
    public function __construct(
        private ContactService $contactService,
        private BranchService $branchService,
        private ManageSupplierService $manageSupplierService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('supplier_manage') || config('generalSettings')['subscription']->features['contacts'] == 0, 403);

        if ($request->ajax()) {

            return $this->manageSupplierService->supplierListTable($request);
        }

        $branches = '';
        // if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) {
        if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == BooleanType::False->value) {

            $branches = $this->branchService->branches(with: ['parentBranch'])
                ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();
        }

        return view('contacts.manage_suppliers.index', compact('branches'));
    }

    public function manage($id)
    {
        abort_if(!auth()->user()->can('supplier_manage') || config('generalSettings')['subscription']->features['contacts'] == 0, 403);

        $contact = $this->contactService->singleContact(id: $id, with: ['account:id,contact_id']);
        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('contacts.manage_suppliers.manage', compact('branches', 'contact'));
    }
}
