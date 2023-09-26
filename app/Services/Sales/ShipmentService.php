<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Models\Sales\Sale;
use App\Enums\PaymentStatus;
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
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->where('sales.shipment_status', '!=', 0);

        $this->filteredQuery($request, $query);

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            if (auth()->user()->can('view_own_sale')) {

                $query->where('sales.created_by_id', auth()->user()->id);
            }

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
            'sales.date',
            'sales.total_item',
            'sales.total_qty',
            'sales.total_invoice_amount',
            'sales.sale_return_amount',
            'sales.paid as received_amount',
            'sales.due',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'customers.name as customer_name',
        )->orderBy('sales.date_ts', 'desc');

        return DataTables::of($shipments)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __("Action") . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                if (auth()->user()->can('shipment_access')) {

                    $html .= '<a class="dropdown-item" id="editShipmentDetails" href="' . route('sale.shipments.edit', [$row->id]) . '">' . __("Edit Shipment Details") . '</a>';
                }

                if (auth()->user()->can('shipment_access')) {

                    $html .= '<a href="' . route('sale.shipments.print.packing.slip', [$row->id]) . '" class="dropdown-item" id="printPackingSlipBtn">' . __("Print Packing Slip") . '</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business__date_format']);

                return date($__date_format, strtotime($row->date));
            })
            ->editColumn('transaction_id', function ($row) {

                if ($row->status == SaleStatus::Final->value) {

                    return '<a href="' . route('sales.show', [$row->id]) . '" id="details_btn">Sale: ' . $row->invoice_id . '</a>';
                } else if ($row->status == SaleStatus::Quotation->value) {

                    return '<a href="' . route('sale.quotations.show', [$row->id]) . '" id="details_btn">Quotation: ' . $row->quotation_id . '</a>';
                } else if ($row->status == SaleStatus::Order->value) {

                    return '<a href="' . route('sale.orders.show', [$row->id]) . '" id="details_btn">Order: ' . $row->order_id . '</a>';
                }
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')';
                    }
                } else {

                    return $generalSettings['business__shop_name'];
                }
            })
            ->editColumn('shipment_status', fn ($row) => '<a id="editShipmentDetails" href="' . route('sale.shipments.edit', [$row->id]) . '">' . ShipmentStatus::tryFrom($row->shipment_status)->name . '</a>')
            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('payment_status', function ($row) {

                $receivable = $row->total_invoice_amount - $row->sale_return_amount;

                if ($row->due <= 0) {

                    return '<span class="text-success"><b>' . __("Paid") . '</span>';
                } elseif ($row->due > 0 && $row->due < $receivable) {

                    return '<span class="text-primary"><b>' . __("Partial") . '</b></span>';
                } elseif ($receivable == $row->due) {

                    return '<span class="text-danger"><b>' . __("Due") . '</b></span>';
                }
            })

            ->editColumn('current_status', fn ($row) =>  SaleStatus::tryFrom($row->status)->name)

            ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . \App\Utils\Converter::format_in_bdt($row->total_item) . '</span>')

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>')

            ->editColumn('total_invoice_amount', fn ($row) => '<span class="total_invoice_amount" data-value="' . $row->total_invoice_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_invoice_amount) . '</span>')

            ->editColumn('received_amount', fn ($row) => '<span class="paid received_amount-success" data-value="' . $row->received_amount . '">' . \App\Utils\Converter::format_in_bdt($row->received_amount) . '</span>')

            ->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="' . $row->due . '">' . \App\Utils\Converter::format_in_bdt($row->due) . '</span>')

            ->rawColumns(['action', 'date', 'total_item', 'total_qty', 'total_invoice_amount', 'received_amount', 'transaction_id', 'branch', 'customer', 'due', 'payment_status', 'current_status', 'shipment_status'])
            ->make(true);
    }

    function updateShipmentDetails(object $request, int $id): object
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
            } else if ($request->payment_status == PaymentStatus::Partial->value) {

                $query->where('sales.paid', '>', 0)->where('sales.due', '>', 0);
            } else if ($request->payment_status == PaymentStatus::Due->value) {

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
