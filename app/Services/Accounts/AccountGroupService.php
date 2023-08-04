<?php

namespace App\Services\Accounts;

use Illuminate\Support\Facades\DB;
use App\Models\Accounts\AccountGroup;

class AccountGroupService
{
    function accountGroups(object $request = null, array $with = null)
    {
        $query = AccountGroup::query();

        if (isset($with)) {

            $query->with($with);
        }

        if (isset($request)) {

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('branch_id', NULL);
                } else {

                    $query->where('branch_id', $request->branch_id);
                }
            }
        }

        if (auth()->user()->role_type != 1 || !auth()->user()->role_type != 2) {

            $query->where('branch_id', auth()->user()->branch_id);
        }

        return $query;
    }

    public function addAccountGroup($request)
    {
        $parentGroup = DB::table('account_groups')->where('id', $request->parent_group_id)->first();

        $addGroup = new AccountGroup();
        $addGroup->name = $request->name;
        $addGroup->branch_id = $parentGroup->is_global == 0 ? auth()->user()->branch_id : null;
        $addGroup->parent_group_id = $request->parent_group_id;
        $addGroup->is_default_tax_calculator = $request->is_default_tax_calculator;
        $addGroup->is_allowed_bank_details = $request->is_allowed_bank_details;
        $addGroup->is_bank_or_cash_ac = $parentGroup->is_bank_or_cash_ac;
        $addGroup->is_sub_sub_group = 1;
        $addGroup->main_group_number = $parentGroup->main_group_number;
        $addGroup->sub_group_number = $parentGroup->sub_group_number;
        $addGroup->sub_sub_group_number = $parentGroup->sub_sub_group_number;
        $addGroup->main_group_name = $parentGroup->main_group_name;
        $addGroup->sub_group_name = $parentGroup->sub_group_name;
        $addGroup->sub_sub_group_name = $parentGroup->sub_sub_group_name;
        $addGroup->default_balance_type = $parentGroup->default_balance_type;
        $addGroup->save();

        $group = AccountGroup::with('parentGroup')->where('id', $addGroup->id)->first();

        return $group;
    }

    public function updateAccountGroup($id, $request)
    {
        $parentGroup = DB::table('account_groups')->where('id', $request->parent_group_id)->first();

        $updateGroup = AccountGroup::where('id', $id)->first();
        $updateGroup->name = $request->name;
        $updateGroup->is_default_tax_calculator = $request->is_default_tax_calculator;
        $updateGroup->is_allowed_bank_details = $request->is_allowed_bank_details;

        if ($updateGroup->is_reserved == 0) {

            $updateGroup->parent_group_id = $request->parent_group_id;
            $updateGroup->is_bank_or_cash_ac = $parentGroup->is_bank_or_cash_ac;
            $updateGroup->main_group_number = $parentGroup->main_group_number;
            $updateGroup->sub_group_number = $parentGroup->sub_group_number;
            $updateGroup->sub_sub_group_number = $parentGroup->sub_sub_group_number;
            $updateGroup->main_group_name = $parentGroup->main_group_name;
            $updateGroup->sub_group_name = $parentGroup->sub_group_name;
            $updateGroup->sub_sub_group_name = $parentGroup->sub_sub_group_name;
            $updateGroup->default_balance_type = $parentGroup->default_balance_type;
            $updateGroup->is_global = $parentGroup->is_global;
        }

        $updateGroup->save();
    }

    public function deleteAccountGroup(int $id)
    {
        // $deleteGroup = AccountGroup::with('subGroups', 'accounts')->where('id', $id)->first();

        $deleteGroup = AccountGroup::with('subGroups')->where('id', $id)->first();

        if (!is_null($deleteGroup)) {

            // if ($deleteGroup->is_reserved == 1 || count($deleteGroup->subGroups) > 0 || count($deleteGroup->accounts)) {
            if ($deleteGroup->is_reserved == 1 || count($deleteGroup->subGroups) > 0) {

                return ['success' => false, 'msg' => __('Account Group can not be deleted')];
            }

            $deleteGroup->delete();
        }

        return ['success' => true, 'msg' => __('Account group deleted successfully.')];
    }

    public function singleAccountGroup(int $id, array $with = null)
    {
        $query = AccountGroup::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function singleAccountGroupByAnyCondition(array $with = null)
    {
        $query = AccountGroup::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
