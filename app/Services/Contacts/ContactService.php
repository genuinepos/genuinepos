<?php

namespace App\Services\Contacts;

use App\Enums\ContactType;
use App\Models\Contacts\Contact;

class ContactService
{
    function addContact($type, $codeGenerator, $contactIdPrefix, $name, $phone, $businessName = null, $email = null, $alternativePhone = null, $landLine = null, $dateOfBirth = null, $taxNumber = null, $customerGroupId = null, $address = null, $city = null, $state = null, $country = null, $zipCode = null, $shippingAddress = null, $payTerm = null, $payTermNumber = null, $creditLimit = null, $openingBalance = 0, $openingBalanceType = 'dr')
    {
        $contactId = $codeGenerator->generateAndTypeWiseWithoutYearMonth(table: 'contacts', column: 'contact_id', typeColName: 'type', typeValue: $type, prefix: $contactIdPrefix, digits: 4);

        $addContact = new Contact();
        $addContact->contact_id = $contactId;
        $addContact->type = $type;
        $addContact->name = $name;
        $addContact->phone = $phone;
        $addContact->business_name = $businessName;
        $addContact->email = $email;
        $addContact->alternative_phone = $alternativePhone;
        $addContact->landline = $landLine;
        $addContact->date_of_birth = $dateOfBirth;
        $addContact->tax_number = $taxNumber;
        $addContact->pay_term = $payTerm;
        $addContact->pay_term_number = $payTermNumber;
        $addContact->credit_limit = $creditLimit ? $creditLimit : 0;
        $addContact->opening_balance = $openingBalance ? $openingBalance : 0;
        $addContact->opening_balance_type = $openingBalanceType;

        if ($type == ContactType::Customer->value) {

            $addContact->customer_group_id = $customerGroupId;
        }

        $addContact->address = $address;
        $addContact->city = $city;
        $addContact->state = $state;
        $addContact->zip_code = $zipCode;
        $addContact->country = $country;
        $addContact->shipping_address = $shippingAddress;
        $addContact->save();

        return $addContact;
    }

    function updateContact($contactId, $type, $name, $phone, $businessName = null, $email = null, $alternativePhone = null, $landLine = null, $dateOfBirth = null, $taxNumber = null, $customerGroupId = null, $address = null, $city = null, $state = null, $country = null, $zipCode = null, $shippingAddress = null, $payTerm = null, $payTermNumber = null, $creditLimit = null, $openingBalance = 0, $openingBalanceType = 'dr')
    {
        $updateContact = Contact::with('openingBalance')->where('id', $contactId)->first();
        $updateContact->type = $type;
        $updateContact->name = $name;
        $updateContact->phone = $phone;
        $updateContact->business_name = $businessName;
        $updateContact->email = $email;
        $updateContact->alternative_phone = $alternativePhone;
        $updateContact->landline = $landLine;
        $updateContact->date_of_birth = $dateOfBirth;
        $updateContact->tax_number = $taxNumber;
        $updateContact->pay_term = $payTerm;
        $updateContact->pay_term_number = $payTermNumber;
        $updateContact->credit_limit = $creditLimit ? $creditLimit : 0;
        $updateContact->opening_balance = $openingBalance ? $openingBalance : 0;
        $updateContact->opening_balance_type = $openingBalanceType;

        if ($type == ContactType::Customer->value) {

            $updateContact->customer_group_id = $customerGroupId;
        }

        $updateContact->address = $address;
        $updateContact->city = $city;
        $updateContact->state = $state;
        $updateContact->zip_code = $zipCode;
        $updateContact->country = $country;
        $updateContact->shipping_address = $shippingAddress;
        $updateContact->save();

        return $updateContact;
    }

    public function changeStatus($contactId) {

        $statusChange = Contact::where('id', $contactId)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();
            return response()->json('Contact deactivated successfully');
        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json('Contact activated successfully');
        }
    }

    public function singleContact(int $id, array $with = null) {

        $query = Contact::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
