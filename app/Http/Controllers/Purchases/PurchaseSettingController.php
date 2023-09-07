<?php

namespace App\Http\Controllers\Purchases;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\GeneralSettingServiceInterface;

class PurchaseSettingController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('purchase_settings')) {

            abort(403, 'Access Forbidden.');
        }

        return view('purchase.purchases.settings.index');
    }

    //Show Change status modal
    public function update(Request $request, GeneralSettingServiceInterface $generalSettingService)
    {
        if (!auth()->user()->can('purchase_settings')) {

            abort(403, 'Access Forbidden.');
        }
        
        $settings = [
            'purchase__is_edit_pro_price' => $request->is_edit_pro_price,
            'purchase__is_enable_lot_no' => $request->is_enable_lot_no
        ];

        $generalSettingService->updateAndSync($settings);

        return response()->json(__("Purchase settings updated successfully."));
    }
}
