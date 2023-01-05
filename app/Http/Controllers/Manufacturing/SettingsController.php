<?php

namespace App\Http\Controllers\Manufacturing;

use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Services\CacheServiceInterface;

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
    public function store(Request $request, CacheServiceInterface $cacheService)
    {
        if (!auth()->user()->can('manuf_settings')) {
            return response()->json('Access Denied');
        }

        $settings = [
            'production_ref_prefix' => $request->production_ref_prefix,
            'enable_editing_ingredient_qty' => isset($request->enable_editing_ingredient_qty) ? 1 : 0,
            'enable_updating_product_price' => isset($request->enable_updating_product_price) ? 1 : 0,
        ];
        GeneralSetting::query()->update([
            'mf_settings' => $settings,
        ]);
        $cacheService->syncGeneralSettings();
        return response()->json('Manufacturing settings updated successfully');
    }
}
