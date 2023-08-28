<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Services\GeneralSettingServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddSaleSettingController extends Controller
{
    // Get notification form method
    public function edit()
    {
        if (! auth()->user()->can('add_sale_settings')) {

            abort(403, 'Access Forbidden.');
        }

        $priceGroups = DB::table('price_groups')->where('status', 'Active')->get();

        return view('sales.settings.add_sale.edit', compact('priceGroups'));
    }

    // Add tax settings
    public function update(Request $request, GeneralSettingServiceInterface $generalSettingService)
    {
        if (! auth()->user()->can('add_sale_settings')) {

            return response()->json('Asses Forbidden.');
        }

        $settings = [
            'sale__default_sale_discount' => $request->default_sale_discount,
            'sale__sales_commission' => $request->sales_commission,
            'sale__default_price_group_id' => $request->default_price_group_id,
        ];

        $generalSettingService->updateAndSync($settings);

        return response()->json(__('Sale settings updated successfully'));
    }
}
