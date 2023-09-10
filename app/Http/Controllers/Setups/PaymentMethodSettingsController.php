<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Setups\PaymentMethodSettingsService;

class PaymentMethodSettingsController extends Controller
{
    public function __construct(
        private PaymentMethodSettingsService $paymentMethodSettingsService,
        private AccountService $accountService,
        private PaymentMethodService $paymentMethodService,
        private AccountFilterService $accountFilterService,
    ) {
    }

    public function index()
    {
        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
        ])
        
        ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
        ->where('branch_id', auth()->user()->branch_id)
        ->whereIn('account_groups.sub_sub_group_number', [2])
        ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
        ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
        ->get();

        $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

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
