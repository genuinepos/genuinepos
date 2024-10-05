<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Models\Sales\Sale;
use App\Enums\PaymentStatus;
use App\Enums\SaleScreenType;
use App\Enums\ShipmentStatus;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ShipmentService
{
    public function shipmentListTable($request)
    {
        $generalSettings = config('generalSettings');
        $shipments = '';

        $query = DB::table('sales')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('sales as salesOrder', 'sales.sales_order_id', 'salesOrder.id')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('currencies', 'branches.currency_id', 'currencies.id')
            ->where('sales.status', SaleStatus::Final->value)
            ->where('sales.shipment_status', '!=', BooleanType::False->value)
            ;

        $this->filteredQuery($request, $query);

        if (auth()->user()->can('view_only_won_transactions')) {

            $query->where('sales.created_by_id', auth()->user()->id);
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }

        $shipments = $query->select(
            'sales.id',
            'sales.branch_id',
            'sales.invoice_id',
            'sales.quotation_id',
            'sales.order_id',
            'sales.status',
            'sales.shipment_status',
            'sales.sale_screen',
            'sales.date',
            'sales.total_item',
            'sales.total_qty',
            'sales.total_invoice_amount',
            'sales.sale_return_amount',
            'sales.paid as received_amount',
            'sales.due',

            'salesOrder.id as sales_order_id',
            'salesOrder.order_id as sale_order_voucher_no',

            'branches.name as branch_name',
            'branches.area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',

            'customers.name as customer_name',
            'currencies.currency_rate as c_rate'
        )->orderBy('sales.date_ts', 'desc');

        return DataTables::of($shipments)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                if (auth()->user()->can('shipment_access')) {

                    $html .= '<a class="dropdown-item" id="editShipmentDetails" href="' . route('sale.shipments.edit', [$row->id]) . '">' . __('Edit Shipment Details') . '</a>';
                }

                if ($row->sale_screen == SaleScreenType::AddSale->value) {

                    if (auth()->user()->can('sales_edit')) {

                        $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                    }
                } elseif ($row->sale_screen == SaleScreenType::PosSale->value || $row->sale_screen == SaleScreenType::ServicePosSale->value) {

                    if (auth()->user()->can('sales_edit')) {

                        $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id, $row->sale_screen]) . '">' . __('Edit') . '</a>';
                    }
                }

                // if (auth()->user()->can('shipment_access')) {

                //     $html .= '<a href="' . route('sale.shipments.print.packing.slip', [$row->id]) . '" class="dropdown-item" id="printPackingSlipBtn">' . __('Print Packing Slip') . '</a>';
                // }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                return date($__date_format, strtotime($row->date));
            })

            ->editColumn('transaction_id', function ($row) {

                $link = '';
                if ($row->status == SaleStatus::Final->value) {

                    $link .= '<a href="' . route('sales.show', [$row->id]) . '" id="details_btn" class="d-block" style="line-height:1.5!important;">Sale: ' . $row->invoice_id . '</a>';
                } elseif ($row->status == SaleStatus::Quotation->value) {

                    $link .= '<a href="' . route('sale.quotations.show', [$row->id]) . '" id="details_btn" class="d-block" style="line-height:1.5!important;">Quotation: ' . $row->quotation_id . '</a>';
                } elseif ($row->status == SaleStatus::Order->value) {

                    $link .= '<a href="' . route('sale.orders.show', [$row->id]) . '" id="details_btn" class="d-block" style="line-height:1.5!important;">Order: ' . $row->order_id . '</a>';
                }

                if ($row->sales_order_id) {

                    $link .= '<span class="p-0 m-0 d-block" style="line-height:1.5!important;font-size:11px;">' . __("S/O") . ':<a href="' . route('sale.orders.show', [$row->sales_order_id]) . '" id="details_btn">' . $row->sale_order_voucher_no . '</a></span>';
                }

                return $link;
            })

            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })

            ->editColumn('shipment_status', fn($row) => '<a id="editShipmentDetails" href="' . route('sale.shipments.edit', [$row->id]) . '">' . ShipmentStatus::tryFrom($row->shipment_status)->name . '</a>')
            ->editColumn('customer', fn($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('payment_status', function ($row) {

                $receivable = $row->total_invoice_amount - $row->sale_return_amount;

                if ($row->due <= 0) {

                    return '<span class="text-success"><b>' . __('Paid') . '</span>';
                } elseif ($row->due > 0 && $row->due < $receivable) {

                    return '<span class="text-primary"><b>' . __('Partial') . '</b></span>';
                } elseif ($receivable == $row->due) {

                    return '<span class="text-danger"><b>' . __('Due') . '</b></span>';
                }
            })

            ->editColumn('current_status', fn($row) => SaleStatus::tryFrom($row->status)->name)

            ->editColumn('total_item', fn($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . \App\Utils\Converter::format_in_bdt($row->total_item) . '</span>')

            ->editColumn('total_qty', fn($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>')

            ->editColumn('total_invoice_amount', fn($row) => '<span class="total_invoice_amount" data-value="' . curr_cnv($row->total_invoice_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->total_invoice_amount, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('received_amount', fn($row) => '<span class="paid received_amount text-success" data-value="' . curr_cnv($row->received_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->received_amount, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('due', fn($row) => '<span class="due text-danger" data-value="' . curr_cnv($row->due, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->due, $row->c_rate, $row->branch_id)) . '</span>')

            ->rawColumns(['action', 'date', 'total_item', 'total_qty', 'total_invoice_amount', 'received_amount', 'transaction_id', 'branch', 'customer', 'due', 'payment_status', 'current_status', 'shipment_status'])
            ->make(true);
    }

    public function updateShipmentDetails(object $request, int $id): object
    {
        $sale = Sale::where('id', $id)->first();
        $sale->shipment_details = $request->shipment_details;
        $sale->shipment_address = $request->shipment_address;
        $sale->shipment_status = $request->shipment_status;
        $sale->delivered_to = $request->delivered_to;
        $sale->save();

        return $sale;
    }

    private function filteredQuery($request, $query)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('sales.branch_id', null);
            } else {

                $query->where('sales.branch_id', $request->branch_id);
            }
        }

        if ($request->customer_account_id) {

            if ($request->customer_id == 'NULL') {

                $query->where('sales.customer_account_id', null);
            } else {

                $query->where('sales.customer_account_id', $request->customer_account_id);
            }
        }

        if ($request->payment_status) {

            if ($request->payment_status == PaymentStatus::Paid->value) {

                $query->where('sales.due', '=', 0);
            } elseif ($request->payment_status == PaymentStatus::Partial->value) {

                $query->where('sales.paid', '>', 0)->where('sales.due', '>', 0);
            } elseif ($request->payment_status == PaymentStatus::Due->value) {

                $query->where('sales.paid', '=', 0);
            }
        }

        if ($request->shipment_status) {

            $query->where('sales.shipment_status', $request->shipment_status);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.date_ts', $date_range); // Final
        }

        return $query;
    }
}
