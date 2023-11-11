<?php

namespace App\Http\Controllers\Sales;

use App\Enums\SaleScreenType;
use App\Enums\SaleStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Sales\SaleProductService;
use App\Services\Sales\SalesHelperService;

class SalesHelperController extends Controller
{
    public function __construct(
        private SalesHelperService $salesHelperService,
        private SaleProductService $saleProductService,
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

    public function holdInvoicesModal($limit = null) {

        $holdInvoices = $this->salesHelperService->recentSales(status: SaleStatus::Hold->value, saleScreenType: SaleScreenType::PosSale->value, limit: $limit);

        if (count($holdInvoices) == 0) {

            return response()->json(['errorMsg' => __('Hold Invoice is not available right now.')]);
        }

        return view('sales.hold_invoices.index_modal', compact('holdInvoices'));
    }

    public function suspendedModal($limit = null) {

        $suspendedInvoices = $this->salesHelperService->recentSales(status: SaleStatus::Suspended->value, saleScreenType: SaleScreenType::PosSale->value, limit: $limit);

        if (count($suspendedInvoices) == 0) {

            return response()->json(['errorMsg' => __('Suspended Invoice is not available right now.')]);
        }

        return view('sales.suspended_invoices.index_modal', compact('suspendedInvoices'));
    }

    function salesPrint($saleId) {

        $sale = $this->salesHelperService->sale(saleId:$saleId);

        $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $sale->id);

        if ($sale->status == SaleStatus::Final->value) {

            $changeAmount = 0;
            return view('sales.save_and_print_template.sale_print', compact('sale', 'changeAmount', 'customerCopySaleProducts'));
        } elseif ($sale->status == SaleStatus::Draft->value) {

            $draft = $sale;
            return view('sales.save_and_print_template.draft_print', compact('draft', 'customerCopySaleProducts'));
        } elseif ($sale->status == SaleStatus::Quotation->value) {

            $quotation = $sale;
            return view('sales.save_and_print_template.quotation_print', compact('quotation', 'customerCopySaleProducts'));
        } elseif ($sale->status == SaleStatus::Order->value) {

            $order = $sale;
            return view('sales.save_and_print_template.order_print', compact('order', 'customerCopySaleProducts'));
        }elseif ($sale->status == SaleStatus::Hold->value) {

            $holdInvoice = $sale;
            return view('sales.save_and_print_template.hold_invoice_print', compact('holdInvoice', 'customerCopySaleProducts'));
        }
    }
}
