<?php

namespace App\Http\Controllers\Contacts;

use App\Enums\ContactType;
use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Contacts\ContactService;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Services\Contacts\ContactCreditLimitService;
use App\Services\Contacts\ContactOpeningBalanceService;

class ContactController extends Controller
{
    public function __construct(
        private ContactService $contactService,
        private ContactOpeningBalanceService $contactOpeningBalanceService,
        private ContactCreditLimitService $contactCreditLimitService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function create($type)
    {
        $customerGroups = DB::table('customer_groups')->select('id', 'group_name')->get();
        return view('contacts.ajax_view.create', compact('type', 'customerGroups'));
    }

    function store($type, Request $request, CodeGenerationServiceInterface $codeGenerator)
    {
        try {

            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $cusIdPrefix = $generalSettings['prefix__customer_id'] ? $generalSettings['prefix__customer_id'] : 'C';
            $supIdPrefix = $generalSettings['prefix__supplier_id'] ? $generalSettings['prefix__supplier_id'] : 'S';

            $contactIdPrefix = $type == ContactType::Customer->value ? $cusIdPrefix : $supIdPrefix;

            $addContact = $this->contactService->addContact(type: $type, codeGenerator: $codeGenerator, contactIdPrefix: $contactIdPrefix, name: $request->name, phone: $request->phone, businessName: $request->business_name, email: $request->email, alternativePhone: $request->alternative_phone, landLine: $request->landline, dateOfBirth: $request->date_of_birth, taxNumber: $request->tax_number, customerGroupId: $request->customer_group_id, address: $request->address, city: $request->city, state: $request->state, country: $request->country, zipCode: $request->zip_code, shippingAddress: $request->shipping_address, payTerm: $request->pay_term, payTermNumber: $request->pay_term_number, creditLimit: $request->credit_limit, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type);

            $addContactOpeningBalance = $this->contactOpeningBalanceService->addContactOpeningBalance(contactId: $addContact->id, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type);

            // if ($type == ContactType::Customer->value) {

            //     $this->contactCreditLimitService->addContactCreditLimit(contactId: $addContact->id, creditLimit: $request->credit_limit, payTerm: $request->pay_term, payTermNumber: $request->pay_term_number);
            // }

            // AccountLedger Will be go Here
            $this->userActivityLogUtil->addLog(action: 1, subject_type: $type == ContactType::Customer->value ? 1 : 2, data_obj: $addContact);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addContact;
    }

    public function edit($contactId, $type)
    {
        $customerGroups = DB::table('customer_groups')->select('id', 'group_name')->get();
        $contact = $this->contactService->singleContact(id: $contactId, with: ['openingBalance', 'openingBalances', 'customerGroup']);
        return view('contacts.ajax_view.edit', compact('type', 'contact', 'customerGroups'));
    }

    public function update(Request $request, $contactId, $type)
    {
        try {

            DB::beginTransaction();

            $updateContact = $this->contactService->updateContact($contactId, type: $type, name: $request->name, phone: $request->phone, businessName: $request->business_name, email: $request->email, alternativePhone: $request->alternative_phone, landLine: $request->landline, dateOfBirth: $request->date_of_birth, taxNumber: $request->tax_number, customerGroupId: $request->customer_group_id, address: $request->address, city: $request->city, state: $request->state, country: $request->country, zipCode: $request->zip_code, shippingAddress: $request->shipping_address, payTerm: $request->pay_term, payTermNumber: $request->pay_term_number, creditLimit: $request->credit_limit, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type);

            $updateContactOpeningBalance = $this->contactOpeningBalanceService->updateContactOpeningBalance(contact: $updateContact->openingBalance, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type);

            // if ($type == ContactType::Customer->value) {

            //     $this->contactCreditLimitService->addContactCreditLimit(contactId: $addContact->id, creditLimit: $request->credit_limit, payTerm: $request->pay_term, payTermNumber: $request->pay_term_number);
            // }

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
}
