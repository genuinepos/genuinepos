<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountGroupService;

class AccountGroupController extends Controller
{
    public function __construct(
        private AccountGroupService $accountGroupService,
    ) {
    }

    public function index()
    {
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('accounting.groups.index', compact('branches'));
    }

    public function groupList()
    {
        $groups = $this->accountGroupService->accountGroups(with: ['subgroups'])->where('is_main_group', 1)->get();
        return view('accounting.groups.ajax_view.list_of_groups', compact('groups'));
    }

    public function create()
    {
        $formGroups = $this->accountGroupService->accountGroups(with: ['parentGroup'])->where('is_main_group', 0)->get();
        return view('accounting.groups.ajax_view.create', compact('formGroups'));
    }

    public function store(Request $request)
    {
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
        $formGroups = $this->accountGroupService->accountGroups(with: ['parentGroup'])->where('is_main_group', 0)->get();
        $group = $this->accountGroupService->singleAccountGroup(id: $id, with: ['parentGroup']);

        return view('accounting.groups.ajax_view.edit', compact('formGroups', 'group'));
    }

    public function update(Request $request, $id)
    {
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

    public function accountGroupBranchWise(Request $request) {

        return $this->accountGroupService->accountGroups($request)->select('id', 'name')->where('is_main_group', 0)->orWhere('is_global', 1)->get();
    }

    public function delete(Request $request, $id)
    {
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
