<?php

namespace App\Http\Controllers\Contacts;

use App\Enums\BooleanType;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Contacts\ContactService;
use App\Services\Contacts\ManageSupplierService;
use App\Http\Requests\Contacts\ManageSupplierRequest;
use App\Http\Requests\Contacts\ManageSupplierIndexRequest;

class ManageSupplierController extends Controller
{
    public function __construct(
        private ContactService $contactService,
        private BranchService $branchService,
        private ManageSupplierService $manageSupplierService,
    ) {
    }

    public function index(ManageSupplierIndexRequest $request)
    {
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

    public function manage($id, ManageSupplierRequest $request)
    {
        $contact = $this->contactService->singleContact(id: $id, with: ['account:id,contact_id']);
        abort_if(!$contact, 404);
        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('contacts.manage_suppliers.manage', compact('branches', 'contact'));
    }
}
