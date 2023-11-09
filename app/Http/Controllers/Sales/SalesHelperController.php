<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Services\Sales\SalesHelperService;
use Illuminate\Http\Request;

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

    public function recentTransactionModal($initialStatus, $saleScreenType, $limit = null) {

        $sales = $this->salesHelperService->recentSales(status: $initialStatus, saleScreenType: $saleScreenType, limit: $limit);
        return view('sales.recent_transactions.index_modal', compact('sales', 'saleScreenType'));
    }

    public function recentSales($status, $saleScreenType, $limit = null) {
        
        $sales = $this->salesHelperService->recentSales(status: $status, saleScreenType: $saleScreenType, limit: $limit);
        return view('sales.recent_transactions.recent_sale_list', compact('sales'));
    }
}
