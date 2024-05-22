<?php

namespace App\Http\Controllers\Accounts;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountGroupService;
use App\Http\Requests\Accounts\AccountGroupStoreRequest;
use App\Http\Requests\Accounts\AccountGroupUpdateRequest;

class AccountGroupController extends Controller
{
    public function __construct(private AccountGroupService $accountGroupService)
    {
        $this->middleware('subscriptionRestrictions');
    }

    public function index()
    {
        abort_if(!auth()->user()->can('account_groups_index'), 403);

        return view('accounting.groups.index');
    }

    public function groupList(Request $request)
    {
        $groups = $this->accountGroupService->accountGroups(request: $request, with: ['subgroups'])
            ->where('account_groups.is_main_group', BooleanType::True->value)->get();

        return view('accounting.groups.ajax_view.list_of_groups', compact('groups'));
    }

    public function create()
    {
        abort_if(!auth()->user()->can('account_groups_create'), 403);

        $formGroups = $this->accountGroupService->accountGroups(with: ['parentGroup'])
            ->where('is_main_group', BooleanType::False->value)->orWhere('is_global', BooleanType::True->value)->get();

        return view('accounting.groups.ajax_view.create', compact('formGroups'));
    }

    public function store(AccountGroupStoreRequest $request)
    {
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
        abort_if(!auth()->user()->can('account_groups_edit'), 403);

        $formGroups = $this->accountGroupService->accountGroups(with: ['parentGroup'])
            ->where('is_main_group', BooleanType::False->value)->orWhere('is_global', BooleanType::True->value)->get();
        $group = $this->accountGroupService->singleAccountGroup(id: $id, with: ['parentGroup']);

        return view('accounting.groups.ajax_view.edit', compact('formGroups', 'group'));
    }

    public function update(AccountGroupUpdateRequest $request, $id)
    {
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
        abort_if(!auth()->user()->can('account_groups_delete'), 403);

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
