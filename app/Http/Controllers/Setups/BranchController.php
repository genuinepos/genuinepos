<?php

namespace App\Http\Controllers\Setups;

use App\Enums\BranchType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\GeneralSettingService;
use App\Services\Setups\CurrencyService;
use App\Services\Setups\TimezoneService;
use App\Services\Setups\CashCounterService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\InvoiceLayoutService;

class BranchController extends Controller
{
    public function __construct(
        private BranchService $branchService,
        private InvoiceLayoutService $invoiceLayoutService,
        private CashCounterService $cashCounterService,
        private CurrencyService $currencyService,
        private TimezoneService $timezoneService,
        private BranchSettingService $branchSettingService,
        private GeneralSettingService $generalSettingService,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('branch')) {

            abort(403, 'Access Forbidden.');
        }

        $generalSettings = config('generalSettings');

        if ($request->ajax()) {

            return $this->branchService->branchListTable();
        }

        return view('setups.branches.index');
    }

    public function create()
    {
        if (!auth()->user()->can('branch')) {
            abort(403, 'Access Forbidden.');
        }

        $currencies = $this->currencyService->currencies();
        $timezones = $this->timezoneService->all();

        $roles = DB::table('roles')->select('id', 'name')->get();
        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return view('setups.branches.ajax_view.create', compact('branches', 'roles', 'currencies', 'timezones'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('branch')) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'area_name' => 'required',
            'branch_code' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zip_code' => 'required',
            'logo' => 'sometimes|image|max:2048',
        ]);

        if (BranchType::DifferentShop->value == $request->branch_type) {

            $this->validate($request, [
                'name' => 'required',
            ]);
        }

        if ($request->add_initial_user) {

            $this->validate($request, [
                'first_name' => 'required',
                'user_phone' => 'required',
                'username' => 'required|unique:users,username',
                'password' => 'required|confirmed',
            ]);
        }

        try {
            DB::beginTransaction();

            $restrictions = $this->branchService->restrictions();

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $addBranch = $this->branchService->addBranch($request);

            // $this->branchService->addBranchDefaultAccountGroups($addBranch->id);

            $this->branchService->addBranchDefaultAccounts($addBranch->id);

            $this->cashCounterService->addCashCounter(branchId: $addBranch->id, cashCounterName: 'Cash Counter 1', shortName: 'CN1');

            $defaultBranchName = $addBranch?->parentBranch ? $addBranch?->parentBranch . '(' . $addBranch?->area_name . ') Default Invoice Layout' :  $addBranch?->name . '(' . $addBranch?->area_name . ') Default Invoice Layout';

            $addInvoiceLayout = $this->invoiceLayoutService->addInvoiceLayout(request: $request, branchId: $addBranch->id, defaultName: $defaultBranchName);

            $this->branchSettingService->addBranchSettings(branchId: $addBranch->id, parentBranchId: $addBranch->parent_branch_id, defaultInvoiceLayoutId: $addInvoiceLayout->id, branchService: $this->branchService, request: $request);

            if ($request->add_opening_user) {

                $this->branchService->addBranchOpeningUser($request, $addBranch->id);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Shop created successfully'));
    }

    public function edit($id)
    {
        if (!auth()->user()->can('branch')) {
            abort(403, 'Access Forbidden.');
        }

        $currencies = $this->currencyService->currencies();
        $timezones = $this->timezoneService->all();

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();
        $branch = $this->branchService->singleBranch($id);

        $branchSettings = $this->generalSettingService->generalSettings(branchId: $id, keys: [
            'business_or_shop__date_format',
            'business_or_shop__time_forma',
            'business_or_shop__timezone',
            'business_or_shop__currency_id',
            'business_or_shop__currency_symbol',
            'business_or_shop__account_start_date',
            'business_or_shop__financial_year_start_month',
            'business_or_shop__stock_accounting_method',
        ]);

        return view('setups.branches.ajax_view.edit', compact('branches', 'branch', 'currencies', 'timezones', 'branchSettings'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('branch')) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'area_name' => 'required',
            'branch_code' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zip_code' => 'required',
            'logo' => 'sometimes|image|max:2048',
        ]);

        if (BranchType::DifferentShop->value == $request->branch_type) {

            $this->validate($request, [
                'name' => 'required',
            ]);
        }

        try {
            DB::beginTransaction();

            $this->branchService->updateBranch(id: $id, request: $request);

            $settings = [
                'business_or_shop__date_format' => $request->date_format,
                'business_or_shop__time_forma' => $request->time_format,
                'business_or_shop__timezone' => $request->timezone,
                'business_or_shop__currency_id' => $request->currency_id,
                'business_or_shop__currency_symbol' => $request->currency_symbol,
            ];

            if ($request->branch_type == BranchType::DifferentShop->value) {
                $settings['business_or_shop__account_start_date'] = $request->account_start_date;
                $settings['business_or_shop__financial_year_start_month'] = $request->financial_year_start_month;
                $settings['business_or_shop__stock_accounting_method'] = $request->stock_accounting_method;
            }

            $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Shop updated successfully'));
    }

    public function delete(Request $request, $id)
    {
        $deleteBranch = $this->branchService->deleteBranch(id: $id);

        if ($deleteBranch['pass'] == false) {

            return response()->json(['errorMsg' => $deleteBranch['msg']]);
        }

        return response()->json(__('Shop deleted deleted successfully'));
    }

    public function parentWithChildBranches($id)
    {
        return $this->branchService->branches(with: ['childBranches'])->where('id', $id)->first();
    }
}
