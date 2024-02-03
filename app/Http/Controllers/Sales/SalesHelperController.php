<?php

namespace App\Http\Controllers\Sales;

use App\Enums\SaleStatus;
use Illuminate\Http\Request;
use App\Enums\SaleScreenType;
use App\Enums\PrintPageSize;
use App\Services\Sales\SaleService;
use App\Http\Controllers\Controller;
use App\Services\Sales\SaleProductService;
use App\Services\Sales\SalesHelperService;

class SalesHelperController extends Controller
{
    public function __construct(
        private SaleService $saleService,
        private SalesHelperService $salesHelperService,
        private SaleProductService $saleProductService,
    ) {
    }

    public function posSelectableProducts(Request $request)
    {
        $products = $this->salesHelperService->getPosSelectableProducts($request);

        return view('sales.pos.ajax_view.selectable_product_list', compact('products'));
    }

    public function recentTransactionModal($initialStatus, $saleScreenType, $limit = null)
    {

        $sales = $this->salesHelperService->recentSales(status: $initialStatus, saleScreenType: $saleScreenType, limit: $limit);

        return view('sales.recent_transactions.index_modal', compact('sales', 'saleScreenType'));
    }

    public function recentSales($status, $saleScreenType, $limit = null)
    {
        $sales = $this->salesHelperService->recentSales(status: $status, saleScreenType: $saleScreenType, limit: $limit);

        return view('sales.recent_transactions.recent_sale_list', compact('sales'));
    }

    public function holdInvoicesModal($limit = null)
    {

        $holdInvoices = $this->salesHelperService->recentSales(status: SaleStatus::Hold->value, saleScreenType: SaleScreenType::PosSale->value, limit: $limit);

        if (count($holdInvoices) == 0) {

            return response()->json(['errorMsg' => __('Hold Invoice is not available right now.')]);
        }

        return view('sales.hold_invoices.index_modal', compact('holdInvoices'));
    }

    public function suspendedModal($limit = null)
    {

        $suspendedInvoices = $this->salesHelperService->recentSales(status: SaleStatus::Suspended->value, saleScreenType: SaleScreenType::PosSale->value, limit: $limit);

        if (count($suspendedInvoices) == 0) {

            return response()->json(['errorMsg' => __('Suspended Invoice is not available right now.')]);
        }

        return view('sales.suspended_invoices.index_modal', compact('suspendedInvoices'));
    }

    public function productStockModal()
    {

        $productStocks = $this->salesHelperService->productStocks();

        return view('sales.product_stocks.index_modal', compact('productStocks'));
    }

    public function salesRelatedVoucherPrint($saleId, Request $request)
    {
        $printPageSize = $request->print_page_size;

        $sale = $this->saleService->singleSale(id: $saleId, with: [
            'branch',
            'branch.parentBranch',
            'customer',
            'saleProducts',
            'saleProducts.product',
        ]);

        if ($sale->status != SaleStatus::Final->value && $request->print_page_size == PrintPageSize::PosPrinterPageThreeIncs->value) {

            return response()->json(['errorMsg' => __('Pos printer page size only for Final Sale.')]);
        }

        $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $sale->id);

        if ($sale->status == SaleStatus::Final->value) {

            $changeAmount = 0;
            return view('sales.print_templates.sale_print', compact('sale', 'changeAmount', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($sale->status == SaleStatus::Draft->value) {

            $draft = $sale;
            return view('sales.print_templates.draft_print', compact('draft', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($sale->status == SaleStatus::Quotation->value) {

            $quotation = $sale;
            return view('sales.print_templates.quotation_print', compact('quotation', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($sale->status == SaleStatus::Order->value) {

            $order = $sale;
            return view('sales.print_templates.order_print', compact('order', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($sale->status == SaleStatus::Hold->value) {

            $holdInvoice = $sale;
            return view('sales.print_templates.hold_invoice_print', compact('holdInvoice', 'customerCopySaleProducts', 'printPageSize'));
        }
    }

    public function printDeliveryNote($id, Request $request)
    {
        if ($request->print_page_size == PrintPageSize::PosPrinterPageThreeIncs->value) {

            return response()->json(['errorMsg' => __('Pos printer page size does not support for delivery note.')]);
        }

        $printPageSize = $request->print_page_size;
        $sale = $this->saleService->singleSale(id: $id, with: [
            'customer:id,name,phone,address',
            'createdBy:id,prefix,name,last_name',
        ]);

        $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $sale->id);

        return view('sales.print_templates.print_delivery_note', compact('sale', 'customerCopySaleProducts', 'printPageSize'));
    }

    public function printPackingSlip($id, Request $request)
    {
        $printPageSize = $request->print_page_size;
        $sale = $this->saleService->singleSale(id: $id, with: [
            'customer:id,name,phone,address',
            'createdBy:id,prefix,name,last_name',
        ]);

        if ($request->print_page_size == PrintPageSize::PosPrinterPageThreeIncs->value) {

            return response()->json(['errorMsg' => __('Pos printer page size does not support for packing slip.')]);
        }

        if ($sale->status != SaleStatus::Final->value) {

            return response()->json(['errorMsg' => __('Invoice yet not to be available. Please created an invoice first.')]);
        }

        $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $sale->id);

        return view('sales.print_templates.print_packing_slip', compact('sale', 'customerCopySaleProducts', 'printPageSize'));
    }
}
