<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountGroupService;
use App\Services\Setups\BranchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountGroupController extends Controller
{
    public function __construct(
        private AccountGroupService $accountGroupService,
        private BranchService $branchService,
    ) {
        $this->middleware('expireDate');
    }

    public function index()
    {
        if (!auth()->user()->can('account_groups_index')) {
            abort(403, 'Access Forbidden.');
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('accounting.groups.index', compact('branches'));
    }

    public function groupList(Request $request)
    {
        $groups = $this->accountGroupService->accountGroups(request: $request, with: ['subgroups'])
            ->where('account_groups.is_main_group', 1)->get();

        return view('accounting.groups.ajax_view.list_of_groups', compact('groups'));
    }

    public function create()
    {
        if (!auth()->user()->can('account_groups_create')) {
            abort(403, 'Access Forbidden.');
        }

        $formGroups = $this->accountGroupService->accountGroups(with: ['parentGroup'])
            ->where('is_main_group', 0)->orWhere('is_global', 1)->get();

        return view('accounting.groups.ajax_view.create', compact('formGroups'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('account_groups_create')) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'parent_group_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $addAccountGroup = $this->accountGroupService->addAccountGroup($request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addAccountGroup;
    }

    public function edit($id)
    {
        if (!auth()->user()->can('account_groups_edit')) {
            abort(403, 'Access Forbidden.');
        }

        $formGroups = $this->accountGroupService->accountGroups(with: ['parentGroup'])
            ->where('is_main_group', 0)->orWhere('is_global', 1)->get();
        $group = $this->accountGroupService->singleAccountGroup(id: $id, with: ['parentGroup']);

        return view('accounting.groups.ajax_view.edit', compact('formGroups', 'group'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('account_groups_create')) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'parent_group_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $updateAccountGroup = $this->accountGroupService->updateAccountGroup(id: $id, request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Account Group Updated Successfully'));
    }

    public function delete(Request $request, $id)
    {
        if (!auth()->user()->can('account_groups_delete')) {
            abort(403, 'Access Forbidden.');
        }

        try {
            DB::beginTransaction();

            $deleteAccountGroup = $this->accountGroupService->deleteAccountGroup($id);

            if ($deleteAccountGroup['success'] == false) {

                return response()->json(['errorMsg' => $deleteAccountGroup['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json($deleteAccountGroup['msg']);
    }
}
