<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderReceiveController extends Controller
{
    public function processReceive($purchaseId)
    {
        $purchase = Purchase::with([
            'supplier:id,name,phone',
            'purchase_order_products',
            'purchase_order_products.product',
            'purchase_order_products.variant',
        ])->where('id', $purchaseId)->first();
        $warehouses = DB::table('warehouses')->where('branch_id', auth()->user()->branch_id)->get();
        $accounts = DB::table('accounts')->select('id', 'name', 'account_number')->get();
        return view('purchases.order_receive.process_to_receive', compact('purchase', 'warehouses', 'accounts'));
    }

    public function processReceiveStore(Request $request, $purchaseId)
    {
        
        // $purchase = 

    }
}
