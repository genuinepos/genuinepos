<?php

namespace App\Http\Controllers\Contacts;

use App\Enums\ContactType;
use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountService;
use App\Services\Contacts\ContactService;
use App\Services\Accounts\AccountGroupService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Contacts\CustomerGroupService;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Services\Contacts\ContactCreditLimitService;
use App\Services\Contacts\ContactOpeningBalanceService;

class ContactController extends Controller
{
    public function __construct(
        private ContactService $contactService,
        private ContactOpeningBalanceService $contactOpeningBalanceService,
        private ContactCreditLimitService $contactCreditLimitService,
        private CustomerGroupService $customerGroupService,
        private AccountService $accountService,
        private AccountGroupService $accountGroupService,
        private AccountLedgerService $accountLedgerService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function create($type)
    {
        if ($type == ContactType::Customer->value) {
            if (!auth()->user()->can('customer_add')) {

                abort(403, 'Access Forbidden.');
            }
        } else if ($type == ContactType::Supplier->value) {

            if (!auth()->user()->can('supplier_add')) {

                abort(403, 'Access Forbidden.');
            }
        }

        $customerGroups = $this->customerGroupService->customerGroups()->get(['id', 'name']);
        return view('contacts.ajax_view.create', compact('type', 'customerGroups'));
    }

    public function store($type, Request $request, CodeGenerationServiceInterface $codeGenerator)
    {
        if ($type == ContactType::Customer->value) {
            if (!auth()->user()->can('customer_add')) {

                abort(403, 'Access Forbidden.');
            }
        } else if ($type == ContactType::Supplier->value) {

            if (!auth()->user()->can('supplier_add')) {

                abort(403, 'Access Forbidden.');
            }
        }

        try {

            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $accountStartDate = $generalSettings['business__start_date'];
            $cusIdPrefix = $generalSettings['prefix__customer_id'] ? $generalSettings['prefix__customer_id'] : 'C';
            $supIdPrefix = $generalSettings['prefix__supplier_id'] ? $generalSettings['prefix__supplier_id'] : 'S';

            $contactIdPrefix = $type == ContactType::Customer->value ? $cusIdPrefix : $supIdPrefix;

            $customerAccountGroup = $this->accountGroupService->singleAccountGroupByAnyCondition()
                ->where('sub_sub_group_number', 6)->where('is_reserved', 1)->first();

            $supplierAccountGroup = $this->accountGroupService->singleAccountGroupByAnyCondition()
                ->where('sub_sub_group_number', 10)->where('is_reserved', 1)->first();

            $accountGroup = $type == ContactType::Customer->value ? $customerAccountGroup : $supplierAccountGroup;

            $addContact = $this->contactService->addContact(type: $type, codeGenerator: $codeGenerator, contactIdPrefix: $contactIdPrefix, name: $request->name, phone: $request->phone, businessName: $request->business_name, email: $request->email, alternativePhone: $request->alternative_phone, landLine: $request->landline, dateOfBirth: $request->date_of_birth, taxNumber: $request->tax_number, customerGroupId: $request->customer_group_id, address: $request->address, city: $request->city, state: $request->state, country: $request->country, zipCode: $request->zip_code, shippingAddress: $request->shipping_address, payTerm: $request->pay_term, payTermNumber: $request->pay_term_number, creditLimit: $request->credit_limit, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type);

            // if ($type == ContactType::Customer->value) {

            //     $this->contactCreditLimitService->addContactCreditLimit(contactId: $addContact->id, creditLimit: $request->credit_limit, payTerm: $request->pay_term, payTermNumber: $request->pay_term_number);
            // }

            if ($type == ContactType::Supplier->value) {

                $addContactOpeningBalance = $this->contactOpeningBalanceService->addContactOpeningBalance(contactId: $addContact->id, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type);
            }

            $addAccount = $this->accountService->addAccount(name: $request->name, accountGroup: $accountGroup, phone: $request->phone, address: $request->address, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type, contactId: $addContact->id);

            $this->accountLedgerService->addAccountLedgerEntry(
                voucher_type_id: 0,
                date: $accountStartDate,
                account_id: $addAccount->id,
                trans_id: $addAccount->id,
                amount: $request->opening_balance ? $request->opening_balance : 0,
                amount_type: $request->opening_balance_type == 'dr' ? 'debit' : 'credit',
            );

            // AccountLedger Will be go Here
            $this->userActivityLogUtil->addLog(action: 1, subject_type: $type == ContactType::Customer->value ? 1 : 2, data_obj: $addContact);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return ['id' => $addAccount->id, 'name' => $addAccount->name, 'phone' => $addAccount->phone, 'balance' => $request->opening_balance, 'balanceType' => $request->opening_balance_type];
    }

    public function edit($contactId, $type)
    {
        if ($type == ContactType::Customer->value) {

            if (!auth()->user()->can('customer_edit')) {

                abort(403, 'Access Forbidden.');
            }
        } else if ($type == ContactType::Supplier->value) {

            if (!auth()->user()->can('supplier_edit')) {

                abort(403, 'Access Forbidden.');
            }
        }

        $customerGroups = $this->customerGroupService->customerGroups()->get(['id', 'name']);
        $contact = $this->contactService->singleContact(id: $contactId, with: ['openingBalance', 'customerGroup']);

        return view('contacts.ajax_view.edit', compact('type', 'contact', 'customerGroups'));
    }

    public function update(Request $request, $contactId, $type)
    {
        if ($type == ContactType::Customer->value) {

            if (!auth()->user()->can('customer_edit')) {

                abort(403, 'Access Forbidden.');
            }
        } else if ($type == ContactType::Supplier->value) {

            if (!auth()->user()->can('supplier_edit')) {

                abort(403, 'Access Forbidden.');
            }
        }

        try {

            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $accountStartDate = $generalSettings['business__start_date'];

            $customerAccountGroup = $this->accountGroupService->singleAccountGroupByAnyCondition()
                ->where('sub_sub_group_number', 6)->where('is_reserved', 1)->first();

            $supplierAccountGroup = $this->accountGroupService->singleAccountGroupByAnyCondition()
                ->where('sub_sub_group_number', 10)->where('is_reserved', 1)->first();

            $accountGroup = $type == ContactType::Customer->value ? $customerAccountGroup : $supplierAccountGroup;

            $updateContact = $this->contactService->updateContact($contactId, type: $type, name: $request->name, phone: $request->phone, businessName: $request->business_name, email: $request->email, alternativePhone: $request->alternative_phone, landLine: $request->landline, dateOfBirth: $request->date_of_birth, taxNumber: $request->tax_number, customerGroupId: $request->customer_group_id, address: $request->address, city: $request->city, state: $request->state, country: $request->country, zipCode: $request->zip_code, shippingAddress: $request->shipping_address, payTerm: $request->pay_term, payTermNumber: $request->pay_term_number, creditLimit: $request->credit_limit, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type);

            if ($type == ContactType::Supplier->value) {

                $updateContactOpeningBalance = $this->contactOpeningBalanceService->updateContactOpeningBalance(contactOpeningBalance: $updateContact->openingBalance, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type);
            }

            // if ($type == ContactType::Customer->value) {

            //     $this->contactCreditLimitService->addContactCreditLimit(contactId: $addContact->id, creditLimit: $request->credit_limit, payTerm: $request->pay_term, payTermNumber: $request->pay_term_number);
            // }

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
                voucher_type_id: 0,
                date: $accountStartDate,
                account_id: $updateAccount->id,
                trans_id: $updateAccount->id,
                amount: $request->opening_balance ? $request->opening_balance : 0,
                amount_type: $request->opening_balance_type == 'dr' ? 'debit' : 'credit',
            );

            // AccountLedger Will be go Here
            $this->userActivityLogUtil->addLog(action: 2, subject_type: $type == ContactType::Customer->value ? 1 : 2, data_obj: $updateContact);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Contact is updated successfully.');
    }

    public function changeStatus($contactId)
    {
        return $this->contactService->changeStatus($contactId);
    }

    public function delete(Request $request, $id)
    {
        if (!auth()->user()->can('supplier_delete')) {

            abort(403, 'Access Forbidden.');
        }

        $this->contactService->deleteContact($id);

        return response()->json('Contact deleted successfully');
    }
}
