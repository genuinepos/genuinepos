<?php

namespace App\Services\Contacts;

use App\Models\Contacts\MoneyReceipt;

class MoneyReceiptService
{
    public function addMoneyReceipt($contactId, $request, $codeGenerator)
    {

        $voucherPrefix = auth()?->user()?->branch ? auth()?->user()?->branch->branch_code : 'MB';

        $voucherNo = $codeGenerator->generateMonthWise(table: 'money_receipts', column: 'voucher_no', prefix: $voucherPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()?->user()?->branch_id);

        $addMoneyReceipt = new MoneyReceipt();
        $addMoneyReceipt->voucher_no = $voucherNo;
        $addMoneyReceipt->contact_id = $contactId;
        $addMoneyReceipt->branch_id = auth()->user()->branch_id;
        $addMoneyReceipt->amount = $request->amount;
        $addMoneyReceipt->note = $request->note;
        $addMoneyReceipt->receiver = $request->receiver;
        $addMoneyReceipt->ac_details = $request->account_details;
        $addMoneyReceipt->is_date = $request->is_date;
        $addMoneyReceipt->is_customer_name = $request->is_customer_name;
        $addMoneyReceipt->is_header_less = $request->is_header_less;
        $addMoneyReceipt->gap_from_top = $request->is_header_less == 1 ? $request->gap_from_top : null;
        $addMoneyReceipt->date_ts = $request->date ? date('Y-m-d', strtotime($request->date)) : null;
        $addMoneyReceipt->save();

        return $addMoneyReceipt;
    }

    public function updateMoneyReceipt($moneyReceiptId, $request)
    {

        $updateMoneyReceipt = MoneyReceipt::where('id', $moneyReceiptId)->first();
        $updateMoneyReceipt->amount = $request->amount;
        $updateMoneyReceipt->note = $request->note;
        $updateMoneyReceipt->receiver = $request->receiver;
        $updateMoneyReceipt->ac_details = $request->account_details;
        $updateMoneyReceipt->is_date = $request->is_date;
        $updateMoneyReceipt->is_customer_name = $request->is_customer_name;
        $updateMoneyReceipt->is_header_less = $request->is_header_less;
        $updateMoneyReceipt->gap_from_top = $request->is_header_less == 1 ? $request->gap_from_top : null;
        $updateMoneyReceipt->date_ts = $request->date ? date('Y-m-d', strtotime($request->date)) : null;
        $updateMoneyReceipt->save();

        return $updateMoneyReceipt;
    }

    public function deleteMoneyReceipt($receiptId)
    {

        $delete = MoneyReceipt::find($receiptId);

        if (! is_null($delete)) {

            $delete->delete();
        }
    }

    public function singleMoneyReceipt(int $id, array $with = null)
    {

        $query = MoneyReceipt::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
