<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\CustomerLedger;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CustomerImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //dd($collection);
        $index = 0;
        foreach ($collection as $c) {
            if ($index != 0) {
                if ($c[2]) {
                    $addCustomer = Customer::create([
                        'type' => 1,
                        'contact_id' => $c[0],
                        'business_name' => $c[1],
                        'name' => $c[2],
                        'phone' => $c[3],
                        'alternative_phone' => $c[4],
                        'landline' => $c[5],
                        'email' => $c[6],
                        'date_of_birth' => $c[7],
                        'tax_number' => $c[8],
                        'opening_balance' => (float)$c[9] ? (float)$c[9] : 0,
                        'address' => $c[10],
                        'city' => $c[11],
                        'state' => $c[12],
                        'country' => $c[13],
                        'zip_code' => $c[14],
                        'shipping_address' => $c[15],
                        'pay_term_number' => (float)$c[16],
                        'pay_term' => (float)$c[17],
                        'total_sale_due' => (float)$c[9] ? (float)$c[9] : 0,
                    ]);

                    if ((float)$c[9] && (float)$c[9]>= 0) {
                        $addCustomerLedger = new CustomerLedger();
                        $addCustomerLedger->customer_id = $addCustomer->id;
                        $addCustomerLedger->row_type = 3;
                        $addCustomerLedger->amount = (float)$c[9];
                        $addCustomerLedger->save();
                    }
                }
            }
            $index++;
        }
    }
}
