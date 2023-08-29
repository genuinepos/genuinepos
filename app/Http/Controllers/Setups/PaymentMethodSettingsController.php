<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountGroupService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Setups\PaymentMethodSettingsService;

class PaymentMethodSettingsController extends Controller
{
    public function __construct(
        private PaymentMethodSettingsService $paymentMethodSettingsService,
        private AccountGroupService $accountGroupService,
        private PaymentMethodService $paymentMethodService,
        private AccountFilterService $accountFilterService,
    ) {
    }

    public function index()
    {
        $accountGroups = $this->accountGroupService->accountGroups(with: [
            'accounts:id,name,account_number,account_group_id,bank_id,bank_branch',
            'accounts.bank:id,name',
            'accounts.group:id,sorting_number,sub_sub_group_number',
            'accounts.bankAccessBranch'
        ])->whereIn('sub_sub_group_number', [1, 2, 11])->where('branch_id', auth()->user()->branch_id)->orWhere('is_global', 1)->get();

        $accounts = $this->accountFilterService->filterCashBankAccounts($accountGroups);

        $paymentMethods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        return view('setups.payment_methods.settings.index', compact('accounts', 'paymentMethods'));
    }

    public function update(Request $request)
    {
        try {

            DB::beginTransaction();

            $addOrUpdatePaymentMethodSettings = $this->paymentMethodSettingsService->addOrUpdatePaymentMethodSettings(request: $request);

            if ($addOrUpdatePaymentMethodSettings['pass'] == false) {

                return response()->json(['errorMsg' => $addOrUpdatePaymentMethodSettings['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Payment method settings is updated successfully."));
    }
}
