<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use App\Enums\RoleType;
use App\Enums\BooleanType;
use App\Models\Sales\Sale as SalesOrder;
use App\Enums\PaymentStatus;
use App\Enums\OrderDeliveryStatus;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalesOrderService
{
    public function salesOrderListTable(object $request, int $customerAccountId = null)
    {
        $generalSettings = config('generalSettings');
        $orders = '';

        $query = DB::table('sales')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('currencies', 'branches.currency_id', 'currencies.id')
            ->leftJoin('users as created_by', 'sales.created_by_id', 'created_by.id')
            ->where('sales.order_status', BooleanType::True->value);

        $this->filteredQuery(request: $request, query: $query, customerAccountId: $customerAccountId);

        $orders = $query->select(
            'sales.id',
            'sales.branch_id',
            'sales.order_id',
            'sales.quotation_id',
            'sales.date',
            'sales.total_item',
            'sales.total_ordered_qty',
            'sales.total_delivered_qty',
            'sales.total_left_qty',
            'sales.order_delivery_status',
            'sales.total_invoice_amount',
            'sales.paid as received_amount',
            'sales.due',
            'branches.name as branch_name',
            'branches.area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'customers.name as customer_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
            'currencies.currency_rate as c_rate'
        )->orderBy('sales.order_date_ts', 'desc');

        return DataTables::of($orders)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="' . route('sale.orders.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('sales_order_to_invoice')) {

                        $html .= '<a class="dropdown-item" href="' . route('sales.order.to.invoice.create', [$row->id]) . '">' . __('Sales Order To Invoice') . '</a>';
                    }
                }

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('sales_orders_edit')) {

                        $html .= '<a class="dropdown-item" href="' . route('sale.orders.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                    }
                }

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('sales_orders_delete')) {

                        $html .= '<a href="' . route('sale.orders.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
                    }
                }

                if (auth()->user()->can('shipment_access')) {

                    $html .= '<a class="dropdown-item" id="editShipmentDetails" href="' . route('sale.shipments.edit', [$row->id]) . '">' . __('Edit Shipment Details') . '</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                return date($__date_format, strtotime($row->date));
            })
            ->editColumn('order_id', function ($row) {

                // return '<a href="' . route('sale.orders.show', [$row->id]) . '" id="details_btn">' . $row->order_id . '</a>';

                $link = '';
                $link .= '<a href="' . route('sale.orders.show', [$row->id]) . '" id="details_btn" class="d-block" style="line-height:1.5!important;">' . $row->order_id . '</a>';

                if ($row->quotation_id) {

                    $link .= '<span class="p-0 m-0 d-block" style="line-height:1.5!important;font-size:11px;">' . __("Q") . ':<a href="' . route('sale.orders.show', [$row->id]) . '" id="details_btn">' . $row->quotation_id . '</a></span>';
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
            ->editColumn('customer', fn($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('total_item', fn($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . \App\Utils\Converter::format_in_bdt($row->total_item) . '</span>')

            ->editColumn('total_ordered_qty', fn($row) => '<span class="total_ordered_qty" data-value="' . $row->total_ordered_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_ordered_qty) . '</span>')

            ->editColumn('total_delivered_qty', fn($row) => '<span class="total_delivered_qty" data-value="' . $row->total_delivered_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_delivered_qty) . '</span>')

            ->editColumn('total_left_qty', fn($row) => '<span class="total_left_qty" data-value="' . $row->total_left_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_left_qty) . '</span>')

            ->editColumn('total_invoice_amount', fn($row) => '<span class="total_invoice_amount" data-value="' . curr_cnv($row->total_invoice_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->total_invoice_amount, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('received_amount', fn($row) => '<span class="received_amount text-success" data-value="' . curr_cnv($row->received_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->received_amount, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('due', function ($row) {

                if ($row->due < 0) {

                    return '(<span class="due text-danger" data-value="' . curr_cnv($row->due, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(abs(curr_cnv($row->due, $row->c_rate, $row->branch_id))) . '</span>)';
                } else {

                    return '<span class="due text-danger" data-value="' . curr_cnv($row->due, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->due, $row->c_rate, $row->branch_id)) . '</span>';
                }
            })

            ->editColumn('payment_status', function ($row) {

                $receivable = $row->total_invoice_amount;

                if ($row->due <= 0) {

                    return '<span class="text-success"><b>' . __('Paid') . '</span>';
                } elseif ($row->due > 0 && $row->due < $receivable) {

                    return '<span class="text-primary"><b>' . __('Partial') . '</b></span>';
                } elseif ($receivable == $row->due) {

                    return '<span class="text-danger"><b>' . __('Due') . '</b></span>';
                }
            })

            ->editColumn('delivery_status', function ($row) {

                $receivable = $row->total_invoice_amount;

                if ($row->order_delivery_status == OrderDeliveryStatus::Pending->value) {

                    return '<span class="text-danger"><b>' . __('Pending') . '</span>';
                } elseif ($row->order_delivery_status == OrderDeliveryStatus::Partial->value) {

                    return '<span class="text-primary"><b>' . __('Partial') . '</b></span>';
                } elseif ($row->order_delivery_status == OrderDeliveryStatus::Completed->value) {

                    return '<span class="text-success"><b>' . __('Completed') . '</b></span>';
                }
            })

            ->editColumn('created_by', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })

            ->rawColumns(['action', 'date', 'total_item', 'total_ordered_qty', 'total_delivered_qty', 'total_left_qty', 'delivery_status', 'total_invoice_amount', 'received_amount', 'order_id', 'branch', 'customer', 'due', 'payment_status', 'created_by'])
            ->make(true);
    }

    public function updateSalesOrder(object $request, object $updateSalesOrder): object
    {
        foreach ($updateSalesOrder->saleProducts as $saleProduct) {

            $saleProduct->is_delete_in_update = BooleanType::True->value;
            $saleProduct->save();
        }

        $updateSalesOrder->sale_account_id = $request->sale_account_id;
        $updateSalesOrder->customer_account_id = $request->customer_account_id;
        $updateSalesOrder->pay_term = $request->pay_term;
        $updateSalesOrder->pay_term_number = $request->pay_term_number;
        $updateSalesOrder->date = $request->date;
        $time = date(' H:i:s', strtotime($updateSalesOrder->date_ts));
        $updateSalesOrder->date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        $updateSalesOrder->order_date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        $updateSalesOrder->total_item = $request->total_item ? $request->total_item : 0;
        $updateSalesOrder->total_qty = $request->total_qty ? $request->total_qty : 0;
        $updateSalesOrder->total_ordered_qty = $request->total_qty ? $request->total_qty : 0;
        $updateSalesOrder->net_total_amount = $request->net_total_amount ? $request->net_total_amount : 0;
        $updateSalesOrder->order_discount_type = $request->order_discount_type;
        $updateSalesOrder->order_discount = $request->order_discount ? $request->order_discount : 0;
        $updateSalesOrder->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0;
        $updateSalesOrder->sale_tax_ac_id = $request->sale_tax_ac_id;
        $updateSalesOrder->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0;
        $updateSalesOrder->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $updateSalesOrder->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $updateSalesOrder->shipment_details = $request->shipment_details;
        $updateSalesOrder->shipment_address = $request->shipment_address;
        $updateSalesOrder->shipment_status = $request->shipment_status ? $request->shipment_status : 0;
        $updateSalesOrder->delivered_to = $request->delivered_to;
        $updateSalesOrder->note = $request->note;
        $updateSalesOrder->total_invoice_amount = $request->total_invoice_amount ? $request->total_invoice_amount : 0;
        $updateSalesOrder->save();

        return $updateSalesOrder;
    }

    public function calculateDeliveryLeftQty($order)
    {
        $totalDeliveredQty = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->where('sales.sales_order_id', $order->id)
            ->select(DB::raw('SUM(quantity) as total_delivered_qty'))
            ->groupBy('sales.sales_order_id')->get();

        $order->total_delivered_qty = $totalDeliveredQty->sum('total_delivered_qty');
        $totalLeftQty = $order->total_ordered_qty - $order->total_delivered_qty;
        $order->total_left_qty = $totalLeftQty;
        $order->save();

        foreach ($order->saleProducts as $saleProduct) {

            $soldProducts = DB::table('sale_products')
                ->where('product_id', $saleProduct->product_id)
                ->where('variant_id', $saleProduct->variant_id)
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->where('sales.sales_order_id', $order->id)
                ->select(DB::raw('sum(sale_products.quantity) as delivered_qty'))
                ->groupBy('sales.sales_order_id')->get();

            $saleProduct->ordered_quantity = $saleProduct->ordered_quantity;
            $saleProduct->delivered_quantity = $soldProducts->sum('delivered_qty');
            $leftQty = $saleProduct->ordered_quantity - $soldProducts->sum('delivered_qty');
            $saleProduct->left_quantity = $leftQty;
            $saleProduct->save();
        }

        if ($order->total_delivered_qty <= 0) {

            $order->order_delivery_status = OrderDeliveryStatus::Pending->value; // Pending
        } elseif ($order->total_delivered_qty > 0 && $order->total_delivered_qty < $order->total_ordered_qty) {

            $order->order_delivery_status = OrderDeliveryStatus::Partial->value; // Partial
        } elseif ($order->total_delivered_qty >= $order->total_ordered_qty) {

            $order->order_delivery_status = OrderDeliveryStatus::Completed->value; // Completed
        }

        $order->save();
    }

    private function filteredQuery(object $request, object $query, int $customerAccountId = null)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('sales.branch_id', null);
            } else {

                $query->where('sales.branch_id', $request->branch_id);
            }
        }

        if ($request->user_id) {

            $query->where('sales.created_by_id', $request->created_by_id);
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

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.order_date_ts', $date_range); // Final
        }

        if (isset($customerAccountId)) {

            $query->where('sales.customer_account_id', $customerAccountId);
        }

        if (auth()->user()->can('view_only_won_transactions')) {

            $query->where('sales.created_by_id', auth()->user()->id);
        }

        // if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }

    public function singleSalesOrder(int $id, array $with = null): ?object
    {
        $query = SalesOrder::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
