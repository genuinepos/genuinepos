<?php

namespace App\Services\Contacts;

use App\Enums\ContactType;
use App\Models\Contacts\Contact;

class ContactService
{
    function addContact($type, $codeGenerator, $name, $phone, $businessName, $email = null, $alternativePhone = null, $landLine = null, $dateOfBirth = null, $taxNumber = null, $customerGroupId = null, $address = null, $city = null, $state = null, $country = null, $zipCode = null, $shippingAddress = null)
    {
        $addContact = new Contact();
        $addContact->name = $name;
        $addContact->phone = $phone;
        $addContact->business_name = $businessName;
        $addContact->email = $email;
        $addContact->alternative_phone = $alternativePhone;
        $addContact->landline = $landLine;
        $addContact->date_of_birth = $dateOfBirth;
        $addContact->tax_number = $taxNumber;

        if ($type == ContactType::Customer->value) {

            $addContact->customer_group_id = $customerGroupId;
        }

        $addContact->address = $address;
        $addContact->city = $city;
        $addContact->state = $request->state;
        $addContact->zip_code = $state;
        $addContact->country = $country;
        $addContact->shipping_address = $zipCode;
        $addContact->save();

        return $addContact;
    }
}
