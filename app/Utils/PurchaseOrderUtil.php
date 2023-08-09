<?php

namespace App\Utils;

use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderUtil
{
    public function poListTable($request)
    {
        $generalSettings = config('generalSettings');
        $purchases = '';
        $query = DB::table('purchases')
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
            ->leftJoin('users as created_by', 'purchases.admin_id', 'created_by.id');

        if (! empty($request->branch_id)) {

            if ($request->branch_id == 'NULL') {

                $query->where('purchases.branch_id', null);
            } else {

                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if (! empty($request->warehouse_id)) {

            $query->where('purchases.warehouse_id', $request->warehouse_id);
        }

        if ($request->supplier_id) {

            $query->where('purchases.supplier_id', $request->supplier_id);
        }

        if ($request->status) {

            $query->where('purchases.purchase_status', $request->status);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        $query->select(
            'purchases.id',
            'purchases.branch_id',
            'purchases.warehouse_id',
            'purchases.date',
            'purchases.invoice_id',
            'purchases.is_return_available',
            'purchases.total_purchase_amount',
            'purchases.purchase_return_amount',
            'purchases.purchase_return_due',
            'purchases.due',
            'purchases.paid',
            'purchases.purchase_status',
            'purchases.po_receiving_status',
            'branches.name as branch_name',
            'branches.branch_code',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
            'suppliers.name as supplier_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $purchases = $query->where('purchases.purchase_status', 3)
                ->orderBy('purchases.report_date', 'desc');
        } else {

            $purchases = $query->where('purchases.branch_id', auth()->user()->branch_id)
                ->where('purchases.purchase_status', 3)
                ->orderBy('purchases.report_date', 'desc');
        }

        return DataTables::of($purchases)
            ->addColumn('action', fn ($row) => $this->createPurchaseOrderAction($row))
            ->editColumn('date', function ($row) use ($generalSettings) {

                return date($generalSettings['business__date_format'], strtotime($row->date));
            })->editColumn('from', function ($row) use ($generalSettings) {

                if ($row->branch_name) {

                    return $row->branch_name.'<b>(BL)</b>';
                } else {

                    return $generalSettings['business__shop_name'];
                }
            })
            ->editColumn('total_purchase_amount', fn ($row) => '<span class="total_purchase_amount" data-value="'.$row->total_purchase_amount.'">'.\App\Utils\Converter::format_in_bdt($row->total_purchase_amount).'</span>')

            ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="'.$row->paid.'">'.\App\Utils\Converter::format_in_bdt($row->paid).'</span>')

            ->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="'.$row->due.'">'.\App\Utils\Converter::format_in_bdt($row->due).'</span>')

            ->editColumn('status', function ($row) {

                if ($row->po_receiving_status == 'Completed') {

                    return '<span class="text-success"><b>Completed</b></span>';
                } elseif ($row->po_receiving_status == 'Pending') {

                    return '<span class="text-danger"><b>Pending</b></span>';
                } elseif ($row->po_receiving_status == 'Partial') {

                    return '<span class="text-primary"><b>Partial</b></span>';
                }
            })->editColumn('payment_status', function ($row) {

                $payable = $row->total_purchase_amount - $row->purchase_return_amount;
                if ($row->due <= 0) {

                    return '<span class="text-success"><b>Paid</b></span>';
                } elseif ($row->due > 0 && $row->due < $payable) {

                    return '<span class="text-primary"><b>Partial</b></span>';
                } elseif ($payable == $row->due) {

                    return '<span class="text-danger"><b>Due</b></span>';
                }
            })->editColumn('created_by', function ($row) {

                return $row->created_prefix.' '.$row->created_name.' '.$row->created_last_name;
            })
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'total_purchase_amount', 'paid', 'due', 'purchase_return_amount', 'purchase_return_due', 'payment_status', 'status', 'created_by'])
            ->make(true);
    }

    public function addPurchaseOrder($request, $invoiceVoucherRefIdUtil, $purchaseOrderIdPrefix)
    {
        $poId = $purchaseOrderIdPrefix.str_pad($invoiceVoucherRefIdUtil->getLastId('purchases'), 5, '0', STR_PAD_LEFT);
        $addOrder = new Purchase();
        $addOrder->invoice_id = $poId;
        $addOrder->supplier_id = $request->supplier_id;
        $addOrder->purchase_account_id = $request->purchase_account_id;
        $addOrder->admin_id = auth()->user()->id;
        $addOrder->total_item = $request->total_item;
        $addOrder->order_discount = $request->order_discount ? $request->order_discount : 0.00;
        $addOrder->order_discount_type = $request->order_discount_type;
        $addOrder->order_discount_amount = $request->order_discount_amount;
        $addOrder->purchase_tax_percent = $request->purchase_tax_percent ? $request->purchase_tax_percent : 0.00;
        $addOrder->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0.00;
        $addOrder->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $addOrder->net_total_amount = $request->net_total_amount;
        $addOrder->total_purchase_amount = $request->total_ordered_amount;
        $addOrder->shipment_details = $request->shipment_details;
        $addOrder->purchase_note = $request->order_note;
        $addOrder->purchase_status = 3;
        $addOrder->is_purchased = 0;
        $addOrder->po_qty = $request->total_qty;
        $addOrder->po_pending_qty = $request->total_qty;
        $addOrder->po_receiving_status = 'Pending';
        $addOrder->date = $request->date;
        $addOrder->delivery_date = $request->delivery_date;
        $addOrder->report_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addOrder->time = date('h:i:s a');
        $addOrder->is_last_created = 1;
        $addOrder->save();

        return $addOrder;
    }

    private function createPurchaseOrderAction($row)
    {
        $html = '<div class="btn-group" role="group">';
        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> <a class="dropdown-item" id="detailsBtn" href="'.route('purchases.order.show', [$row->id]).'">'.__('view').'</a>';

        if (auth()->user()->branch_id == $row->branch_id) {

            $html .= '<a class="dropdown-item" href="'.route('purchases.po.receive.process', [$row->id]).'">'.__('Add Receive Stock').'</a>';
        }

        // $html .= '<a class="dropdown-item" href="' . route('barcode.on.purchase.barcode', $row->id) . '"><i class="fas fa-barcode text-primary"></i> Barcode</a>';

        $html .= '<a class="dropdown-item" id="view_payment" href="'.route('purchase.payment.list', $row->id).'">'.__('View Payment').'</a>';

        if (auth()->user()->branch_id == $row->branch_id) {

            if (auth()->user()->can('purchase_payment')) {

                if ($row->due > 0) {

                    $html .= '<a class="dropdown-item" data-type="1" id="add_payment" href="'.route('purchases.payment.modal', [$row->id]).'">'.__('Add Payment').'</a>';
                }
            }

            if (auth()->user()->can('purchase_edit')) {

                $html .= '<a class="dropdown-item" href="'.route('purchases.order.edit', [$row->id]).' ">'.__('Edit').'</a>';
            }
        }

        if (auth()->user()->can('purchase_delete')) {

            $html .= '<a class="dropdown-item" id="delete" href="'.route('purchase.delete', $row->id).'">'.__('Delete').'</a>';
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
