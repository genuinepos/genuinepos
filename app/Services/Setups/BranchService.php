<?php

namespace App\Services\Setups;

use App\Models\Role;
use App\Models\User;
use App\Enums\BranchType;
use App\Enums\BooleanType;
use App\Models\Setups\Branch;
use Illuminate\Validation\Rule;
use App\Models\Accounts\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Accounts\AccountGroup;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class BranchService
{
    public function branchListTable()
    {
        $generalSettings = config('generalSettings');
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
            'branches.address',
            'branches.expire_date',
            'parentBranch.name as parent_branch_name',
            'parentBranch.logo as parent_branch_logo',
        )->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id');

        return DataTables::of($branches)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __("Action") . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item" id="edit" href="' . route('branches.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                $html .= '<a class="dropdown-item" id="delete" href="' . route('branches.delete', [$row->id]) . '">' . __('Delete') . '</a>';
                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('branchName', function ($row) {

                if ($row->branch_type == BranchType::DifferentShop->value) {

                    return '</span> <span class="fw-bold">' . $row->branch_name . ' (' . $row->area_name . ')' . '</span>';
                } else {

                    return '<span class="fas fa-long-arrow-alt-right text-success" style="font-size:15px;"></span> <span class="fw-bold">' . $row->parent_branch_name . ' (' . $row->area_name . ')' . '</span>';
                }
            })

            ->editColumn('logo', function ($row) {

                $photo = asset('images/general_default.png');

                if (isset($row->parent_branch_logo)) {

                    $photo = asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . $row->parent_branch_logo);
                } elseif (isset($row->logo)) {

                    $photo = asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . $row->logo);
                }

                return '<img loading="lazy" class="rounded" style="height:40px; width:60px; padding:2px 0px;" src="' . $photo . '">';
            })

            ->editColumn('address', function ($row) {

                if ($row->address) {

                    $row->address;
                } else {

                    return $row->city . ', ' . $row->state . ', ' . $row->zip_code . ', ' . $row->country;
                }
            })

            ->editColumn('expire_date', function ($row) use ($generalSettings) {

                if (isset($row->expire_date)) {

                    $__date_format = $generalSettings['business_or_shop__date_format'];

                    $expireDate = date($__date_format, strtotime($row->expire_date));

                    $expireDateText = date('Y-m-d') > date('Y-m-d', strtotime($expireDate)) ? ' | <span class="text-danger fw-bold">' . __('Expired') . '</span>' : ' | <span class="text-success fw-bold">' . __('Active') . '</span>';

                    return '<span class="text-danger">' . $expireDate . '</span>' . $expireDateText;
                } else if ($generalSettings['subscription']->is_trial_plan) {

                    $planStartDate = $generalSettings['subscription']->trial_start_date;
                    $trialDays = $generalSettings['subscription']->trial_days;
                    $startDate = new \DateTime($planStartDate);
                    $lastDate = $startDate->modify('+ ' . $trialDays . ' days');
                    $expireDate = $lastDate->format('Y-m-d');
                    $dateFormat = $generalSettings['business_or_shop__date_format'];
                    return date($dateFormat, strtotime($expireDate));
                }
            })

            ->rawColumns(['branchName', 'expire_date', 'logo', 'address', 'action'])
            ->make(true);
    }

    public function addBranch($request): object
    {
        $generalSettings = config('generalSettings');
        $subscription = $generalSettings['subscription'];

        $shopHistory = (new \App\Services\Setups\ShopExpireDateHistoryService)->shopExpireDateHistoryByAnyCondition()->where('is_created', BooleanType::False->value)->first();

        $addBranch = new Branch();
        $addBranch->branch_type = $request->branch_type;
        $addBranch->name = $request->branch_type == BranchType::DifferentShop->value ? $request->name : null;
        $addBranch->area_name = $request->area_name;
        $addBranch->parent_branch_id = $request->branch_type == BranchType::ChainShop->value ? $request->parent_branch_id : null;
        $addBranch->branch_code = $request->branch_code;
        $addBranch->phone = $request->phone;
        $addBranch->city = $request->city;
        $addBranch->state = $request->state;
        $addBranch->zip_code = $request->zip_code;
        $addBranch->country = $request->country;
        $addBranch->address = $request->address;
        $addBranch->alternate_phone_number = $request->alternate_phone_number;
        $addBranch->bin = $request->bin;
        $addBranch->tin = $request->tin;
        $addBranch->email = $request->email;
        $addBranch->website = $request->website;
        $addBranch->expire_date = $subscription->is_trial_plan == BooleanType::False->value ? $shopHistory?->expire_date : null;
        $addBranch->shop_expire_date_history_id = $subscription->is_trial_plan == BooleanType::False->value ? $shopHistory?->id : null;

        $branchLogoName = '';
        if ($request->hasFile('logo') || $request->hasFile('branch_logo')) {

            $dir = public_path('uploads/' . tenant('id') . '/' . 'branch_logo/');

            if (!\File::isDirectory($dir)) {

                \File::makeDirectory($dir, 493, true);
            }

            $logo = $request->file('logo');

            $branchLogo = isset($logo) ? $logo : $request->file('branch_logo');
            if (isset($branchLogo)) {

                $branchLogoName = uniqid() . '-' . '.' . $branchLogo->getClientOriginalExtension();
                $branchLogo->move($dir, $branchLogoName);
                $addBranch->logo = $branchLogoName;
            }
        }

        $addBranch->save();

        if ($subscription->is_trial_plan == BooleanType::False->value) {

            (new \App\Services\Setups\ShopExpireDateHistoryService)->updateShopExpireDateHistory(id: $shopHistory->id, isCreated: BooleanType::True->value);
        }

        $this->forgetCache();

        return $this->singleBranch(id: $addBranch->id, with: ['parentBranch']);
    }

    public function updateBranch(int $id, object $request): void
    {
        $updateBranch = $this->singleBranch($id);

        $updateBranch->name = $request->branch_type;
        $updateBranch->name = $request->branch_type == BranchType::DifferentShop->value ? $request->name : null;
        $updateBranch->area_name = $request->area_name;
        $updateBranch->parent_branch_id = $request->branch_type == BranchType::ChainShop->value ? $request->parent_branch_id : null;
        $updateBranch->branch_code = $request->branch_code;
        $updateBranch->phone = $request->phone;
        $updateBranch->city = $request->city;
        $updateBranch->state = $request->state;
        $updateBranch->zip_code = $request->zip_code;
        $updateBranch->country = $request->country;
        $updateBranch->address = $request->address;
        $updateBranch->alternate_phone_number = $request->alternate_phone_number;
        $updateBranch->bin = $request->bin;
        $updateBranch->tin = $request->tin;
        $updateBranch->email = $request->email;
        $updateBranch->website = $request->website;

        if ($request->hasFile('logo')) {

            $dir = public_path('uploads/' . tenant('id') . '/' . 'branch_logo/');

            if (isset($updateBranch->logo) && file_exists($dir . $updateBranch->logo)) {

                unlink($dir . $updateBranch->logo);
            }

            if (!\File::isDirectory($dir)) {

                \File::makeDirectory($dir, 493, true);
            }

            $branchLogo = $request->file('logo');
            $branchLogoName = uniqid() . '-' . '.' . $branchLogo->getClientOriginalExtension();
            $branchLogo->move($dir, $branchLogoName);
            $updateBranch->logo = $branchLogoName;
        }

        $updateBranch->save();

        $this->forgetCache();
    }

    public function deleteBranch(int $id): array
    {
        $deleteBranch = $this->singleBranch(id: $id, with: ['sales', 'purchases', 'childBranches', 'shopExpireDateHistory']);

        if (count($deleteBranch->childBranches) > 0) {

            return ['pass' => false, 'msg' => __('Shop can not be deleted. This shop has one or more chain shop.')];
        }

        if (count($deleteBranch->sales) > 0) {

            return ['pass' => false, 'msg' => __('Shop can not be deleted. This shop has one or more sales.')];
        }

        if (count($deleteBranch->purchases) > 0) {

            return ['pass' => false, 'msg' => __('Shop can not be deleted. This shop has one or more purchases.')];
        }

        $dir = public_path('uploads/' . tenant('id') . '/' . 'branch_logo/');
        if (isset($deleteBranch->logo) && file_exists($dir . $deleteBranch->logo)) {

            unlink($dir . $deleteBranch->logo);
        }

        if ($deleteBranch?->shopExpireDateHistory) {

            (new \App\Services\Setups\ShopExpireDateHistoryService)->updateShopExpireDateHistory(id: $deleteBranch?->shopExpireDateHistory?->id, isCreated: BooleanType::False->value);
        }

        $deleteBranch->delete();

        $this->forgetCache();

        return ['pass' => true];
    }

    public function deleteLogo(int $id): void
    {
        $deleteLogo = $this->singleBranch(id: $id);

        $dir = public_path('uploads/' . tenant('id') . '/' . 'branch_logo/');
        if (isset($deleteLogo->logo) && file_exists($dir . $deleteLogo->logo)) {

            unlink($dir . $deleteLogo->logo);
        }

        $deleteLogo->logo = null;
        $deleteLogo->save();

        $this->forgetCache();
    }

    public function addBranchDefaultAccounts(int $branchId, ?int $parentBranchId = null): void
    {
        $cashInHand = DB::table('account_groups')->where('sub_sub_group_number', 2)->first();
        $directExpenseGroup = DB::table('account_groups')->where('sub_group_number', 10)->first();
        $directIncomeGroup = DB::table('account_groups')->where('sub_group_number', 13)->first();
        $salesAccountGroup = DB::table('account_groups')->where('sub_group_number', 15)->first();
        $purchaseAccountGroup = DB::table('account_groups')->where('sub_group_number', 12)->first();
        $accountReceivablesAccountGroup = DB::table('account_groups')->where('sub_sub_group_number', 6)->first();
        $suspenseAccountGroup = DB::table('account_groups')->where('sub_group_number', 9)->first();
        // $capitalAccountGroup = DB::table('account_groups')->where('sub_group_number', 6)->first();
        // $dutiesAndTaxAccountGroup = AccountGroup::where('sub_sub_group_number', 8)->where('branch_id', $branchId)->first();

        $accounts = [
            ['account_group_id' => $cashInHand->id, 'is_walk_in_customer' => 0, 'name' => 'Cash', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => '1', 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-04 17:33:01', 'updated_at' => '2023-08-04 17:33:01', 'branch_id' => $branchId],
            ['account_group_id' => $salesAccountGroup->id, 'is_walk_in_customer' => 0, 'name' => 'Sales Ledger Account', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => '1', 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-06 12:02:13', 'updated_at' => '2023-08-06 12:02:13', 'branch_id' => $branchId],
            // ['account_group_id' => $dutiesAndTaxAccountGroup->id, 'name' => 'Tax@5%', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '5.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-06 16:59:55', 'updated_at' => '2023-08-06 16:59:55', 'branch_id' => $branchId],
            // ['account_group_id' => $dutiesAndTaxAccountGroup->id, 'name' => 'Tax@8%', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '8.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-06 17:00:18', 'updated_at' => '2023-08-06 17:00:18', 'branch_id' => $branchId],
            ['account_group_id' => $purchaseAccountGroup->id, 'is_walk_in_customer' => 0, 'name' => 'Purchase Ledger Account', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:09:48', 'updated_at' => '2023-08-08 18:09:48', 'branch_id' => $branchId],
            ['account_group_id' => $directExpenseGroup->id, 'is_walk_in_customer' => 0, 'name' => 'Net Bill', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:10:36', 'updated_at' => '2023-08-08 18:10:36', 'branch_id' => $branchId],
            ['account_group_id' => $directExpenseGroup->id, 'is_walk_in_customer' => 0, 'name' => 'Electricity Bill', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:10:53', 'updated_at' => '2023-08-08 18:10:53', 'branch_id' => $branchId],
            ['account_group_id' => $directExpenseGroup->id, 'is_walk_in_customer' => 0, 'name' => 'Snacks Bill', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:11:16', 'updated_at' => '2023-08-08 18:11:16', 'branch_id' => $branchId],
            ['account_group_id' => $directExpenseGroup->id, 'is_walk_in_customer' => 0, 'name' => 'Roll Pages', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:11:59', 'updated_at' => '2023-08-08 18:11:59', 'branch_id' => $branchId],
            ['account_group_id' => $directIncomeGroup->id, 'is_walk_in_customer' => 0, 'name' => 'Sale Damage Goods', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:12:33', 'updated_at' => '2023-08-08 18:12:33', 'branch_id' => $branchId],
            ['account_group_id' => $directExpenseGroup->id, 'is_walk_in_customer' => 0, 'name' => 'Lost/Damage Stock', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:13:13', 'updated_at' => '2023-08-08 18:13:13', 'branch_id' => $branchId],
            // ['account_group_id' => $accountReceivablesAccountGroup->id, 'is_walk_in_customer' => 1, 'name' => 'Walk-In-Customer', 'phone' => 0, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:13:13', 'updated_at' => '2023-08-08 18:13:13', 'branch_id' => $branchId],
            // ['account_group_id' => $suspenseAccountGroup->id, 'is_walk_in_customer' => 0, 'name' => 'Profit Loss Account', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => '1', 'created_at' => '2023-08-08 18:13:57', 'updated_at' => '2023-08-08 18:13:57', 'branch_id' => $branchId],
            // ['account_group_id' => $capitalAccountGroup->id, 'name' => 'Capital Account', 'phone' => null, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => '1', 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:14:40', 'updated_at' => '2023-08-08 18:14:40', 'branch_id' => $branchId],
        ];

        if (!isset($parentBranchId)) {

            $accounts[] = [
                'account_group_id' => $accountReceivablesAccountGroup->id, 'is_walk_in_customer' => 1, 'name' => 'Walk-In-Customer', 'phone' => 0, 'contact_id' => null, 'address' => null, 'account_number' => null, 'bank_id' => null, 'bank_branch' => null, 'bank_address' => null, 'tax_percent' => '0.00', 'bank_code' => null, 'swift_code' => null, 'opening_balance' => '0.00', 'opening_balance_type' => 'dr', 'remark' => null, 'status' => '1', 'created_by_id' => '1', 'is_fixed' => null, 'is_main_capital_account' => null, 'is_main_pl_account' => null, 'created_at' => '2023-08-08 18:13:13', 'updated_at' => '2023-08-08 18:13:13', 'branch_id' => $branchId
            ];
        }

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

    public function switchableBranches()
    {
        $branchesMethod = $this->branches(with: ['parentBranch']);
        $tenantId = tenant('id');
        $cacheKey = "{$tenantId}_switchableBranches";
        return Cache::rememberForever($cacheKey, function () use ($branchesMethod) {

            return $branchesMethod->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();
        });
    }

    public function singleBranch(?int $id, array $with = null)
    {
        $query = Branch::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function addBranchInitialUser(object $request, int $branchId): void
    {
        $addUser = new User();
        $addUser->name = $request->user_first_name;
        $addUser->last_name = $request->user_last_name;
        $addUser->phone = $request->user_phone;
        $addUser->branch_id = $branchId;

        $addUser->allow_login = BooleanType::True->value;
        $addUser->email = $request->user_email;
        $addUser->username = $request->user_username;
        $addUser->password = Hash::make($request->password);

        // Assign role
        $addUser->role_type = 3;
        $addUser->is_belonging_an_area = BooleanType::True->value;

        $roleId = $request->role_id ?? 3;
        $role = Role::find($roleId);
        $addUser->assignRole($role->name);

        $addUser->branch_id = $branchId;

        $addUser->save();
    }

    public function branchName(object $transObject = null): string
    {
        $generalSettings = config('generalSettings');
        $branchName = $generalSettings['business_or_shop__business_name'];

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
        $branchLimit = $generalSettings['subscription']->current_shop_count;

        $branchCount = DB::table('branches')->count();

        if ($branchLimit == $branchCount) {

            return ['pass' => false, 'msg' => __("Shop limit is ${branchLimit}")];
        }

        return ['pass' => true];
    }

    public function updateRestrictions(): array
    {
        $generalSettings = config('generalSettings');
        $branchLimit = $generalSettings['subscription']->current_shop_count;

        $branchCount = DB::table('branches')->count();

        if ($branchLimit <= $branchCount) {

            return ['pass' => false, 'msg' => __("Shop limit is ${branchLimit}")];
        }

        return ['pass' => true];
    }

    private function forgetCache(): void
    {
        $tenantId = tenant('id');
        $cacheKey = "{$tenantId}_switchableBranches";
        Cache::forget($cacheKey);
    }
}
