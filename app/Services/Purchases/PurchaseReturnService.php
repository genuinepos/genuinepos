<?php

namespace App\Services\Purchases;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Purchases\PurchaseReturn;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseReturnService
{
    public function restrictions(object $request): array
    {
        if (!isset($request->product_ids)) {

            return ['pass' => false, 'msg' => __("Product table is empty.")];
        } elseif (count($request->product_ids) > 60) {

            return ['pass' => false, 'msg' => __("Purchase invoice products must be less than 60 or equal.")];
        }

        if ($request->total_qty == 0) {

            return ['pass' => false, 'msg' => 'All product`s quantity is 0.'];
        }

        return ['pass' => true];
    }

    function addPurchaseReturn(object $request, object $codeGenerator, ?string $voucherPrefix = null): object
    {
        // generate invoice ID
        $voucherNo = $codeGenerationService->generateMonthWise(table: 'purchase_returns', column: 'voucher_no', prefix: $voucherPrefix, splitter: '-', suffixSeparator: '-');

        $addPurchaseReturn = new PurchaseReturn();
        $addPurchaseReturn->voucher_no = $voucherNo;
        $addPurchaseReturn->purchase_id = $request->purchase_id;
        $addPurchaseReturn->supplier_account_id = $request->supplier_account_id;
        $addPurchaseReturn->purchase_account_id = $request->purchase_account_id;
        $addPurchaseReturn->total_item = $request->total_item;
        $addPurchaseReturn->total_qty = $request->total_qty;
        $addPurchaseReturn->net_total_amount = $request->net_total_amount;
        $addPurchaseReturn->return_discount = $request->return_discount ? $request->return_discount : 0;
        $addPurchaseReturn->return_discount_type = $request->return_discount_type;
        $addPurchaseReturn->return_discount_amount = $request->return_discount_amount ? $request->return_discount_amount : 0;
        $addPurchaseReturn->return_tax_ac_id = $request->return_tax_ac_id;
        $addPurchaseReturn->return_tax_percent = $request->return_tax_percent ? $request->return_tax_percent : 0;
        $addPurchaseReturn->return_tax_amount = $request->return_tax_amount ? $request->return_tax_amount : 0;
        $addPurchaseReturn->total_return_amount = $request->total_return_amount ? $request->total_return_amount : 0;
        $addPurchaseReturn->date = $request->date;
        $addPurchaseReturn->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addPurchaseReturn->note = $request->note;
        $addPurchaseReturn->created_by_id = auth()->user()->id;
        $addPurchaseReturn->save();

        return $addPurchaseReturn;
    }

    public function singlePurchaseReturn(int $id, ?array $with = null): ?object
    {
        $query = PurchaseReturn::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function adjustPurchaseReturnVoucherAmounts($purchaseReturn)
    {
        $totalReceived = DB::table('voucher_description_references')
            ->where('voucher_description_references.purchase_return_id', $purchaseReturn->id)
            ->select(DB::raw('sum(voucher_description_references.amount) as total_received'))
            ->groupBy('voucher_description_references.purchase_return_id')
            ->get();

        // $totalReturnPaid = DB::table('purchase_payments')
        //     ->where('purchase_payments.purchase_id', $purchaseReturn->purchase_id)
        //     ->where('purchase_payments.payment_type', 2)
        //     ->select(DB::raw('sum(paid_amount) as total_paid'))
        //     ->groupBy('purchase_payments.purchase_id')
        //     ->get();

        $due = $purchaseReturn->total_return_amount - $totalReceived->sum('total_received');
        $purchaseReturn->received_amount = $totalReturnPaid->sum('total_received');
        $purchaseReturn->due = $due;
        $purchaseReturn->save();
    }

}
