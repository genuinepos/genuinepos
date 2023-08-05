<?php

namespace App\Services\Contacts;

use Carbon\Carbon;
use App\Models\Contacts\ContactOpeningBalance;

class ContactOpeningBalanceService
{
    function addContactOpeningBalance(int $contactId, float|null $openingBalance, string $openingBalanceType) {

        $addCustomerOpeningBalance = new ContactOpeningBalance();
        $addCustomerOpeningBalance->contact_id = $contactId;
        $addCustomerOpeningBalance->branch_id = auth()->user()->branch_id;
        $addCustomerOpeningBalance->created_by_id = auth()->user()->id;
        $addCustomerOpeningBalance->amount = $openingBalance ? $openingBalance : 0;
        $addCustomerOpeningBalance->date_ts = Carbon::now();
        $addCustomerOpeningBalance->amount_type = $openingBalanceType;
        $addCustomerOpeningBalance->save();
    }

    function updateContactOpeningBalance(object $contactOpeningBalance, float|null $openingBalance, string $openingBalanceType) {

        $contactOpeningBalance->amount = $openingBalance ? $openingBalance : 0;
        $contactOpeningBalance->amount_type = $openingBalanceType;
        $contactOpeningBalance->save();

        return $contactOpeningBalance;
    }
}
