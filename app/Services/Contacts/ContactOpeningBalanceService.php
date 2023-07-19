<?php

namespace App\Services\Contacts;

use App\Models\Contacts\ContactOpeningBalance;

class ContactOpeningBalanceService
{
    function addContactOpeningBalance($contactId, $openingBalance, $openingBalanceType) {

        $addCustomerOpeningBalance = new ContactOpeningBalance();
        $addCustomerOpeningBalance->customer_id = $contactId;
        $addCustomerOpeningBalance->branch_id = auth()->user()->branch_id;
        $addCustomerOpeningBalance->created_by_id = auth()->user()->id;
        $addCustomerOpeningBalance->amount = $openingBalance ? $openingBalance : 0;
        $addCustomerOpeningBalance->amount_type = $openingBalanceType;
        $addCustomerOpeningBalance->save();
    }
}
