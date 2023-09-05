<?php

namespace App\Services\Purchases;

use Carbon\Carbon;
use App\Enums\PurchaseStatus;
use App\Models\Purchases\Purchase;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseService
{
    function purchaseListTable($request)
    {
        $generalSettings = config('generalSettings');
        $purchases = '';
        $query = DB::table('purchases')
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
            ->leftJoin('users as created_by', 'purchases.admin_id', 'created_by.id');

        if (!empty($request->branch_id)) {

            if ($request->branch_id == 'NULL') {

                $query->where('purchases.branch_id', null);
            } else {

                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if (!empty($request->warehouse_id)) {

            $query->where('purchases.warehouse_id', $request->warehouse_id);
        }

        if ($request->supplier_id) {

            $query->where('purchases.supplier_id', $request->supplier_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $purchases = $query->where('purchases.branch_id', auth()->user()->branch_id);
        }

        $purchases = $query->select(
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
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
            'suppliers.name as supplier_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        )->where('is_purchased', 1)->orderBy('purchases.report_date', 'desc');

        return DataTables::of($purchases)
            ->addColumn('action', fn ($row) => $this->createPurchaseAction($row))

            ->editColumn('date', function ($row) use ($generalSettings) {

                return date($generalSettings['business__date_format'], strtotime($row->date));
            })->editColumn('invoice_id', function ($row) {

                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo text-white"></i></span>' : '';

                return $html;
            })->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->warehouse_name) {

                    return $row->warehouse_name . '<b>(WH)</b>';
                } elseif ($row->branch_name) {

                    return $row->branch_name . '<b>(BL)</b>';
                } else {

                    return $generalSettings['business__shop_name'];
                }
            })
            ->editColumn('total_purchase_amount', fn ($row) => '<span class="total_purchase_amount" data-value="' . $row->total_purchase_amount . '">' . $this->converter->format_in_bdt($row->total_purchase_amount) . '</span>')

            ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . $row->paid . '">' . $this->converter->format_in_bdt($row->paid) . '</span>')

            ->editColumn('due', fn ($row) => '<span class="text-danger">' . '<span class="due" data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span></span>')

            ->editColumn('purchase_return_amount', fn ($row) => '<span class="purchase_return_amount" data-value="' . $row->purchase_return_amount . '">' . $this->converter->format_in_bdt($row->purchase_return_amount) . '</span>')

            ->editColumn('purchase_return_due', fn ($row) => '<span class="purchase_return_due text-danger" data-value="' . $row->purchase_return_due . '">' . $this->converter->format_in_bdt($row->purchase_return_due) . '</span>')

            ->editColumn('status', function ($row) {

                if ($row->purchase_status == 1) {

                    return '<span class="text-success"><b>Purchased</b></span>';
                } elseif ($row->purchase_status == 2) {

                    return '<span class="text-secondary"><b>Pending</b></span>';
                } elseif ($row->purchase_status == 3) {

                    return '<span class="text-primary"><b>Purchased By Order</b></span>';
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

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'total_purchase_amount', 'paid', 'due', 'purchase_return_amount', 'purchase_return_due', 'payment_status', 'status', 'created_by'])
            ->make(true);
    }


    public function addPurchase(object $request, object $codeGenerator, string $invoicePrefix): ?object
    {
        $__invoicePrefix = $invoicePrefix != null ? $invoicePrefix : 'PI';
        $invoiceId = $codeGenerator->generateMonthAndTypeWise(table: 'purchases', column: 'invoice_id', typeColName: 'purchase_status', typeValue: PurchaseStatus::Purchase->value, prefix: $__invoicePrefix, splitter: '-', suffixSeparator: '-');

        $addPurchase = new Purchase();
        $addPurchase->invoice_id = $invoiceId;
        $addPurchase->warehouse_id = $request->warehouse_id ? $request->warehouse_id : null;
        $addPurchase->branch_id = auth()->user()->branch_id;
        $addPurchase->supplier_account_id = $request->supplier_account_id;
        $addPurchase->purchase_account_id = $request->purchase_account_id;
        $addPurchase->pay_term = $request->pay_term;
        $addPurchase->pay_term_number = $request->pay_term_number;
        $addPurchase->admin_id = auth()->user()->id;
        $addPurchase->total_item = $request->total_item;
        $addPurchase->order_discount = $request->order_discount ? $request->order_discount : 0.00;
        $addPurchase->order_discount_type = $request->order_discount_type;
        $addPurchase->order_discount_amount = $request->order_discount_amount;
        $addPurchase->purchase_tax_ac_id = $request->purchase_tax_ac_id;
        $addPurchase->purchase_tax_percent = $request->purchase_tax_percent ? $request->purchase_tax_percent : 0;
        $addPurchase->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0;
        $addPurchase->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $addPurchase->net_total_amount = $request->net_total_amount;
        $addPurchase->total_purchase_amount = $request->total_purchase_amount;
        $addPurchase->paid = $request->paying_amount;
        $addPurchase->due = $request->total_purchase_amount;
        $addPurchase->shipment_details = $request->shipment_details;
        $addPurchase->purchase_note = $request->purchase_note;
        $addPurchase->purchase_status = PurchaseStatus::Purchase->value;
        $addPurchase->is_purchased = 1;
        $addPurchase->date = $request->date;
        $addPurchase->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addPurchase->is_last_created = 1;
        $addPurchase->save();

        return $addPurchase;
    }

    public function adjustPurchaseInvoiceAmounts(object $purchase): ?object
    {
        $totalPurchasePaid = DB::table('voucher_description_references')
            ->where('voucher_description_references.purchase_id', $purchase->id)
            ->select(DB::raw('sum(voucher_description_references.amount) as total_paid'))
            ->groupBy('voucher_description_references.purchase_id')
            ->get();

        $totalReturn = DB::table('purchase_returns')
            ->where('purchase_returns.purchase_id', $purchase->id)
            ->select(DB::raw('sum(total_return_amount) as total_returned_amount'))
            ->groupBy('purchase_returns.purchase_id')
            ->get();

        $due = $purchase->total_purchase_amount
            - $totalPurchasePaid->sum('total_paid')
            - $totalReturn->sum('total_returned_amount');

        $purchase->paid = $totalPurchasePaid->sum('total_paid');
        $purchase->due = $due;
        $purchase->purchase_return_amount = $totalReturn->sum('total_returned_amount');
        $purchase->save();

        return $purchase;
    }

    public function purchaseByAnyConditions(?array $with = null): ?object
    {
        $query = Purchase::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function restrictions(object $request): array
    {
        if (!isset($request->product_ids)) {

            return ['pass' => false, 'msg' => __("Product table is empty.")];
        } elseif (count($request->product_ids) > 60) {

            return ['pass' => false, 'msg' => __("Purchase invoice items must be less than 60 or equal.")];
        }

        return ['pass' => true];
    }
}
