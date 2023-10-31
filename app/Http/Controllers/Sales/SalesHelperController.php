<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Sales\SalesHelperService;

class SalesHelperController extends Controller
{
    public function __construct(
        private SalesHelperService $salesHelperService,
    ) {
    }

    public function posSelectableProducts(Request $request)
    {
        $products = $this->salesHelperService->getPosSelectableProducts($request);
        return view('sales.pos.ajax_view.selectable_product_list', compact('products'));
    }
}
