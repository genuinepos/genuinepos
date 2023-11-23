<?php

namespace App\Services\Setups;

use App\Enums\BranchType;
use App\Models\Accounts\Account;
use App\Models\Accounts\AccountGroup;
use App\Models\Role;
use App\Models\Setups\Branch;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class BranchService
{
    public function branchListTable()
    {
        $generalSettings = config('generalSettings');
        $logoUrl = asset('uploads/branch_logo');
        $branches = '';
        $query = DB::table('branches')->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');

        $branches = $query->select(
            'branches.id',
            'branches.branch_type',
            'branches.area_name',
            'branches.name as branch_name',
            'branches.branch_code',
            'branches.phone',
            'branches.logo',
            'branches.city',
            'branches.state',
            'branches.zip_code',
            'branches.country',
            'parentBranch.name as parent_branch_name',
        )->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return DataTables::of($branches)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item" id="edit" href="' . route('branches.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                $html .= '<a class="dropdown-item" id="delete" href="' . route('branches.delete', [$row->id]) . '">' . __('Delete') . '</a>';
                $html .= '<a class="dropdown-item" id="branchSettings" href="' . route('branches.settings.edit', [$row->id]) . '">' . __('Shop Settings') . '</a>';
                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('branchName', function ($row) {

                if ($row->branch_type == 1) {

                    return '</span> <span class="fw-bold">' . $row->branch_name . ' (' . $row->area_name . ')' . '</span>';
                } else {

                    return '<span class="fas fa-long-arrow-alt-right text-success" style="font-size:15px;"></span> <span class="fw-bold">' . $row->parent_branch_name . ' (' . $row->area_name . ')' . '</span>';
                }
            })

            ->editColumn('logo', function ($row) use ($logoUrl) {

                return '<img loading="lazy" class="rounded" style="height:40px; width:40px; padding:2px 0px;" src="' . $logoUrl . '/' . $row->logo . '">';
            })

            ->editColumn('address', function ($row) {

                return $row->city . ', ' . $row->state . ', ' . $row->zip_code . ', ' . $row->country;
            })

            ->rawColumns(['branchName', 'shopLogo', 'logo', 'address', 'action'])
            ->make(true);
    }

    public function addBranch($request): object
    {
        $addBranch = new Branch();
        $addBranch->branch_type = $request->branch_type;
        $addBranch->name = $request->branch_type == BranchType::DifferentShop->value ? $request->name : null;
        $addBranch->area_name = $request->area_name;
        $addBranch->parent_branch_id = $request->branch_type == 2 ? $request->parent_branch_id : null;
        $addBranch->branch_code = $request->branch_code;
        $addBranch->phone = $request->phone;
        $addBranch->city = $request->city;
        $addBranch->state = $request->state;
        $addBranch->zip_code = $request->zip_code;
        $addBranch->country = $request->country;
        $addBranch->alternate_phone_number = $request->alternate_phone_number;
        $addBranch->email = $request->email;
        $addBranch->website = $request->website;
        $addBranch->purchase_permission = $request->purchase_permission;

        $branchLogoName = '';
        if ($request->hasFile('logo')) {

            $branchLogo = $request->file('logo');
            $branchLogoName = uniqid() . '-' . '.' . $branchLogo->getClientOriginalExtension();
            $branchLogo->move(public_path('uploads/branch_logo/'), $branchLogoName);

            $addBranch->logo = $branchLogoName;
        }

        $addBranch->save();

        return $addBranch;
    }

    public function updateBranch(int $id, object $request): void
    {
        $updateBranch = $this->singleBranch($id);
        $updateBranch->name = $request->branch_type;
        $updateBranch->name = $request->branch_type == 1 ? $request->name : null;
        $updateBranch->area_name = $request->area_name;
        $updateBranch->parent_branch_id = $request->branch_type == 2 ? $request->parent_branch_id : null;
        $updateBranch->branch_code = $request->branch_code;
        $updateBranch->phone = $request->phone;
        $updateBranch->city = $request->city;
        $updateBranch->state = $request->state;
        $updateBranch->zip_code = $request->zip_code;
        $updateBranch->country = $request->country;
        $updateBranch->alternate_phone_number = $request->alternate_phone_number;
        $updateBranch->email = $request->email;
        $updateBranch->website = $request->website;
        $updateBranch->purchase_permission = $request->purchase_permission;

        if ($request->hasFile('logo')) {

            if ($updateBranch->logo != 'default.png') {

                if (file_exists(public_path('uploads/branch_logo/' . $updateBranch->logo))) {

                    unlink(public_path('uploads/branch_logo/' . $updateBranch->logo));
                }
            }

            $branchLogo = $request->file('logo');
            $branchLogoName = uniqid() . '-' . '.' . $branchLogo->getClientOriginalExtension();
            $branchLogo->move(public_path('uploads/branch_logo/'), $branchLogoName);
            $updateBranch->logo = $branchLogoName;
        }

        $updateBranch->save();
    }

    public function deleteBranch(int $id): array
    {
        $deleteBranch = $this->singleBranch(id: $id, with: ['sales', 'purchases', 'childBranches']);

        if (count($deleteBranch->childBranches) > 0) {

            return ['pass' => false, 'msg' => __('Shop can not be deleted. This shop has one or more chain shop.')];
        }

        if (count($deleteBranch->sales) > 0) {

            return ['pass' => false, 'msg' => __('Shop can not be deleted. This shop has one or more sales.')];
        }

        if (count($deleteBranch->purchases) > 0) {

            return ['pass' => false, 'msg' => __('Shop can not be deleted. This shop has one or more purchases.')];
        }

        if ($deleteBranch->logo != 'default.png') {

            if (file_exists(public_path('uploads/branch_logo/' . $deleteBranch->logo))) {

                unlink(public_path('uploads/branch_logo/' . $deleteBranch->logo));
            }
        }

        $deleteBranch->delete();

        return ['pass' => true];
    }

    // public function addBranchDefaultAccountGroups(int $branchId): void
    // {
    //     $accountGroups = [
    //         ['sorting_number' => '0', 'branch_id' => $branchId, 'name' => 'Assets', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '1', 'is_sub_group' => '0', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '1', 'sub_group_number' => null, 'sub_sub_group_number' => null, 'main_group_name' => 'Assets', 'sub_group_name' => null, 'sub_sub_group_name' => null, 'default_balance_type' => 'dr', 'is_global' => '0', 'parent_name' => null],
    //         ['sorting_number' => '1', 'branch_id' => $branchId, 'name' => 'Current Assets', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '1', 'is_parent_sub_group' => '1', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '1', 'sub_group_number' => '1', 'sub_sub_group_number' => null, 'main_group_name' => 'Assets', 'sub_group_name' => 'Current Assets', 'sub_sub_group_name' => null, 'default_balance_type' => 'dr', 'is_global' => '0', 'parent_name' => 'Assets'],
    //         ['sorting_number' => '2', 'branch_id' => $branchId, 'name' => 'Cash-In-Hand', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '1', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '0', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '1', 'is_parent_sub_sub_group' => '1', 'main_group_number' => '1', 'sub_group_number' => '1', 'sub_sub_group_number' => '2', 'main_group_name' => 'Assets', 'sub_group_name' => 'Current Assets', 'sub_sub_group_name' => 'Cash-In-Hand', 'default_balance_type' => 'dr', 'is_global' => '0', 'parent_name' => 'Current Assets'],
    //         ['sorting_number' => '4', 'branch_id' => $branchId, 'name' => 'Deposits (Asset)', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '0', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '1', 'is_parent_sub_sub_group' => '1', 'main_group_number' => '1', 'sub_group_number' => '1', 'sub_sub_group_number' => '3', 'main_group_name' => 'Assets', 'sub_group_name' => 'Current Assets', 'sub_sub_group_name' => 'Deposits (Asset)', 'default_balance_type' => 'dr', 'is_global' => '0', 'parent_name' => 'Current Assets'],
    //         ['sorting_number' => '5', 'branch_id' => $branchId, 'name' => 'Loan & Advance (Asset)', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '0', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '1', 'is_parent_sub_sub_group' => '1', 'main_group_number' => '1', 'sub_group_number' => '1', 'sub_sub_group_number' => '4', 'main_group_name' => 'Assets', 'sub_group_name' => 'Current Assets', 'sub_sub_group_name' => 'Loan & Advance (Asset)', 'default_balance_type' => 'dr', 'is_global' => '0', 'parent_name' => 'Current Assets'],
    //         ['sorting_number' => '6', 'branch_id' => $branchId, 'name' => 'Stock-In-Hand', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '0', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '1', 'main_group_number' => '1', 'sub_group_number' => '1', 'sub_sub_group_number' => '5', 'main_group_name' => 'Assets', 'sub_group_name' => 'Current Assets', 'sub_sub_group_name' => 'Stock-In-Hand', 'default_balance_type' => 'dr', 'is_global' => '0', 'parent_name' => 'Current Assets'],
    //         ['sorting_number' => '7', 'branch_id' => $branchId, 'name' => 'Account Receivable', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '0', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '1', 'is_parent_sub_sub_group' => '1', 'main_group_number' => '1', 'sub_group_number' => '1', 'sub_sub_group_number' => '6', 'main_group_name' => 'Assets', 'sub_group_name' => 'Current Assets', 'sub_sub_group_name' => 'Sundry Debtors', 'default_balance_type' => 'dr', 'is_global' => '0', 'parent_name' => 'Current Assets'],
    //         ['sorting_number' => '8', 'branch_id' => $branchId, 'name' => 'Fixed Assets', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '1', 'is_parent_sub_group' => '1', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '1', 'sub_group_number' => '2', 'sub_sub_group_number' => null, 'main_group_name' => 'Assets', 'sub_group_name' => 'Fixed Assets', 'sub_sub_group_name' => null, 'default_balance_type' => 'dr', 'is_global' => '0', 'parent_name' => 'Assets'],
    //         ['sorting_number' => '9', 'branch_id' => $branchId, 'name' => 'Investments', 'is_reserved' => '0', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '1', 'is_parent_sub_group' => '1', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '1', 'sub_group_number' => '3', 'sub_sub_group_number' => null, 'main_group_name' => 'Assets', 'sub_group_name' => 'Investments', 'sub_sub_group_name' => null, 'default_balance_type' => 'dr', 'is_global' => '0', 'parent_name' => 'Assets'],
    //         ['sorting_number' => '10', 'branch_id' => $branchId, 'name' => 'Liabilities', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '1', 'is_sub_group' => '0', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '2', 'sub_group_number' => null, 'sub_sub_group_number' => null, 'main_group_name' => 'Liabilities', 'sub_group_name' => null, 'sub_sub_group_name' => null, 'default_balance_type' => 'cr', 'is_global' => '0', 'parent_name' => null],
    //         ['sorting_number' => '11', 'branch_id' => $branchId, 'name' => 'Branch / Divisions', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '1', 'is_parent_sub_group' => '1', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '2', 'sub_group_number' => '5', 'sub_sub_group_number' => null, 'main_group_name' => 'Liabilities', 'sub_group_name' => 'Branch / Divisions', 'sub_sub_group_name' => null, 'default_balance_type' => 'cr', 'is_global' => '0', 'parent_name' => 'Liabilities'],
    //         // ['sorting_number' => '12', 'branch_id' => $branchId, 'name' => 'Capital Account', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '1', 'is_parent_sub_group' => '1', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '2', 'sub_group_number' => '6', 'sub_sub_group_number' => null, 'main_group_name' => 'Liabilities', 'sub_group_name' => 'Capital Account', 'sub_sub_group_name' => null, 'default_balance_type' => 'cr', 'is_global' => '0', 'parent_name' => 'Liabilities'],
    //         // ['sorting_number' => '13', 'branch_id' => $branchId, 'name' => 'Reserves / Surplus', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '1', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '1', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '2', 'sub_group_number' => '6', 'sub_sub_group_number' => '7', 'main_group_name' => 'Liabilities', 'sub_group_name' => 'Capital Account', 'sub_sub_group_name' => null, 'default_balance_type' => 'cr', 'is_global' => '0', 'parent_name' => 'Capital Account'],
    //         ['sorting_number' => '14', 'branch_id' => $branchId, 'name' => 'Current Liabilities', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '1', 'is_parent_sub_group' => '1', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '2', 'sub_group_number' => '7', 'sub_sub_group_number' => null, 'main_group_name' => 'Liabilities', 'sub_group_name' => 'Current Liabilities', 'sub_sub_group_name' => null, 'default_balance_type' => 'cr', 'is_global' => '0', 'parent_name' => 'Liabilities'],
    //         // ['sorting_number' => '15', 'branch_id' => $branchId, 'name' => 'Duties & Taxes', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '1', 'is_default_tax_calculator' => '1', 'is_main_group' => '0', 'is_sub_group' => '0', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '1', 'is_parent_sub_sub_group' => '1', 'main_group_number' => '2', 'sub_group_number' => '7', 'sub_sub_group_number' => '8', 'main_group_name' => 'Liabilities', 'sub_group_name' => 'Current Liabilities', 'sub_sub_group_name' => 'Duties & Taxes', 'default_balance_type' => 'cr', 'is_global' => '0', 'parent_name' => 'Current Liabilities'],
    //         ['sorting_number' => '16', 'branch_id' => $branchId, 'name' => 'Provisions', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '0', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '1', 'is_parent_sub_sub_group' => '1', 'main_group_number' => '2', 'sub_group_number' => '7', 'sub_sub_group_number' => '9', 'main_group_name' => 'Liabilities', 'sub_group_name' => 'Current Liabilities', 'sub_sub_group_name' => 'Provisions', 'default_balance_type' => 'cr', 'is_global' => '0', 'parent_name' => 'Current Liabilities'],
    //         // ['sorting_number' => '17', 'branch_id' => $branchId, 'name' => 'Account Payable', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '0', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '1', 'is_parent_sub_sub_group' => '1', 'main_group_number' => '2', 'sub_group_number' => '7', 'sub_sub_group_number' => '10', 'main_group_name' => 'Liabilities', 'sub_group_name' => 'Current Liabilities', 'sub_sub_group_name' => 'Sundry Creditors', 'default_balance_type' => 'cr', 'is_global' => '1', 'parent_name' => 'Current Liabilities'],
    //         ['sorting_number' => '18', 'branch_id' => $branchId, 'name' => 'Loans (Liability)', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '1', 'is_parent_sub_group' => '1', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '2', 'sub_group_number' => '8', 'sub_sub_group_number' => null, 'main_group_name' => 'Liabilities', 'sub_group_name' => 'Loans (Liability)', 'sub_sub_group_name' => null, 'default_balance_type' => 'cr', 'is_global' => '0', 'parent_name' => 'Liabilities'],
    //         ['sorting_number' => '20', 'branch_id' => $branchId, 'name' => 'Secure Loans', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '0', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '1', 'is_parent_sub_sub_group' => '1', 'main_group_number' => '2', 'sub_group_number' => '8', 'sub_sub_group_number' => '12', 'main_group_name' => 'Liabilities', 'sub_group_name' => 'Loans (Liability)', 'sub_sub_group_name' => 'Secure Loans', 'default_balance_type' => 'cr', 'is_global' => '0', 'parent_name' => 'Loans (Liability)'],
    //         ['sorting_number' => '21', 'branch_id' => $branchId, 'name' => 'Unsecure Loans', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '0', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '1', 'is_parent_sub_sub_group' => '1', 'main_group_number' => '2', 'sub_group_number' => '8', 'sub_sub_group_number' => '13', 'main_group_name' => 'Liabilities', 'sub_group_name' => 'Loans (Liability)', 'sub_sub_group_name' => 'Unsecure Loans', 'default_balance_type' => 'cr', 'is_global' => '0', 'parent_name' => 'Loans (Liability)'],
    //         ['sorting_number' => '22', 'branch_id' => $branchId, 'name' => 'Suspense A/c', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '1', 'is_parent_sub_group' => '1', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '2', 'sub_group_number' => '9', 'sub_sub_group_number' => null, 'main_group_name' => 'Liabilities', 'sub_group_name' => 'Suspense', 'sub_sub_group_name' => null, 'default_balance_type' => 'cr', 'is_global' => '0', 'parent_name' => 'Liabilities'],
    //         ['sorting_number' => '23', 'branch_id' => $branchId, 'name' => 'Expenses', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '1', 'is_sub_group' => '0', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '3', 'sub_group_number' => null, 'sub_sub_group_number' => null, 'main_group_name' => 'Expenses', 'sub_group_name' => null, 'sub_sub_group_name' => null, 'default_balance_type' => 'dr', 'is_global' => '0', 'parent_name' => null],
    //         ['sorting_number' => '24', 'branch_id' => $branchId, 'name' => 'Direct Expenses', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '1', 'is_parent_sub_group' => '1', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '3', 'sub_group_number' => '10', 'sub_sub_group_number' => null, 'main_group_name' => 'Expenses', 'sub_group_name' => 'Direct Expenses', 'sub_sub_group_name' => null, 'default_balance_type' => 'dr', 'is_global' => '0', 'parent_name' => 'Expenses'],
    //         ['sorting_number' => '25', 'branch_id' => $branchId, 'name' => 'Indirect Expenses', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '1', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '3', 'sub_group_number' => '11', 'sub_sub_group_number' => null, 'main_group_name' => 'Expenses', 'sub_group_name' => 'Indirect Expenses', 'sub_sub_group_name' => 'dr', 'default_balance_type' => null, 'is_global' => '0', 'parent_name' => 'Expenses'],
    //         ['sorting_number' => '26', 'branch_id' => $branchId, 'name' => 'Purchase Accounts', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '1', 'is_parent_sub_group' => '1', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '3', 'sub_group_number' => '12', 'sub_sub_group_number' => null, 'main_group_name' => 'Expenses', 'sub_group_name' => 'Purchase Accounts', 'sub_sub_group_name' => 'dr', 'default_balance_type' => null, 'is_global' => '0', 'parent_name' => 'Expenses'],
    //         ['sorting_number' => '27', 'branch_id' => $branchId, 'name' => 'Incomes', 'is_reserved' => '0', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '1', 'is_sub_group' => '0', 'is_parent_sub_group' => '0', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '4', 'sub_group_number' => null, 'sub_sub_group_number' => null, 'main_group_name' => 'Incomes', 'sub_group_name' => null, 'sub_sub_group_name' => 'cr', 'default_balance_type' => null, 'is_global' => '0', 'parent_name' => null],
    //         ['sorting_number' => '28', 'branch_id' => $branchId, 'name' => 'Direct Incomes', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '1', 'is_parent_sub_group' => '1', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '4', 'sub_group_number' => '13', 'sub_sub_group_number' => null, 'main_group_name' => 'Incomes', 'sub_group_name' => 'Direct Incomes', 'sub_sub_group_name' => 'cr', 'default_balance_type' => null, 'is_global' => '0', 'parent_name' => 'Incomes'],
    //         ['sorting_number' => '29', 'branch_id' => $branchId, 'name' => 'Indirect Incomes', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '1', 'is_parent_sub_group' => '1', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '4', 'sub_group_number' => '14', 'sub_sub_group_number' => null, 'main_group_name' => 'Incomes', 'sub_group_name' => 'Indirect Incomes', 'sub_sub_group_name' => 'cr', 'default_balance_type' => null, 'is_global' => '0', 'parent_name' => 'Incomes'],
    //         ['sorting_number' => '30', 'branch_id' => $branchId, 'name' => 'Sales Accounts', 'is_reserved' => '1', 'is_allowed_bank_details' => '0', 'is_bank_or_cash_ac' => '0', 'is_fixed_tax_calculator' => '0', 'is_default_tax_calculator' => '0', 'is_main_group' => '0', 'is_sub_group' => '1', 'is_parent_sub_group' => '1', 'is_sub_sub_group' => '0', 'is_parent_sub_sub_group' => '0', 'main_group_number' => '4', 'sub_group_number' => '15', 'sub_sub_group_number' => null, 'main_group_name' => 'Incomes', 'sub_group_name' => 'Sales Accounts', 'sub_sub_group_name' => 'cr', 'default_balance_type' => null, 'is_global' => '0', 'parent_name' => 'Incomes'],
    //     ];

    //     array_walk($account_groups, function (&$v) {
    //         unset($v['id']);
    //     });

    //     foreach ($accountGroups as $group) {

    //         $addAccountGroup = new AccountGroup();
    //         $addAccountGroup->sorting_number = $group['sorting_number'];
    //         $addAccountGroup->branch_id = $group['branch_id'];
    //         $addAccountGroup->name = $group['name'];
    //         $addAccountGroup->is_reserved = $group['is_reserved'];
    //         $addAccountGroup->is_allowed_bank_details = $group['is_allowed_bank_details'];
    //         $addAccountGroup->is_bank_or_cash_ac = $group['is_bank_or_cash_ac'];
    //         $addAccountGroup->is_fixed_tax_calculator = $group['is_fixed_tax_calculator'];
    //         $addAccountGroup->is_default_tax_calculator = $group['is_default_tax_calculator'];
    //         $addAccountGroup->is_main_group = $group['is_main_group'];
    //         $addAccountGroup->is_sub_group = $group['is_sub_group'];
    //         $addAccountGroup->is_parent_sub_group = $group['is_parent_sub_group'];
    //         $addAccountGroup->is_sub_sub_group = $group['is_sub_sub_group'];
    //         $addAccountGroup->is_parent_sub_sub_group = $group['is_parent_sub_sub_group'];
    //         $addAccountGroup->main_group_number = $group['main_group_number'];
    //         $addAccountGroup->sub_group_number = $group['sub_group_number'];
    //         $addAccountGroup->sub_sub_group_number = $group['sub_sub_group_number'];
    //         $addAccountGroup->main_group_name = $group['main_group_name'];
    //         $addAccountGroup->sub_group_name = $group['sub_group_name'];
    //         $addAccountGroup->sub_sub_group_name = $group['sub_sub_group_name'];
    //         $addAccountGroup->default_balance_type = $group['default_balance_type'];
    //         $addAccountGroup->is_global = $group['is_global'];
    //         $addAccountGroup->save();

    //         if ($group['parent_name']) {

    //             $parentGroup = DB::table('account_groups')->where('account_groups.name', $group['parent_name'])
    //                 ->where('account_groups.branch_id', $group['branch_id'])->first(['id']);

    //             $addAccountGroup->parent_group_id = $parentGroup->id;
    //             $addAccountGroup->save();
    //         }
    //     }
    // }

    public function addBranchDefaultAccounts(int $branchId): void
    {
        $cashInHand = AccountGroup::where('sub_sub_group_number', 2)->first();
        $directExpenseGroup = AccountGroup::where('sub_group_number', 10)->first();
        $directIncomeGroup = AccountGroup::where('sub_group_number', 13)->first();
        $salesAccountGroup = AccountGroup::where('sub_group_number', 15)->first();
        $purchaseAccountGroup = AccountGroup::where('sub_group_number', 12)->first();
        $accountReceivablesAccountGroup = AccountGroup::where('sub_group_number', 6)->first();
        $suspenseAccountGroup = AccountGroup::where('sub_group_number', 9)->first();
        // $capitalAccountGroup = AccountGroup::where('sub_group_number', 6)->where('branch_id', $branchId)->first();
        // $dutiesAndTaxAccountGroup = AccountGroup::where('sub_sub_group_number', 8)->where('branch_id', $branchId)->first();

        $accounts = [
            ['account_group_id' => $cashInHand->id, 'name' => 'Cash', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'debit', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => '1', 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-04 17:33:01', 'updated_at' => '2023-08-04 17:33:01', 'branch_id' => $branchId],
            ['account_group_id' => $salesAccountGroup->id, 'name' => 'Sales Ledger Account', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => '1', 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-06 12:02:13', 'updated_at' => '2023-08-06 12:02:13', 'branch_id' => $branchId],
            // ['account_group_id' => $dutiesAndTaxAccountGroup->id, 'name' => 'Tax@5%', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '5.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-06 16:59:55', 'updated_at' => '2023-08-06 16:59:55', 'branch_id' => $branchId],
            // ['account_group_id' => $dutiesAndTaxAccountGroup->id, 'name' => 'Tax@8%', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '8.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-06 17:00:18', 'updated_at' => '2023-08-06 17:00:18', 'branch_id' => $branchId],
            ['account_group_id' => $purchaseAccountGroup->id, 'name' => 'Purchase Ledger Account', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:09:48', 'updated_at' => '2023-08-08 18:09:48', 'branch_id' => $branchId],
            ['account_group_id' => $directExpenseGroup->id, 'name' => 'Net Bill', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:10:36', 'updated_at' => '2023-08-08 18:10:36', 'branch_id' => $branchId],
            ['account_group_id' => $directExpenseGroup->id, 'name' => 'Electricity Bill', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:10:53', 'updated_at' => '2023-08-08 18:10:53', 'branch_id' => $branchId],
            ['account_group_id' => $directExpenseGroup->id, 'name' => 'Snacks Bill', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:11:16', 'updated_at' => '2023-08-08 18:11:16', 'branch_id' => $branchId],
            ['account_group_id' => $directExpenseGroup->id, 'name' => 'Roll Pages', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:11:59', 'updated_at' => '2023-08-08 18:11:59', 'branch_id' => $branchId],
            ['account_group_id' => $directIncomeGroup->id, 'name' => 'Sale Damage Goods', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:12:33', 'updated_at' => '2023-08-08 18:12:33', 'branch_id' => $branchId],
            ['account_group_id' => $directExpenseGroup->id, 'name' => 'Lost/Damage Stock', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:13:13', 'updated_at' => '2023-08-08 18:13:13', 'branch_id' => $branchId],
            ['account_group_id' => $accountReceivablesAccountGroup->id, 'name' => 'Walk-In-Customer', 'phone' => 0, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:13:13', 'updated_at' => '2023-08-08 18:13:13', 'branch_id' => $branchId],
            ['account_group_id' => $suspenseAccountGroup->id, 'name' => 'Profit Loss Account', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => '1', 'created_at' => '2023-08-08 18:13:57', 'updated_at' => '2023-08-08 18:13:57', 'branch_id' => $branchId],
            // ['account_group_id' => $capitalAccountGroup->id, 'name' => 'Capital Account', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => '1', 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:14:40', 'updated_at' => '2023-08-08 18:14:40', 'branch_id' => $branchId],
        ];

        Account::insert($accounts);
    }

    public function branches(array $with = null)
    {
        $query = Branch::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function singleBranch(?int $id, array $with = null)
    {
        $query = Branch::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function addBranchOpeningUser(object $request, int $branchId): void
    {
        $addUser = new User();
        $addUser->name = $request->first_name;
        $addUser->last_name = $request->last_name;
        $addUser->phone = $request->user_phone;
        $addUser->branch_id = $branchId;

        $addUser->allow_login = 1;
        $addUser->username = $request->username;
        $addUser->password = Hash::make($request->password);

        // Assign role
        $addUser->role_type = 3;
        $addUser->is_belonging_an_area = 1;

        $roleId = $request->role_id ?? 3;
        $role = Role::find($roleId);
        $addUser->assignRole($role->name);

        $addUser->branch_id = $branch_id;

        $addUser->save();
    }

    public function branchName(object $transObject = null): string
    {
        $generalSettings = config('generalSettings');
        $branchName = $generalSettings['business__shop_name'];

        if (isset($transObject)) {

            if ($transObject?->branch?->branch) {

                if ($transObject?->branch->parentBranch) {

                    $branchName = $transObject?->branch?->parentBranch?->name . '(' . $transObject?->branch?->parentBranch?->area_name . ')';
                } else {

                    $branchName = $transObject?->branch?->name . '(' . $transObject?->branch?->area_name . ')';
                }
            }
        } else {

            if (auth()->user()?->branch) {

                if (auth()->user()?->branch->parentBranch) {

                    $branchName = auth()->user()?->branch->parentBranch?->name . '(' . auth()->user()?->branch->parentBranch?->area_name . ')';
                } else {

                    $branchName = auth()->user()?->branch?->name . '(' . auth()->user()?->branch?->area_name . ')';
                }
            }
        }

        return $branchName;
    }

    public function restrictions(): array
    {
        $generalSettings = config('generalSettings');
        $branchLimit = $generalSettings['addons__branch_limit'];

        $branchCount = DB::table('branches')->count();

        if ($branchLimit <= $branchCount) {

            return ['pass' => false, 'msg' => __("Shop limit is ${branchLimit}")];
        }

        return ['pass' => true];
    }
}
