<?php

namespace App\Services\Contacts;

use App\Enums\BooleanType;
use App\Enums\ContactType;
use App\Models\Contacts\Contact;

class ContactService
{
    public function addContact($type, $codeGenerator, $contactIdPrefix, $name, $phone, $businessName = null, $email = null, $alternativePhone = null, $landLine = null, $dateOfBirth = null, $taxNumber = null, $customerGroupId = null, $address = null, $city = null, $state = null, $country = null, $zipCode = null, $shippingAddress = null, $payTerm = null, $payTermNumber = null, $creditLimit = null, $openingBalance = 0, $openingBalanceType = 'dr')
    {
        $isCheckBranch = $type == ContactType::Supplier->value ? false : true;
        $contactId = $codeGenerator->generateAndTypeWiseWithoutYearMonth(table: 'contacts', column: 'contact_id', typeColName: 'type', typeValue: $type, prefix: $contactIdPrefix, digits: 4, isCheckBranch: $isCheckBranch, branchId: auth()->user()->branch_id);

        $prefixTypeSign = $type == ContactType::Supplier->value ? 'S' : 'C';
        $contactPrefix = $codeGenerator->generateAndTypeWiseWithoutYearMonth(table: 'contacts', column: 'contact_id', typeColName: 'type', typeValue: $type, prefix: $prefixTypeSign, digits: 0, splitter: ':', isCheckBranch: $isCheckBranch, branchId: auth()->user()->branch_id);

        $addContact = new Contact();
        $addContact->branch_id = auth()->user()->branch_id;
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
        $addContact->prefix = $contactPrefix;
        $addContact->save();

        return $addContact;
    }

    public function updateContact($contactId, $type, $name, $phone, $businessName = null, $email = null, $alternativePhone = null, $landLine = null, $dateOfBirth = null, $taxNumber = null, $customerGroupId = null, $address = null, $city = null, $state = null, $country = null, $zipCode = null, $shippingAddress = null, $payTerm = null, $payTermNumber = null, $creditLimit = null, $openingBalance = 0, $openingBalanceType = 'dr')
    {
        $updateContact = $this->singleContact(id: $contactId, with: ['account', 'account.accountOpeningBalance']);
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

    public function updateRewardPoint(int $contactId = null, int $pointOnInvoice = 0, int $currentRedeemedPoint = 0): void
    {
        if (isset($contactId)) {

            $updateRewardPoint = $this->singleContact(id: $contactId);
            $updateRewardPoint->reward_point = ($updateRewardPoint->reward_point + $pointOnInvoice) - $currentRedeemedPoint;
            $updateRewardPoint->save();
        }
    }

    public function changeStatus(int $id): string
    {
        $statusChange = $this->singleContact(id: $id);

        if ($statusChange->status == BooleanType::True->value) {

            $statusChange->status = BooleanType::False->value;
            $statusChange->save();

            return  __('Contact deactivated successfully');
        } else {

            $statusChange->status = BooleanType::True->value;
            $statusChange->save();

            return __('Contact activated successfully');
        }
    }

    public function deleteContact(int $id): array|object
    {
        $deleteContact = $this->singleContact(id: $id, with: ['account', 'account.accountLedgersWithOutOpeningBalances']);

        if (isset($deleteContact)) {

            if (isset($deleteContact?->account)) {

                if (count($deleteContact?->account?->accountLedgersWithOutOpeningBalances) > 0) {

                    return ['pass' => false, 'msg' => __('Contact can not be deleted. One or more ledger entries are belonging in this contact.')];
                }
            }

            $deleteContact->delete();
        }

        return $deleteContact;
    }

    public function singleContact(int $id, array $with = null)
    {
        $query = Contact::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
