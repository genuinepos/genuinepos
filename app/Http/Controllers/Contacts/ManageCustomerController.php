<?php

namespace App\Http\Controllers\Contacts;

use App\Enums\BooleanType;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Contacts\ContactService;
use App\Services\Contacts\ManageCustomerService;
use App\Http\Requests\Contacts\ManageCustomerRequest;
use App\Http\Requests\Contacts\ManageCustomerIndexRequest;

class ManageCustomerController extends Controller
{
    public function __construct(
        private ContactService $contactService,
        private BranchService $branchService,
        private ManageCustomerService $manageCustomerService,
    ) {
    }

    public function index(ManageCustomerIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->manageCustomerService->customerListTable($request);
        }

        $branches = '';
        if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == BooleanType::False->value) {

            $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();
        }

        return view('contacts.manage_customers.index', compact('branches'));
    }

    public function manage($id, ManageCustomerRequest $request)
    {
        $contact = $this->contactService->singleContact(id: $id, with: [
            'account:id,contact_id,branch_id',
            'account.branch:id,name,branch_code,parent_branch_id',
        ]);

        abort_if(!$contact, 404);

        $ownBranchIdOrParentBranchId = $contact->account?->branch?->parent_branch_id ? $contact->account?->branch?->parent_branch_id : $contact->account?->branch_id;

        $branch = $this->branchService->singleBranch(id: $ownBranchIdOrParentBranchId, with: ['childBranches']);

        return view('contacts.manage_customers.manage', compact('branch', 'contact'));
    }
}
