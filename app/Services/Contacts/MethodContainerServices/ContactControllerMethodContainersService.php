<?php

namespace App\Services\Contacts\MethodContainerServices;

use App\Enums\ContactType;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\BooleanType;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Contacts\ContactService;
use App\Services\Accounts\AccountGroupService;
use App\Services\Users\UserActivityLogService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Contacts\CustomerGroupService;
use App\Services\Accounts\AccountOpeningBalanceService;
use App\Interfaces\Contacts\ContactControllerMethodContainersInterface;

class ContactControllerMethodContainersService implements ContactControllerMethodContainersInterface
{
    public function __construct(
        private ContactService $contactService,
        private AccountOpeningBalanceService $accountOpeningBalanceService,
        private CustomerGroupService $customerGroupService,
        private AccountService $accountService,
        private AccountGroupService $accountGroupService,
        private AccountLedgerService $accountLedgerService,
        private UserActivityLogService $userActivityLogService,
    ) {
    }

    public function createMethodContainer(int $type): array
    {
        $data = [];
        $data['customerGroups'] = $this->customerGroupService->customerGroups()->get(['id', 'name']);
        return $data;
    }

    public function storeMethodContainer(int $type, object $request, object $codeGenerator): array
    {
        $generalSettings = config('generalSettings');
        $accountStartDate = $generalSettings['business_or_shop__account_start_date'];
        $cusIdPrefix = $generalSettings['prefix__customer_id'] ? $generalSettings['prefix__customer_id'] : 'C';
        $supIdPrefix = $generalSettings['prefix__supplier_id'] ? $generalSettings['prefix__supplier_id'] : 'S';

        $contactIdPrefix = $type == ContactType::Customer->value ? $cusIdPrefix : $supIdPrefix;

        $customerAccountGroup = $this->accountGroupService->singleAccountGroupByAnyCondition()
            ->where('sub_sub_group_number', 6)->where('is_reserved', BooleanType::True->value)->first();

        $supplierAccountGroup = $this->accountGroupService->singleAccountGroupByAnyCondition()
            ->where('sub_sub_group_number', 10)->where('is_reserved', BooleanType::True->value)->first();

        $accountGroup = $type == ContactType::Customer->value ? $customerAccountGroup : $supplierAccountGroup;

        $addContact = $this->contactService->addContact(type: $type, codeGenerator: $codeGenerator, contactIdPrefix: $contactIdPrefix, name: $request->name, phone: $request->phone, businessName: $request->business_name, email: $request->email, alternativePhone: $request->alternative_phone, landLine: $request->landline, dateOfBirth: $request->date_of_birth, taxNumber: $request->tax_number, customerGroupId: $request->customer_group_id, address: $request->address, city: $request->city, state: $request->state, country: $request->country, zipCode: $request->zip_code, shippingAddress: $request->shipping_address, payTerm: $request->pay_term, payTermNumber: $request->pay_term_number, creditLimit: $request->credit_limit, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type);

        $addAccount = $this->accountService->addAccount(name: $request->name, accountGroup: $accountGroup, phone: $request->phone, address: $request->address, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type, contactId: $addContact->id);

        if ($type == ContactType::Supplier->value) {

            $this->accountOpeningBalanceService->addOrUpdateAccountOpeningBalance(
                branchId: auth()->user()->branch_id,
                accountId: $addAccount->id,
                openingBalanceType: $request->opening_balance_type,
                openingBalance: $request->opening_balance ? $request->opening_balance : 0,
            );
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

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: $type == ContactType::Customer->value ? UserActivityLogSubjectType::Customers->value : UserActivityLogSubjectType::Suppliers->value, dataObj: $addContact);

        return ['id' => $addAccount->id, 'name' => $addAccount->name, 'phone' => $addAccount->phone, 'balance' => $request->opening_balance, 'balanceType' => $request->opening_balance_type, 'payTerm' => $addContact->pay_term, 'payTermNumber' => $addContact->pay_term_number];
    }

    public function editMethodContainer(int $id, int $type): array
    {
        $data = [];
        $data['customerGroups'] = $this->customerGroupService->customerGroups()->get(['id', 'name']);
        $data['contact'] = $this->contactService->singleContact(id: $id, with: ['account', 'account.accountOpeningBalance', 'customerGroup']);
        return $data;
    }

    public function updateMethodContainer(int $id, int $type, object $request): void
    {
        $generalSettings = config('generalSettings');
        $accountStartDate = $generalSettings['business_or_shop__account_start_date'];

        $customerAccountGroup = $this->accountGroupService->singleAccountGroupByAnyCondition()
            ->where('sub_sub_group_number', 6)->where('is_reserved', BooleanType::True->value)->first();

        $supplierAccountGroup = $this->accountGroupService->singleAccountGroupByAnyCondition()
            ->where('sub_sub_group_number', 10)->where('is_reserved', BooleanType::True->value)->first();

        $accountGroup = $type == ContactType::Customer->value ? $customerAccountGroup : $supplierAccountGroup;

        $updateContact = $this->contactService->updateContact(contactId: $id, type: $type, name: $request->name, phone: $request->phone, businessName: $request->business_name, email: $request->email, alternativePhone: $request->alternative_phone, landLine: $request->landline, dateOfBirth: $request->date_of_birth, taxNumber: $request->tax_number, customerGroupId: $request->customer_group_id, address: $request->address, city: $request->city, state: $request->state, country: $request->country, zipCode: $request->zip_code, shippingAddress: $request->shipping_address, payTerm: $request->pay_term, payTermNumber: $request->pay_term_number, creditLimit: $request->credit_limit, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type);

        if ($type == ContactType::Supplier->value) {

            if ($updateContact?->account) {

                $this->accountOpeningBalanceService->addOrUpdateAccountOpeningBalance(
                    branchId: auth()->user()->branch_id,
                    accountId: $updateContact?->account?->id,
                    openingBalanceType: $request->opening_balance_type,
                    openingBalance: $request->opening_balance ? $request->opening_balance : 0,
                );
            }
        }

        $updateAccount = $this->accountService->updateAccount(
            accountId: $updateContact?->account?->id,
            name: $request->name,
            phone: $request->phone,
            address: $request->address,
            accountGroup: $accountGroup,
            openingBalance: $request->opening_balance,
            openingBalanceType: $request->opening_balance_type,
        );

        $this->accountLedgerService->updateAccountLedgerEntry(
            voucher_type_id: AccountLedgerVoucherType::OpeningBalance->value,
            date: $accountStartDate,
            account_id: $updateAccount->id,
            trans_id: $updateAccount->id,
            amount: $request->opening_balance ? $request->opening_balance : 0,
            amount_type: $request->opening_balance_type == 'dr' ? 'debit' : 'credit',
            branch_id: $updateContact?->account?->branch_id,
            current_account_id: $updateContact?->account?->id,
        );

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: $type == ContactType::Customer->value ? UserActivityLogSubjectType::Customers->value : UserActivityLogSubjectType::Suppliers->value, dataObj: $updateContact);
    }

    public function changeStatusMethodContainer(int $id): string
    {
        return $this->contactService->changeStatus(id: $id);
    }

    public function deleteMethodContainer(int $id, int $type): array
    {
        $deleteContact = $this->contactService->deleteContact(id: $id);

        if (isset($deleteContact['pass']) && $deleteContact['pass'] == false) {

            return ['pass' => false, 'msg' => $deleteContact['msg']];
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: $type == ContactType::Customer->value ? UserActivityLogSubjectType::Customers->value : UserActivityLogSubjectType::Suppliers->value, dataObj: $deleteContact);

        return ['pass' => true];
    }
}
