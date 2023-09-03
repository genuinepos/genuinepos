<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Products\UnitService;
use App\Services\GeneralSettingServiceInterface;
use App\Services\Products\ProductSettingsService;

class ProductSettingsController extends Controller
{
    public function __construct(
        private ProductSettingsService $productSettingsService,
        private UnitService $unitService
    ) {
    }

    public function index()
    {
        $units = $this->unitService->units()->get();

        return view('product.products.settings.index', compact('units'));
    }

    public function update(Request $request, GeneralSettingServiceInterface $generalSettingService)
    {
        $updateProductSettings = $this->productSettingsService->updateProductSettings(request: $request, generalSettingService: $generalSettingService);
        
        return response()->json(__("Product settings updated successfully"));
    }
}
