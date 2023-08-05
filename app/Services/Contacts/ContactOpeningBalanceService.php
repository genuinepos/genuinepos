<?php

namespace App\Services\Contacts;

use App\Models\Contacts\ContactOpeningBalance;
use Carbon\Carbon;

class ContactOpeningBalanceService
{
    public function addContactOpeningBalance($contactId, $openingBalance, $openingBalanceType)
    {

        $addCustomerOpeningBalance = new ContactOpeningBalance();
        $addCustomerOpeningBalance->contact_id = $contactId;
        $addCustomerOpeningBalance->branch_id = auth()->user()->branch_id;
        $addCustomerOpeningBalance->created_by_id = auth()->user()->id;
        $addCustomerOpeningBalance->amount = $openingBalance ? $openingBalance : 0;
        $addCustomerOpeningBalance->date_ts = Carbon::now();
        $addCustomerOpeningBalance->amount_type = $openingBalanceType;
        $addCustomerOpeningBalance->save();
    }

    public function updateContactOpeningBalance($contactOpeningBalance, $openingBalance, $openingBalanceType)
    {

        $contactOpeningBalance->amount = $openingBalance ? $openingBalance : 0;
        $contactOpeningBalance->amount_type = $openingBalanceType;
        $contactOpeningBalance->save();

        return $contactOpeningBalance;
    }
}
