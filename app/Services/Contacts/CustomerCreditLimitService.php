<?php

namespace App\Services\Contacts;

use App\Models\CustomerCreditLimit;

class CustomerCreditLimitService
{
    public function addCustomerCreditLimit($customerId, $creditLimit = null, $payTerm = null, $payTermNumber = null)
    {

        $addCreditLimit = new CustomerCreditLimit();
        $addCreditLimit->customer_id = $customerId;
        $addCreditLimit->branch_id = auth()->user()->branch_id;
        $addCreditLimit->created_by_id = auth()->user()->id;
        $addCreditLimit->credit_limit = $creditLimit ? $creditLimit : 0;
        $addCreditLimit->pay_term = $payTerm;
        $addCreditLimit->pay_term_number = $payTermNumber;
        $addCreditLimit->save();
    }
}
