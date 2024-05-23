<?php

namespace App\Http\Controllers\Contacts;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Contacts\ContactService;
use App\Services\Contacts\ManageCustomerService;

class ManageCustomerController extends Controller
{
    public function __construct(
        private ContactService $contactService,
        private BranchService $branchService,
        private ManageCustomerService $manageCustomerService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('customer_manage') || config('generalSettings')['subscription']->features['contacts'] == BooleanType::False->value, 403);

        if ($request->ajax()) {

            return $this->manageCustomerService->customerListTable($request);
        }

        $branches = '';
        if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == BooleanType::False->value) {

            $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();
        }

        return view('contacts.manage_customers.index', compact('branches'));
    }

    public function manage($id)
    {
        abort_if(!auth()->user()->can('customer_manage') || config('generalSettings')['subscription']->features['contacts'] == BooleanType::False->value, 403);

        $contact = $this->contactService->singleContact(id: $id, with: [
            'account:id,contact_id,branch_id',
            'account.branch:id,name,branch_code,parent_branch_id',
        ]);

        $ownBranchIdOrParentBranchId = $contact->account?->branch?->parent_branch_id ? $contact->account?->branch?->parent_branch_id : $contact->account?->branch_id;

        $branch = $this->branchService->singleBranch(id: $ownBranchIdOrParentBranchId, with: ['childBranches']);

        return view('contacts.manage_customers.manage', compact('branch', 'contact'));
    }
}
