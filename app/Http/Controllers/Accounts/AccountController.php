<?php

namespace App\Http\Controllers\Accounts;

use App\Enums\ContactType;
use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountGroupService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\BankAccessBranchService;
use App\Services\Accounts\BankService;
use App\Services\CodeGenerationService;
use App\Services\Contacts\ContactOpeningBalanceService;
use App\Services\Contacts\ContactService;
use App\Services\Setups\BranchService;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function __construct(
        private BranchService $branchService,
        private AccountService $accountService,
        private BankService $bankService,
        private AccountGroupService $accountGroupService,
        private ContactService $contactService,
        private ContactOpeningBalanceService $contactOpeningBalanceService,
        private AccountLedgerService $accountLedgerService,
        private BankAccessBranchService $bankAccessBranchService,
        private UserActivityLogUtil $userActivityLogUtil
    ) {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('accounting_access')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->accountService->accountListTable($request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $accountGroups = $this->accountGroupService->singleAccountGroupByAnyCondition(with: ['parentGroup'])->where('is_main_group', 0)->get();

        return view('accounting.accounts.index', compact('branches', 'accountGroups'));
    }

    public function create()
    {
        if (! auth()->user()->can('accounting_access')) {

            abort(403, 'Access Forbidden.');
        }

        $groups = $this->accountGroupService->accountGroups(with: ['parentGroup'])->where('is_main_group', 0)->orWhere('is_global', 1)->get();
        $banks = $this->bankService->banks()->get();

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('accounting.accounts.ajax_view.create', compact('groups', 'banks', 'branches'));
    }

    public function store(Request $request, CodeGenerationService $codeGenerator)
    {
        if (! auth()->user()->can('accounting_access')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'account_group_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $accountStartDate = $generalSettings['business__start_date'];
            $cusIdPrefix = $generalSettings['prefix__customer_id'] ? $generalSettings['prefix__customer_id'] : 'C';
            $supIdPrefix = $generalSettings['prefix__supplier_id'] ? $generalSettings['prefix__supplier_id'] : 'S';

            $accountGroup = $this->accountGroupService->singleAccountGroup(id: $request->account_group_id);

            $addAccount = $this->accountService->addAccount(
                name: $request->name,
                accountGroup: $accountGroup,
                accountNumber: $request->account_number,
                bankId: $request->bank_id,
                bankAddress: $request->bank_address,
                bankCode: $request->bank_code,
                swiftCode: $request->swift_code,
                bankBranch: $request->bank_branch,
                taxPercent: $request->tax_percent,
                openingBalance: $request->opening_balance,
                openingBalanceType: $request->opening_balance_type,
                remarks: $request->remarks,
            );

            if ($accountGroup->sub_sub_group_number == 1 || $accountGroup->sub_sub_group_number == 11) {

                if (isset($request->branch_count) && count($request->branch_ids) > 0) {

                    $this->bankAccessBranchService->addBankAccessBranch(bankAccountId: $addAccount->id, branchIds: $request->branch_ids);
                }
            }

            if ($accountGroup->sub_sub_group_number == 6 || $accountGroup->sub_sub_group_number == 10) {

                $contactIdPrefix = $accountGroup->sub_sub_group_number == 6 ? $cusIdPrefix : $supIdPrefix;
                $contactPhoneNo = $accountGroup->sub_sub_group_number == 6 ? $request->customer_phone_no : $request->supplier_phone_no;
                $contactAddress = $accountGroup->sub_sub_group_number == 6 ? $request->customer_address : $request->supplier_address;

                $addAccount->phone = $contactPhoneNo;
                $addAccount->address = $contactAddress;

                $contactType = $accountGroup->sub_sub_group_number == 6 ? ContactType::Customer->value : ContactType::Supplier->value;

                $addContact = $this->contactService->addContact(type: $contactType, codeGenerator: $codeGenerator, contactIdPrefix: $contactIdPrefix, name: $request->name, phone: $contactPhoneNo, address: $contactAddress, creditLimit: $request->credit_limit, openingBalance: ($request->opening_balance ? $request->opening_balance : 0), openingBalanceType: $request->opening_balance_type);

                $addAccount->contact_id = $addContact->id;
                $addAccount->save();

                if ($contactType == ContactType::Supplier->value) {

                    $addContactOpeningBalance = $this->contactOpeningBalanceService->addContactOpeningBalance(contactId: $addContact->id, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type);
                }
            }

            $this->accountLedgerService->addAccountLedgerEntry(
                voucher_type_id: 0,
                date: $accountStartDate,
                account_id: $addAccount->id,
                trans_id: $addAccount->id,
                amount: $request->opening_balance ? $request->opening_balance : 0,
                amount_type: $request->opening_balance_type == 'dr' ? 'debit' : 'credit',
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addAccount;
    }

    public function edit($accountId)
    {
        $account = $this->accountService->singleAccountById(id: $accountId, with: ['group', 'bankAccessBranches', 'openingBalance']);
        $groups = $this->accountGroupService->accountGroups(with: ['parentGroup'])->where('is_main_group', 0)->orWhere('is_global', 1)->get();
        $banks = $this->bankService->banks()->get();
        $branches = $this->branchService->branches(with: ['parentBranch'])
        ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('accounting.accounts.ajax_view.edit', compact('account', 'groups', 'banks', 'branches'));
    }

    public function update(Request $request, $accountId, CodeGenerationService $codeGenerator)
    {
        if (! auth()->user()->can('accounting_access')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'account_group_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $accountStartDate = $generalSettings['business__start_date'];
            $cusIdPrefix = $generalSettings['prefix__customer_id'] ? $generalSettings['prefix__customer_id'] : 'C';
            $supIdPrefix = $generalSettings['prefix__supplier_id'] ? $generalSettings['prefix__supplier_id'] : 'S';

            $accountGroup = $this->accountGroupService->singleAccountGroup(id: $request->account_group_id);

            $restriction = $this->accountService->restriction($accountGroup, $accountId);
            if ($restriction['pass'] == false) {

                return response()->json(['errorMsg' => $restriction['msg']]);
            }

            $updateAccount = $this->accountService->updateAccount(
                accountId: $accountId,
                name: $request->name,
                accountGroup: $accountGroup,
                accountNumber: $request->account_number,
                bankId: $request->bank_id,
                bankAddress: $request->bank_address,
                bankCode: $request->bank_code,
                swiftCode: $request->swift_code,
                bankBranch: $request->bank_branch,
                taxPercent: $request->tax_percent,
                openingBalance: $request->opening_balance,
                openingBalanceType: $request->opening_balance_type,
                remarks: $request->remarks,
            );

            // @if ($loop->first) @continue @endif

            if ($accountGroup->sub_sub_group_number == 1 || $accountGroup->sub_sub_group_number == 11) {

                $this->bankAccessBranchService->updateBankAccessBranch(bankAccount: $updateAccount, branchIds: $request->branch_ids ?? []);
            } else {

                $updateAccount->bankAccessBranches()->delete();
            }

            if ($accountGroup->sub_sub_group_number == 6 || $accountGroup->sub_sub_group_number == 10) {

                $contactPhoneNo = $accountGroup->sub_sub_group_number == 6 ? $request->customer_phone_no : $request->supplier_phone_no;
                $contactAddress = $accountGroup->sub_sub_group_number == 6 ? $request->customer_address : $request->supplier_address;

                $updateAccount->phone = $contactPhoneNo;
                $updateAccount->address = $contactAddress;

                $contactType = $accountGroup->sub_sub_group_number == 6 ? ContactType::Customer->value : ContactType::Supplier->value;
                $contactId = '';
                if ($updateAccount?->contact) {

                    $contactId = $updateAccount?->contact_id;
                    $updateContact = $this->contactService->updateContact(contactId: $updateAccount->contact_id, type: $contactType, name: $request->name, phone: $contactPhoneNo, address: $contactAddress, creditLimit: $request->credit_limit, openingBalance: ($request->opening_balance ? $request->opening_balance : 0), openingBalanceType: $request->opening_balance_type);
                } else {

                    $contactIdPrefix = $accountGroup->sub_sub_group_number == 6 ? $cusIdPrefix : $supIdPrefix;
                    $addContact = $this->contactService->addContact(type: $contactType, codeGenerator: $codeGenerator, contactIdPrefix: $contactIdPrefix, name: $request->name, phone: $contactPhoneNo, address: $contactAddress, creditLimit: $request->credit_limit, openingBalance: ($request->opening_balance ? $request->opening_balance : 0), openingBalanceType: $request->opening_balance_type);

                    $contactId = $addContact->id;
                }

                $updateAccount->contact_id = $contactId;
                $updateAccount->save();

                if ($contactType == ContactType::Supplier->value) {

                    if ($updateAccount->contact->openingBalance) {

                        $updateContactOpeningBalance = $this->contactOpeningBalanceService->updateContactOpeningBalance(contactOpeningBalance: $updateAccount?->contact?->openingBalance, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type);
                    } else {

                        $addContactOpeningBalance = $this->contactOpeningBalanceService->addContactOpeningBalance(contactId: $contactId, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type);
                    }
                }
            } else {

                $contact = $updateAccount?->contact;
                $updateAccount->contact_id = null;
                $updateAccount->save();

                $contact?->delete();
            }

            $this->accountLedgerService->updateAccountLedgerEntry(
                voucher_type_id: 0,
                date: $accountStartDate,
                account_id: $updateAccount->id,
                trans_id: $updateAccount->id,
                amount: $request->opening_balance ? $request->opening_balance : 0,
                amount_type: $request->opening_balance_type == 'dr' ? 'debit' : 'credit',
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Account updated successfully'));
    }

    public function delete(Request $request, $accountId)
    {
        if (! auth()->user()->can('accounting_access')) {

            abort(403, 'Access Forbidden.');
        }

        try {
            DB::beginTransaction();

            $deleteAccount = $this->accountService->deleteAccount($accountId);

            if ($deleteAccount['success'] == false) {

                return response()->json(['errorMsg' => $deleteAccount['msg']]);
            }

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 17, data_obj: $deleteAccount);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json($deleteAccount['msg']);
    }
}
