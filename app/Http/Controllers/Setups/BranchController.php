<?php

namespace App\Http\Controllers\Setups;

use App\Enums\RoleType;
use App\Enums\BranchType;
use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\GeneralSettingService;
use App\Services\Setups\CurrencyService;
use App\Services\Setups\TimezoneService;
use Illuminate\Support\Facades\Redirect;
use App\Services\Setups\CashCounterService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\InvoiceLayoutService;
use App\Services\Setups\GenerateBranchCodeService;

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
        private GenerateBranchCodeService $generateBranchCodeService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        $generalSettings = config('generalSettings');

        abort_if(!auth()->user()->can('shops_create') && $generalSettings['subscription']->current_shop_count == 1);

        $currentCreatedBranchCount = $this->branchService->branches()->count();

        if ($request->ajax()) {

            return $this->branchService->branchListTable();
        }

        return view('setups.branches.index', compact('currentCreatedBranchCount'));
    }

    public function create()
    {
        abort_if(!auth()->user()->can('shops_create'), 403);

        $currencies = $this->currencyService->currencies();
        $timezones = $this->timezoneService->all();

        $branchCode = $this->generateBranchCodeService->branchCode();

        $roles = DB::table('roles')->select('id', 'name')->get();
        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return view('setups.branches.ajax_view.create', compact('branches', 'roles', 'currencies', 'timezones', 'branchCode'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('shops_create'), 403);

        $this->branchService->branchStoreValidation(request: $request);

        try {
            DB::beginTransaction();

            $restrictions = $this->branchService->restrictions();

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $addBranch = $this->branchService->addBranch($request);

            $this->branchService->addBranchDefaultAccounts($addBranch->id);

            $this->cashCounterService->addCashCounter(branchId: $addBranch->id, cashCounterName: 'Cash Counter 1', shortName: 'CN1');

            $defaultBranchName = $addBranch?->parentBranch ? strtoupper($addBranch?->parentBranch->name . '(' . $addBranch?->area_name . ')') . ' - Default Invoice Layout' :  strtoupper($addBranch?->name . '(' . $addBranch?->area_name . ')') . ' - Default Invoice Layout';

            $addInvoiceLayout = $this->invoiceLayoutService->addInvoiceLayout(request: $request, branchId: $addBranch->id, defaultName: $defaultBranchName);

            $this->branchSettingService->addBranchSettings(branchId: $addBranch->id, parentBranchId: $addBranch->parent_branch_id, defaultInvoiceLayoutId: $addInvoiceLayout->id, branchService: $this->branchService, request: $request);

            if ($request->add_initial_user == 1) {

                $this->branchService->addBranchInitialUser(request: $request, branchId: $addBranch->id);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Shop created successfully'));
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('shops_edit'), 403);

        $currencies = $this->currencyService->currencies();
        $timezones = $this->timezoneService->all();

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();
        $branch = $this->branchService->singleBranch(id: $id, with: ['parentBranch']);

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
        abort_if(!auth()->user()->can('shops_edit') && !auth()->user()->can('general_settings'), 403);

        $this->branchService->branchUpdateValidation(request: $request);

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
            } else {

                $this->branchSettingService->deleteUnusedBranchSettings(branchId: $id, keys: [
                    'business_or_shop__account_start_date',
                    'business_or_shop__financial_year_start_month',
                    'business_or_shop__stock_accounting_method',
                ]);
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
        abort_if(!auth()->user()->can('shops_delete'), 403);

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

    public function branchCode($parentBranchId = null)
    {
        return $this->generateBranchCodeService->branchCode(parentBranchId: $parentBranchId);
    }
}
