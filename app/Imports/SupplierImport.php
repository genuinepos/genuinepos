<?php

namespace App\Imports;

use App\Enums\BooleanType;
use App\Enums\ContactType;
use Illuminate\Support\Collection;
use App\Enums\SupplierImportExcelCol;
use Maatwebsite\Excel\Concerns\ToCollection;

class SupplierImport implements ToCollection
{
    public function __construct(
        private $accountGroupService,
        private $codeGenerator,
        private $contactService,
        private $accountService,
        private $accountLedgerService,
    ) {
    }

    public function collection(Collection $collection)
    {
        $index = 0;
        $generalSettings = config('generalSettings');
        $supIdPrefix = $generalSettings['prefix__supplier_id'] ? $generalSettings['prefix__supplier_id'] : 'S';
        $accountStartDate = $generalSettings['business_or_shop__account_start_date'];
        $supplierAccountGroup = $this->accountGroupService->singleAccountGroupByAnyCondition()
            ->where('sub_sub_group_number', 10)->where('is_reserved', BooleanType::True->value)->first();

        foreach ($collection as $c) {

            if ($index != 0) {

                if ($c[SupplierImportExcelCol::Name->value] && $c[SupplierImportExcelCol::Phone->value]) {

                    $addContact = $this->contactService->addContact(
                        type: ContactType::Supplier->value,
                        codeGenerator: $this->codeGenerator,
                        contactIdPrefix: $supIdPrefix,
                        name: $c[SupplierImportExcelCol::Name->value],
                        phone: $c[SupplierImportExcelCol::Phone->value],
                        businessName: $c[SupplierImportExcelCol::Business->value],
                        email: $c[SupplierImportExcelCol::Email->value],
                        alternativePhone: $c[SupplierImportExcelCol::AlternativeNumber->value],
                        landLine: $c[SupplierImportExcelCol::Landline->value],
                        dateOfBirth: null,
                        taxNumber: $c[SupplierImportExcelCol::TaxNumber->value],
                        customerGroupId: null,
                        address: $c[SupplierImportExcelCol::Address->value],
                        city: $c[SupplierImportExcelCol::City->value],
                        state: $c[SupplierImportExcelCol::State->value],
                        country: $c[SupplierImportExcelCol::Country->value],
                        zipCode: $c[SupplierImportExcelCol::ZipCode->value],
                        shippingAddress: null,
                        payTerm: $c[SupplierImportExcelCol::PayTermNumber->value],
                        payTermNumber: $c[SupplierImportExcelCol::PayTerm->value],
                        creditLimit: 0,
                        openingBalance: 0,
                        openingBalanceType: 'dr'
                    );

                    $addAccount = $this->accountService->addAccount(
                        name: $c[SupplierImportExcelCol::Name->value],
                        accountGroup: $supplierAccountGroup,
                        phone: $c[SupplierImportExcelCol::Phone->value],
                        address: $c[SupplierImportExcelCol::Address->value],
                        openingBalance: 0,
                        openingBalanceType: 'cr',
                        contactId: $addContact->id
                    );

                    $this->accountLedgerService->addAccountLedgerEntry(
                        voucher_type_id: 0,
                        date: $accountStartDate,
                        account_id: $addAccount->id,
                        trans_id: $addAccount->id,
                        amount: 0,
                        amount_type: 'credit',
                        branch_id: $addAccount->branch_id,
                    );
                }
            }
            $index++;
        }
    }
}
