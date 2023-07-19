<?php

namespace App\Http\Controllers\Contacts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Service\CodeGenerationService;
use App\Services\Contacts\ContactService;
use App\Services\Contacts\ContactOpeningBalanceService;

class ContactController extends Controller
{
    public function __construct(
        private ContactService $contactService,
        private ContactOpeningBalanceService $contactOpeningBalanceService,
    ) {
    }

    public function create($type)
    {
        $customerGroups = DB::table('customer_groups')->select('id', 'group_name')->get();
        return view('contacts.ajax_view.create', compact('type', 'customerGroups'));
    }

    function store($type, CodeGenerationService $codeGenerator)
    {
        $addContact = $this->contactService->addContact(type: $type, phone: $request->phone, businessName: $request->business_name, email: $request->email, alternativePhone: $request->alternative_phone, landLine: $request->land_line, dateOfBirth: $request->date_of_birth, taxNumber: $request->tax_number, customerGroupId: $request->customer_group_id, address: $request->address, city: $request->city, state: $request->state, country: $request->county, zipCode: $request->zip_code, shippingAddress: $request->shipping_address);

        $addContactOpeningBalance = $this->contactOpeningBalanceService->addContactOpeningBalance(contactId: $addContact->id, openingBalance: $request->opening_balance, openingBalanceType: $request->opening_balance_type);

        if ($type == Contact::Customer->value) {

            // ContractCreditLimit::class
        }
    }
}
