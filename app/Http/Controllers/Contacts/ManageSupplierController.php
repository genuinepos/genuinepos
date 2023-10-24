<?php

namespace App\Http\Controllers\Contacts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            return $this->manageSupplierService->supplierListTable($request);
        }

        $branches = '';
        if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) {

            $branches = $this->branchService->branches(with: ['parentBranch'])
                ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();
        }

        return view('contacts.manage_suppliers.index', compact('branches'));
    }

    function manage($id)
    {
        $contact = $this->contactService->singleContact(id: $id, with: ['account:id,contact_id']);
        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('contacts.manage_suppliers.manage', compact('branches', 'contact'));
    }
}
