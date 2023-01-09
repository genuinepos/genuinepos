<?php

namespace App\Http\Controllers\Manufacturing;

use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Services\GeneralSettingServiceInterface;

class SettingsController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        if (!auth()->user()->can('manuf_settings')) {
            abort(403, 'Access Forbidden.');
        }

        return view('manufacturing.settings.index');
    }

    // Add tax settings
    public function store(Request $request, GeneralSettingServiceInterface $generalSettingService)
    {
        if (!auth()->user()->can('manuf_settings')) {
            return response()->json('Access Denied');
        }

        $settings = [
            'mf_settings__production_ref_prefix' => $request->production_ref_prefix,
            'mf_settings__enable_editing_ingredient_qty' => isset($request->enable_editing_ingredient_qty) ? 1 : 0,
            'mf_settings__enable_updating_product_price' => isset($request->enable_updating_product_price) ? 1 : 0,
        ];
        $generalSettingService->updateAndSync($settings);
        return response()->json('Manufacturing settings updated successfully');
    }
}
