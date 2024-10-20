<?php

namespace App\Services\Purchases;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;
use App\Models\Purchases\Purchase;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderService
{
    public function purchaseOrdersTable(object $request, int $supplierAccountId = null): object
    {
        $generalSettings = config('generalSettings');
        $orders = '';
        $query = DB::table('purchases')
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
            ->leftJoin('users as created_by', 'purchases.admin_id', 'created_by.id')
            ->leftJoin('currencies', 'branches.currency_id', 'currencies.id');

        if (!empty($request->branch_id)) {

            if ($request->branch_id == 'NULL') {

                $query->where('purchases.branch_id', null);
            } else {

                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if ($request->supplier_account_id) {

            $query->where('purchases.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->receiving_status) {

            $query->where('purchases.po_receiving_status', $request->receiving_status);
        }

        if ($request->payment_status) {

            if ($request->payment_status == PaymentStatus::Paid->value) {

                $query->where('purchases.due', '=', 0);
            } elseif ($request->payment_status == PaymentStatus::Partial->value) {

                $query->where('purchases.paid', '>', 0)->where('purchases.due', '>', 0);
            } elseif ($request->payment_status == PaymentStatus::Due->value) {

                $query->where('purchases.paid', '=', 0);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        if (isset($supplierAccountId)) {

            $query->where('purchases.supplier_account_id', $supplierAccountId);
        }

        if (auth()->user()->can('view_only_won_transactions')) {

            $query->where('purchases.admin_id', auth()->user()->id);
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('purchases.branch_id', auth()->user()->branch_id);
        }

        $orders = $query->select(
            'purchases.id',
            'purchases.branch_id',
            'purchases.date',
            'purchases.invoice_id',
            'purchases.total_purchase_amount',
            'purchases.due',
            'purchases.paid',
            'purchases.purchase_status',
            'purchases.po_qty',
            'purchases.po_pending_qty',
            'purchases.po_received_qty',
            'purchases.po_receiving_status',
            'branches.name as branch_name',
            'branches.area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'suppliers.name as supplier_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
            'currencies.currency_rate as c_rate'
        )->where('purchases.purchase_status', PurchaseStatus::PurchaseOrder->value)->orderBy('purchases.report_date', 'desc');

        return DataTables::of($orders)
            ->addColumn('action', fn($row) => $this->createPurchaseOrderAction($row))
            ->editColumn('date', function ($row) use ($generalSettings) {

                return date($generalSettings['business_or_shop__date_format'], strtotime($row->date));
            })->editColumn('branch', function ($row) use ($generalSettings) {

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
            ->editColumn('invoice_id', function ($row) {

                return '<a href="' . route('purchase.orders.show', [$row->id]) . '" id="details_btn">' . $row->invoice_id . '</a>';
            })
            ->editColumn('total_purchase_amount', fn($row) => '<span class="total_purchase_amount" data-value="' . curr_cnv($row->total_purchase_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->total_purchase_amount, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('paid', fn($row) => '<span class="paid text-success" data-value="' . curr_cnv($row->paid, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->paid, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('due', function ($row) {

                if ($row->due < 0) {

                    return '(<span class="due text-danger" data-value="' . curr_cnv($row->due, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(abs(curr_cnv($row->due, $row->c_rate, $row->branch_id))) . '</span>)';
                } else {

                    return '<span class="due text-danger" data-value="' . curr_cnv($row->due, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->due, $row->c_rate, $row->branch_id)) . '</span>';
                }
            })

            ->editColumn('po_qty', function ($row) {

                return '<span class="po_qty" data-value="' . $row->po_qty . '">' . \App\Utils\Converter::format_in_bdt($row->po_qty) . '</span>';
            })

            ->editColumn('po_pending_qty', function ($row) {

                return '<span class="po_pending_qty text-danger" data-value="' . $row->po_pending_qty . '">' . \App\Utils\Converter::format_in_bdt($row->po_pending_qty) . '</span>';
            })

            ->editColumn('po_received_qty', function ($row) {

                return '<span class="po_received_qty text-success" data-value="' . $row->po_received_qty . '">' . \App\Utils\Converter::format_in_bdt($row->po_received_qty) . '</span>';
            })

            ->editColumn('receiving_status', function ($row) {

                if ($row->po_receiving_status == 'Completed') {

                    return '<span class="text-success"><b>' . __('Completed') . '</b></span>';
                } elseif ($row->po_receiving_status == 'Pending') {

                    return '<span class="text-danger"><b>' . __('Pending') . '</b></span>';
                } elseif ($row->po_receiving_status == 'Partial') {

                    return '<span class="text-primary"><b>' . __('Partial') . '</b></span>';
                }
            })->editColumn('payment_status', function ($row) {

                $payable = $row->total_purchase_amount;
                if ($row->due <= 0) {

                    return '<span class="text-success"><b>' . __('Paid') . '</b></span>';
                } elseif ($row->due > 0 && $row->due < $payable) {

                    return '<span class="text-primary"><b>' . __('Partial') . '</b></span>';
                } elseif ($payable == $row->due) {

                    return '<span class="text-danger"><b>' . __('Due') . '</b></span>';
                }
            })->editColumn('created_by', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })
            ->rawColumns(['action', 'date', 'invoice_id', 'branch', 'total_purchase_amount', 'paid', 'due', 'purchase_return_amount', 'purchase_return_due', 'payment_status', 'receiving_status', 'po_qty', 'po_pending_qty', 'po_received_qty', 'created_by'])
            ->make(true);
    }

    public function addPurchaseOrder(object $request, object $codeGenerator, string $invoicePrefix): ?object
    {
        $orderId = $codeGenerator->generateMonthAndTypeWise(table: 'purchases', column: 'invoice_id', typeColName: 'purchase_status', typeValue: PurchaseStatus::PurchaseOrder->value, prefix: $invoicePrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $addPurchase = new Purchase();
        $addPurchase->invoice_id = $orderId;
        $addPurchase->branch_id = auth()->user()->branch_id;
        $addPurchase->supplier_account_id = $request->supplier_account_id;
        $addPurchase->purchase_account_id = $request->purchase_account_id;
        $addPurchase->pay_term = $request->pay_term;
        $addPurchase->pay_term_number = $request->pay_term_number;
        $addPurchase->admin_id = auth()->user()->id;
        $addPurchase->total_item = $request->total_item;
        $addPurchase->total_qty = $request->total_qty;
        $addPurchase->order_discount = $request->order_discount ? $request->order_discount : 0;
        $addPurchase->order_discount_type = $request->order_discount_type;
        $addPurchase->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0;
        $addPurchase->purchase_tax_ac_id = $request->purchase_tax_ac_id;
        $addPurchase->purchase_tax_percent = $request->purchase_tax_percent ? $request->purchase_tax_percent : 0;
        $addPurchase->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0;
        $addPurchase->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $addPurchase->net_total_amount = $request->net_total_amount ? $request->net_total_amount : 0;
        $addPurchase->total_purchase_amount = $request->total_ordered_amount ? $request->total_ordered_amount : 0;
        $addPurchase->paid = $request->paying_amount ? $request->paying_amount : 0;
        $addPurchase->due = $request->total_ordered_amount ? $request->total_ordered_amount : 0;
        $addPurchase->shipment_details = $request->shipment_details;
        $addPurchase->purchase_note = $request->order_note;
        $addPurchase->purchase_status = PurchaseStatus::PurchaseOrder->value;
        $addPurchase->date = $request->date;
        $addPurchase->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addPurchase->delivery_date = $request->delivery_date;
        $addPurchase->po_qty = $request->total_qty;
        $addPurchase->po_pending_qty = $request->total_qty;
        $addPurchase->po_receiving_status = 'Pending';
        $addPurchase->is_last_created = 1;
        $addPurchase->save();

        return $addPurchase;
    }

    public function updatePurchaseOrder(object $request, object $updatePurchaseOrder): object
    {
        foreach ($updatePurchaseOrder->purchaseOrderProducts as $purchaseOrderProduct) {

            $purchaseOrderProduct->is_delete_in_update = 1;
            $purchaseOrderProduct->save();
        }

        $updatePurchaseOrder->supplier_account_id = $request->supplier_account_id;
        $updatePurchaseOrder->purchase_account_id = $request->purchase_account_id;
        $updatePurchaseOrder->pay_term = $request->pay_term;
        $updatePurchaseOrder->pay_term_number = $request->pay_term_number;
        $updatePurchaseOrder->total_item = $request->total_item;
        $updatePurchaseOrder->total_qty = $request->total_qty;
        $updatePurchaseOrder->order_discount = $request->order_discount ? $request->order_discount : 0;
        $updatePurchaseOrder->order_discount_type = $request->order_discount_type;
        $updatePurchaseOrder->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0;
        $updatePurchaseOrder->purchase_tax_ac_id = $request->purchase_tax_ac_id;
        $updatePurchaseOrder->purchase_tax_percent = $request->purchase_tax_percent ? $request->purchase_tax_percent : 0;
        $updatePurchaseOrder->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0;
        $updatePurchaseOrder->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $updatePurchaseOrder->net_total_amount = $request->net_total_amount ? $request->net_total_amount : 0;
        $updatePurchaseOrder->total_purchase_amount = $request->total_ordered_amount ? $request->total_ordered_amount : 0;
        $updatePurchaseOrder->shipment_details = $request->shipment_details;
        $updatePurchaseOrder->purchase_note = $request->order_note;
        $updatePurchaseOrder->date = $request->date;
        $time = date(' H:i:s', strtotime($updatePurchaseOrder->report_date));
        $updatePurchaseOrder->report_date = date('Y-m-d H:i:s', strtotime($request->date . $time));
        $updatePurchaseOrder->delivery_date = $request->delivery_date;
        $updatePurchaseOrder->is_last_created = 1;
        $updatePurchaseOrder->save();

        $this->updatePoReceivingStatus(purchaseOrderId: $updatePurchaseOrder->id);

        return $updatePurchaseOrder;
    }

    public function deletePurchaseOrder(int $id): array|object
    {
        $deletePurchaseOrder = $this->singlePurchaseOrder(id: $id, with: [
            'references',
        ]);

        if (count($deletePurchaseOrder->references) > 0) {

            return ['pass' => false, 'msg' => __('Purchase Order can not be deleted. There is one or more payment against this purchase order.')];
        }

        if (count($deletePurchaseOrder->purchases) > 0) {

            return ['pass' => false, 'msg' => __('Purchase Order can not be deleted. There is one or more purchase invoice against this purchase order.')];
        }

        $deletePurchaseOrder->delete();

        return $deletePurchaseOrder;
    }

    public function restrictions(object $request, bool $checkSupplierChangeRestriction = false, int $purchaseOrderId = null): array
    {
        if (!isset($request->product_ids)) {

            return ['pass' => false, 'msg' => __('Product table is empty.')];
        } elseif (count($request->product_ids) > 60) {

            return ['pass' => false, 'msg' => __('Purchase order products must be less than 60 or equal.')];
        }

        if ($checkSupplierChangeRestriction == true) {

            $purchase = $this->singlePurchaseOrder(id: $purchaseOrderId, with: ['references']);

            if (count($purchase->references)) {

                if ($purchase->supplier_account_id != $request->supplier_account_id) {

                    return ['pass' => false, 'msg' => __('Supplier can not be changed. One or more payments is exists against this purchase order.')];
                }
            }
        }

        return ['pass' => true];
    }

    public function updatePoReceivingStatus(int $purchaseOrderId)
    {
        $purchaseOrderProducts = DB::table('purchase_order_products')->where('purchase_order_products.purchase_id', $purchaseOrderId)
            ->select(
                DB::raw('sum(ordered_quantity) as ordered_qty'),
                DB::raw('sum(pending_quantity) as pending_quantity'),
                DB::raw('sum(received_quantity) as received_quantity')
            )->groupBy('purchase_order_products.purchase_id')->get();

        $purchaseOrder = $this->singlePurchaseOrder(id: $purchaseOrderId);

        $purchaseOrder->po_qty = $purchaseOrderProducts->sum('ordered_qty');
        $purchaseOrder->po_pending_qty = $purchaseOrderProducts->sum('pending_quantity');
        $purchaseOrder->po_received_qty = $purchaseOrderProducts->sum('received_quantity');

        if ($purchaseOrderProducts->sum('pending_quantity') == 0) {

            $purchaseOrder->po_receiving_status = 'Completed';
        } elseif ($purchaseOrderProducts->sum('ordered_qty') == $purchaseOrderProducts->sum('pending_quantity')) {

            $purchaseOrder->po_receiving_status = 'Pending';
        } elseif ($purchaseOrderProducts->sum('received_quantity') > 0) {

            $purchaseOrder->po_receiving_status = 'Partial';
        }

        $purchaseOrder->save();
    }

    public function singlePurchaseOrder(int $id, array $with = null): ?object
    {
        $query = Purchase::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    private function createPurchaseOrderAction($row)
    {
        $html = '<div class="btn-group" role="group">';
        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __("Action") . '</button>';
        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> <a class="dropdown-item" id="details_btn" href="' . route('purchase.orders.show', [$row->id]) . '">' . __('view') . '</a>';

        if (auth()->user()->branch_id == $row->branch_id) {

            if (auth()->user()->can('purchase_order_to_invoice')) {

                $html .= '<a class="dropdown-item" href="' . route('purchase.order.to.invoice.create', [$row->id]) . ' ">' . __('P/o To Purchase Invoice') . '</a>';
            }
        }

        if (auth()->user()->branch_id == $row->branch_id) {

            if (auth()->user()->can('purchase_order_edit')) {

                $html .= '<a class="dropdown-item" href="' . route('purchase.orders.edit', [$row->id]) . ' ">' . __('Edit') . '</a>';
            }
        }

        if (auth()->user()->branch_id == $row->branch_id) {

            if (auth()->user()->can('purchase_order_delete')) {

                $html .= '<a class="dropdown-item" id="delete" href="' . route('purchase.orders.delete', $row->id) . '">' . __('Delete') . '</a>';
            }
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
