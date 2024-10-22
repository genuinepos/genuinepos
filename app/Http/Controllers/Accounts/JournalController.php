<?php

namespace App\Http\Controllers\Accounts;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Models\Accounts\Account;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\AccountLedgerVoucherType;
use App\Services\Setups\PaymentMethodService;

class JournalController extends Controller
{
    public function __construct(private PaymentMethodService $paymentMethodService) {}

    public function create()
    {
        $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        return view('accounting.accounting_vouchers.journals.create', compact('methods'));
    }

    public function searchAccount(Request $request)
    {
        $replacedStr = str_replace('~', '/', $request->keyword);
        $replacedStr = str_replace('^^^', '#', $replacedStr);
        $__keyword = $request->keyword == 'NULL' ? '' : $replacedStr;
        $accounts = '';

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $filteredBranchId = '';
        if (auth()->user()->branch_id) {

            if (auth()->user()->branch_id == null) {

                $filteredBranchId = 'NULL';
            } else {

                $filteredBranchId = auth()->user()->branch_id;
            }
        } else {

            $filteredBranchId = auth()->user()->branch_id ? auth()->user()->branch_id : 'NULL';
        }

        // $userBranchId = auth()->user()->branch_id ? auth()->user()->branch_id : NULL;

        $generalSettings = config('generalSettings');

        $mainQuery = DB::table('accounts')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('bank_access_branches', function ($join) use ($filteredBranchId) {
                $__filteredBranchId = $filteredBranchId == 'NULL' ? null : $filteredBranchId;
                $join->on('accounts.id', '=', 'bank_access_branches.bank_account_id')
                    ->where('bank_access_branches.branch_id', '=', $__filteredBranchId);
            })->where('accounts.is_global', BooleanType::False->value)
            ->where('accounts.name', 'LIKE', '%' . $__keyword . '%');

        if (auth()->user()->branch_id == null) {

            $mainQuery->where('accounts.branch_id', null);
        }

        $branchId = $filteredBranchId;

        $mainQuery->where(function ($query) use ($branchId) {
            $__branchId = $branchId == 'NULL' ? null : $branchId;
            $query->where('accounts.branch_id', '=', $__branchId)
                ->orWhere('bank_access_branches.branch_id', '=', $__branchId);
        });

        $mainQuery->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('account_groups')
                ->whereRaw('account_groups.sub_sub_group_number = ?', [6])
                ->whereRaw('account_groups.id = accounts.account_group_id');
        });

        // Global Account
        $global = '';
        $globalQ = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->where('account_groups.is_global', 1)
            ->where('accounts.is_walk_in_customer', 0)
            ->where('accounts.name', 'LIKE', '%' . $__keyword . '%');

        $global = $globalQ->select(
            'accounts.id',
            'accounts.branch_id',
            'accounts.name',
            'accounts.account_number',
            'banks.name as b_name',
            'account_groups.default_balance_type',
            'account_groups.name as group_name',
            'account_groups.sub_sub_group_number',
        );

        $customerAccounts = '';
        $query = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->where('accounts.branch_id', $ownBranchIdOrParentBranchId)
            ->where('accounts.name', 'LIKE', '%' . $__keyword . '%');

        $customerAccounts = $query->select(
            'accounts.id',
            'accounts.branch_id',
            'accounts.name',
            'accounts.account_number',
            'banks.name as b_name',
            'account_groups.default_balance_type',
            'account_groups.name as group_name',
            'account_groups.sub_sub_group_number',
        );

        $accounts = $mainQuery->union($global)->union($customerAccounts)
            ->select(
                'accounts.id',
                'accounts.branch_id',
                'accounts.name',
                'accounts.account_number',
                'banks.name as b_name',
                'account_groups.default_balance_type',
                'account_groups.name as group_name',
                'account_groups.sub_sub_group_number',
            )
            ->orderBy('name', 'asc')->get();


        return $accounts;
    }

    public function store(Request $request)
    {
        $restrictions = $this->receiptService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $journalVoucherPrefix = $generalSettings['prefix__journal_voucher_prefix'] ? $generalSettings['prefix__journal_voucher_prefix'] : 'JN';

        // Add Accounting Voucher
        $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Journal->value, remarks: $request->remarks, reference: $request->reference, codeGenerator: $codeGenerator, voucherPrefix: $journalVoucherPrefix, debitTotal: $request->debit_total, creditTotal: $request->credit_total, totalAmount: $request->debit_total);

        $cashBankAccountId = $this->getCashBankAccountId($request);

        foreach ($request->account_ids as $index => $accountId) {

            $amountType = $request->amount_types[$index] == 'Dr' ? 'dr' : 'cr';
            $amountTypeFullStr = $request->amount_types[$index] == 'Dr' ? 'debit' : 'credit';

            // Add Accounting voucher Description
            $addAccountingVoucherDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $accountId, paymentMethodId: $request->payment_method_ids[$index], amountType: $amountType, amount: $request->received_amount, transactionNo: $request->transaction_nos[$index], chequeNo: $request->cheque_nos[$index], chequeSerialNo: $request->cheque_serial_nos[$index]);

            $cashBankAcId = $addAccountingVoucherDescription->is_cash_bank_ac == 0 ? $cashBankAccountId : null;

            if ($index == 0) {

                // Add Day Book entry for Journal
                $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Journal->value, date: $request->date, accountId: $addAccountingVoucherDescription->account_id, transId: $addAccountingVoucherDescription->id, amount: $addAccountingVoucherDescription->amount, amountType: $addAccountingVoucherDescription->amount_type);
            }

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Journal->value, date: $request->date, account_id: $addAccountingVoucherDescription->account_id, trans_id: $addAccountingVoucherDescription->id, amount: $addAccountingVoucherDescription->amount, amount_type: $amountTypeFullStr);

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->credit_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->debit_account_id);
        }


        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Journal->value, dataObj: $journal);
    }

    public function getCashBankAccountId(object $request)
    {
        $cashBankAccountId = null;
        foreach ($request->account_ids as $accountId) {

            $account = DB::table('accounts')->where('accounts.id', $accountId)
                ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
                ->select('accounts.id', 'account_groups.sub_sub_group_number')->first();

            if ($account->sub_sub_group_number == 1 || $account->sub_sub_group_number == 2 || $account->sub_sub_group_number == 11) {

                if (! isset($cashBankAccountId)) {

                    $cashBankAccountId = $account->id;
                }
            }

            if ($cashBankAccountId != null) {

                break;
            }
        }

        return $cashBankAccountId;
    }
}
