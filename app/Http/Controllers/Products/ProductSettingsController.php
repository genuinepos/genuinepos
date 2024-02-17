<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Services\GeneralSettingServiceInterface;
use App\Services\Products\ProductSettingsService;
use App\Services\Products\UnitService;
use Illuminate\Http\Request;

class ProductSettingsController extends Controller
{
    public function __construct(
        private ProductSettingsService $productSettingsService,
        private UnitService $unitService
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index()
    {
        if (!auth()->user()->can('product_settings')) {

            abort(403, __('Access Forbidden.'));
        }

        $units = $this->unitService->units()->get();

        return view('product.products.settings.index', compact('units'));
    }

    public function update(Request $request, GeneralSettingServiceInterface $generalSettingService)
    {
        if (!auth()->user()->can('product_settings')) {

            abort(403, __('Access Forbidden.'));
        }

        $updateProductSettings = $this->productSettingsService->updateProductSettings(request: $request, generalSettingService: $generalSettingService);

        return response()->json(__('Product settings updated successfully'));
    }
}
