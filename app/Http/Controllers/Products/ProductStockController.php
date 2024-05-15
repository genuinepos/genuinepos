<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Products\ProductStockService;

class ProductStockController extends Controller
{
    public function __construct(
        private ProductStockService $productStockService,
    ) {
    }

    public function productStock($id, Request $request)
    {
        return $this->productStockService->productStock(id: $id, request: $request);
    }
}
