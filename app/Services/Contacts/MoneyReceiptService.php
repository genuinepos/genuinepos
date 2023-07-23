<?php

namespace App\Services\Contacts;

use App\Models\Contacts\MoneyReceipt;

class MoneyReceiptService
{
    public function addMoneyReceipt($contactId, $request, $codeGenerator){

        $voucherPrefix = auth()?->user()?->branch ? auth()?->user()?->branch->branch_code : 'MB';

        $voucherNo = $codeGenerator->generateMonthWise(table: 'money_receipts', column: 'voucher_no', prefix: $voucherPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()?->user()?->branch_id);
        // dd($voucherNo);
        $addMoneyReceipt = new MoneyReceipt();
        $addMoneyReceipt->voucher_no = $voucherNo;
        $addMoneyReceipt->contact_id = $contactId;
        $addMoneyReceipt->branch_id = auth()->user()->branch_id;
        $addMoneyReceipt->amount = $request->amount;
        $addMoneyReceipt->note = $request->note;
        $addMoneyReceipt->receiver = $request->receiver;
        $addMoneyReceipt->ac_details = $request->ac_details;
        $addMoneyReceipt->is_date = $request->is_date;
        $addMoneyReceipt->is_customer_name = $request->is_customer_name;
        $addMoneyReceipt->is_header_less = $request->is_header_less;
        $addMoneyReceipt->gap_from_top = $request->is_header_less == 1 ? $request->gap_from_top : NULL;
        $addMoneyReceipt->date_ts = date('Y-m-d');
        $addMoneyReceipt->save();

        return $addMoneyReceipt;
    }

    public function singleMoneyReceipt(int $id, array $with = null) {

        $query = MoneyReceipt::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
