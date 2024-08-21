<?php

namespace App\Services\Accounts\MethodContainerServices;

use App\Enums\BooleanType;
use App\Enums\ContactType;
use App\Services\Accounts\BankService;
use App\Services\Setups\BranchService;
use App\Enums\AccountCreateAndEditType;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Contacts\ContactService;
use App\Services\Accounts\AccountGroupService;
use App\Services\Users\UserActivityLogService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\BankAccessBranchService;
use App\Services\Accounts\AccountOpeningBalanceService;
use App\Interfaces\Accounts\AccountControllerMethodContainersInterface;

class AccountControllerMethodContainersService implements AccountControllerMethodContainersInterface
{
    public function __construct(
        private BranchService $branchService,
        private AccountService $accountService,
        private AccountOpeningBalanceService $accountOpeningBalanceService,
        private BankService $bankService,
        private AccountGroupService $accountGroupService,
        private ContactService $contactService,
        private AccountLedgerService $accountLedgerService,
        private BankAccessBranchService $bankAccessBranchService,
        private UserActivityLogService $userActivityLogService
    ) {
    }

    public function indexMethodContainer(object $request): array|object
    {
        $data = [];
        if ($request->ajax()) {

            return $this->accountService->accountListTable(request: $request);
        }

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $data['accountGroups'] = $this->accountGroupService->singleAccountGroupByAnyCondition(with: ['parentGroup'])
            ->where('is_main_group', BooleanType::False->value)->get();

        return $data;
    }

    public function expenseAccountsContainer(object $request): ?object
    {
        return $this->accountService->expenseAccounts(request: $request);
    }

    public function customerAccountsContainer(object $request): ?object
    {
        return $this->accountService->customerAccounts(request: $request);
    }

    public function createMethodContainer(int $type): array
    {
        $data = [];
        $query = $this->accountGroupService->accountGroups(with: ['parentGroup']);

        if ($type == AccountCreateAndEditType::Capitals->value) {

            $query->where('sub_group_number', 6);
        } else if ($type == AccountCreateAndEditType::DutiesAndTaxes->value) {

            $query->where('main_group_number', 2)
                ->where('sub_group_number', 7)
                ->where('sub_sub_group_number', 8);
        }

        $data['groups'] = $query->get();

        $data['banks'] = $this->bankService->banks()->get();

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return $data;
    }

    public function storeMethodContainer(object $request, object $codeGenerator): object
    {
        $generalSettings = config('generalSettings');
        $accountStartDate = $generalSettings['business_or_shop__account_start_date'];
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

        if ($accountGroup->is_global) {

            $this->accountOpeningBalanceService->addOrUpdateAccountOpeningBalance(
                branchId: auth()->user()->branch_id,
                accountId: $addAccount->id,
                openingBalanceType: $request->opening_balance_type,
                openingBalance: $request->opening_balance ? $request->opening_balance : 0,
            );
        }

        if ($accountGroup->sub_sub_group_number == 1 || $accountGroup->sub_sub_group_number == 11) {

            if (isset($request->branch_count) && count($request->branch_ids) > 0) {

                $this->bankAccessBranchService->addBankAccessBranch(bankAccountId: $addAccount->id, branchIds: $request->branch_ids);
            } else if (auth()?->user()?->branch_id) {

                $this->bankAccessBranchService->addBankAccessBranch(bankAccountId: $addAccount->id, branchIds: [auth()?->user()?->branch_id]);
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
        }

        $this->accountLedgerService->addAccountLedgerEntry(
            voucher_type_id: AccountLedgerVoucherType::OpeningBalance->value,
            date: $accountStartDate,
            account_id: $addAccount->id,
            trans_id: $addAccount->id,
            amount: $request->opening_balance ? $request->opening_balance : 0,
            amount_type: $request->opening_balance_type == 'dr' ? 'debit' : 'credit',
            branch_id: auth()->user()->branch_id,
        );

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Accounts->value, dataObj: $addAccount);

        return $addAccount;
    }

    public function editMethodContainer(int $id, int $type): array
    {
        $data = [];
        $data['account'] = $this->accountService->singleAccountById(id: $id, with: ['group', 'bankAccessBranches', 'accountOpeningBalance']);

        $query = $this->accountGroupService->accountGroups(with: ['parentGroup']);

        if ($type == AccountCreateAndEditType::Capitals->value) {

            $query->where('sub_group_number', 6);
        } else if ($type == AccountCreateAndEditType::DutiesAndTaxes->value) {

            $query->where('main_group_number', 2)
                ->where('sub_group_number', 7)
                ->where('sub_sub_group_number', 8);
        }

        $data['groups'] = $query->get();

        $data['banks'] = $this->bankService->banks()->get();
        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return $data;
    }

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array
    {
        $generalSettings = config('generalSettings');
        $accountStartDate = $generalSettings['business_or_shop__account_start_date'];
        $cusIdPrefix = $generalSettings['prefix__customer_id'] ? $generalSettings['prefix__customer_id'] : 'C';
        $supIdPrefix = $generalSettings['prefix__supplier_id'] ? $generalSettings['prefix__supplier_id'] : 'S';

        $accountGroup = $this->accountGroupService->singleAccountGroup(id: $request->account_group_id);

        $restriction = $this->accountService->restriction($accountGroup, $id);
        if ($restriction['pass'] == false) {

            return ['pass' => false, 'msg' => $restriction['msg']];
        }

        $updateAccount = $this->accountService->updateAccount(
            accountId: $id,
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

        if ($accountGroup->is_global) {

            $this->accountOpeningBalanceService->addOrUpdateAccountOpeningBalance(
                branchId: auth()->user()->branch_id,
                accountId: $updateAccount->id,
                openingBalanceType: $request->opening_balance_type,
                openingBalance: $request->opening_balance ? $request->opening_balance : 0,
            );
        }

        if (
            ($accountGroup->sub_sub_group_number == 1 || $accountGroup->sub_sub_group_number == 11) &&
            isset($request->branch_count)
        ) {

            $this->bankAccessBranchService->updateBankAccessBranch(bankAccount: $updateAccount, branchIds: $request->branch_ids);
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
        } else {

            $contact = $updateAccount?->contact;
            $updateAccount->contact_id = null;
            $updateAccount->save();

            $contact?->delete();
        }

        $this->accountLedgerService->updateAccountLedgerEntry(
            voucher_type_id: AccountLedgerVoucherType::OpeningBalance->value,
            date: $accountStartDate,
            account_id: $updateAccount->id,
            trans_id: $updateAccount->id,
            amount: $request->opening_balance ? $request->opening_balance : 0,
            amount_type: $request->opening_balance_type == 'dr' ? 'debit' : 'credit',
            branch_id: auth()->user()->branch_id,
        );

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::Accounts->value, dataObj: $updateAccount);

        return null;
    }

    public function deleteMethodContainer(int $id): array
    {
        $deleteAccount = $this->accountService->deleteAccount(id: $id);

        if ($deleteAccount['pass'] == false) {

            return ['pass' => false, 'msg' => $deleteAccount['msg']];
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::Accounts->value, dataObj: $deleteAccount);

        return ['pass' => true];
    }
}
