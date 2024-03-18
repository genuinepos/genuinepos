<?php

namespace App\Imports;

use App\Enums\BooleanType;
use App\Enums\ContactType;
use Illuminate\Support\Collection;
use App\Enums\CustomerImportExcelCol;
use Maatwebsite\Excel\Concerns\ToCollection;

class CustomerImport implements ToCollection
{
    public function __construct(
        private $accountGroupService,
        private $codeGenerator,
        private $contactService,
        private $accountService,
        private $accountLedgerService,
        private $contactOpeningBalanceService,
    ) {
    }

    public function collection(Collection $collection)
    {
        // dd($collection);
        $index = 0;
        $generalSettings = config('generalSettings');
        $cusIdPrefix = $generalSettings['prefix__customer_id'] ? $generalSettings['prefix__customer_id'] : 'C';
        $accountStartDate = $generalSettings['business_or_shop__account_start_date'];
        $customerAccountGroup = $this->accountGroupService->singleAccountGroupByAnyCondition()
            ->where('sub_sub_group_number', 6)->where('is_reserved', BooleanType::True->value)->first();

        foreach ($collection as $c) {

            if ($index != 0) {

                if ($c[CustomerImportExcelCol::Name->value] && $c[CustomerImportExcelCol::Phone->value]) {

                    $creditLimit = (float)$c[CustomerImportExcelCol::CreditLimit->value];
                    $__creditLimit = 0;
                    if (gettype($creditLimit) == 'integer' || gettype($creditLimit) == 'double') {

                        $__creditLimit = $creditLimit;
                    }

                    $openingBalance = (float)$c[CustomerImportExcelCol::OpeningBalance->value];
                    $__openingBalance = 0;
                    if (gettype($openingBalance) == 'integer' || gettype($openingBalance) == 'double') {

                        $__openingBalance = $openingBalance;
                    }

                    $openingBalanceType = strtolower($c[CustomerImportExcelCol::OpeningBalanceType->value]);
                    $__openingBalanceType = 'dr';
                    if ($openingBalanceType == 'debit' || $openingBalanceType == 'dr') {

                        $__openingBalanceType = 'dr';
                    } else if ($openingBalanceType == 'credit' || $openingBalanceType == 'cr') {

                        $__openingBalanceType = 'cr';
                    }

                    $addContact = $this->contactService->addContact(
                        type: ContactType::Customer->value,
                        codeGenerator: $this->codeGenerator,
                        contactIdPrefix: $cusIdPrefix,
                        name: $c[CustomerImportExcelCol::Name->value],
                        phone: $c[CustomerImportExcelCol::Phone->value],
                        businessName: $c[CustomerImportExcelCol::Business->value],
                        email: $c[CustomerImportExcelCol::Email->value],
                        alternativePhone: $c[CustomerImportExcelCol::AlternativeNumber->value],
                        landLine: $c[CustomerImportExcelCol::Landline->value],
                        dateOfBirth: null,
                        taxNumber: $c[CustomerImportExcelCol::TaxNumber->value],
                        customerGroupId: null,
                        address: $c[CustomerImportExcelCol::Address->value],
                        city: $c[CustomerImportExcelCol::City->value],
                        state: $c[CustomerImportExcelCol::State->value],
                        country: $c[CustomerImportExcelCol::Country->value],
                        zipCode: $c[CustomerImportExcelCol::ZipCode->value],
                        shippingAddress: $c[CustomerImportExcelCol::ShippingAddress->value],
                        payTerm: $c[CustomerImportExcelCol::PayTermNumber->value],
                        payTermNumber: $c[CustomerImportExcelCol::PayTerm->value],
                        creditLimit: $__creditLimit,
                        openingBalance: $__openingBalance,
                        openingBalanceType: $__openingBalanceType
                    );

                    $addAccount = $this->accountService->addAccount(
                        name: $c[CustomerImportExcelCol::Name->value],
                        accountGroup: $customerAccountGroup,
                        phone: $c[CustomerImportExcelCol::Phone->value],
                        address: $c[CustomerImportExcelCol::Address->value],
                        openingBalance: $__openingBalance,
                        openingBalanceType: $__openingBalanceType,
                        contactId: $addContact->id
                    );

                    $this->accountLedgerService->addAccountLedgerEntry(
                        voucher_type_id: 0,
                        date: $accountStartDate,
                        account_id: $addAccount->id,
                        trans_id: $addAccount->id,
                        amount: $__openingBalance,
                        amount_type: $__openingBalanceType == 'dr' ? 'debit' : 'credit',
                        branch_id: $addAccount->branch_id,
                    );
                }
            }
            $index++;
        }
    }
}
