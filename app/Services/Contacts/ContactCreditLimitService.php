<?php

namespace App\Services\Contacts;

use App\Models\Contacts\ContactCreditLimit;

class ContactCreditLimitService
{
    public function addContactCreditLimit($contactId, $creditLimit, $payTerm, $payTermNumber)
    {
        $addCreditLimit = new ContactCreditLimit();
        $addCreditLimit->contact_id = $contactId;
        $addCreditLimit->branch_id = auth()->user()->branch_id;
        $addCreditLimit->created_by_id = auth()->user()->id;
        $addCreditLimit->credit_limit = $creditLimit ? $creditLimit : 0;
        $addCreditLimit->pay_term = $payTerm;
        $addCreditLimit->pay_term_number = $payTermNumber;
        $addCreditLimit->save();
    }
}
