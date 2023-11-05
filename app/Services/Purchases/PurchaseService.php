<?php

namespace App\Services\Purchases;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;
use App\Models\Purchases\Purchase;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseService
{
    function purchaseListTable(object $request, ?int $supplierAccountId = null)
    {
        $generalSettings = config('generalSettings');
        $purchases = '';
        $query = DB::table('purchases')
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
            ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
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

        if ($request->supplier_account_id) {

            $query->where('purchases.supplier_id', $request->supplier_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        if ($request->payment_status) {

            if ($request->payment_status == PaymentStatus::Paid->value) {

                $query->where('purchases.due', '=', 0);
            } else if ($request->payment_status == PaymentStatus::Partial->value) {

                $query->where('purchases.paid', '>', 0)->where('purchases.due', '>', 0);
            } else if ($request->payment_status == PaymentStatus::Due->value) {

                $query->where('purchases.paid', '=', 0);
            }
        }

        if (isset($supplierAccountId)) {

            $query->where('purchases.supplier_account_id', $supplierAccountId);
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
        )->where('purchases.purchase_status', PurchaseStatus::Purchase->value)->orderBy('purchases.report_date', 'desc');

        return DataTables::of($purchases)
            ->addColumn('action', fn ($row) => $this->createPurchaseAction($row))

            ->editColumn('date', function ($row) use ($generalSettings) {

                return date($generalSettings['business__date_format'], strtotime($row->date));
            })->editColumn('invoice_id', function ($row) {

                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo text-white"></i></span>' : '';

                return '<a href="' . route('purchases.show', [$row->id]) . '" id="details_btn">' . $html . '</a>';
            })->editColumn('branch', function ($row) use ($generalSettings) {

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
            ->editColumn('total_purchase_amount', fn ($row) => '<span class="total_purchase_amount" data-value="' . $row->total_purchase_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_purchase_amount) . '</span>')

            ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . $row->paid . '">' . \App\Utils\Converter::format_in_bdt($row->paid) . '</span>')

            ->editColumn('due', fn ($row) => '<span class="text-danger">' . '<span class="due" data-value="' . $row->due . '">' . \App\Utils\Converter::format_in_bdt($row->due) . '</span></span>')

            ->editColumn('purchase_return_amount', fn ($row) => '<span class="purchase_return_amount" data-value="' . $row->purchase_return_amount . '">' . \App\Utils\Converter::format_in_bdt($row->purchase_return_amount) . '</span>')

            ->editColumn('payment_status', function ($row) {

                $payable = $row->total_purchase_amount - $row->purchase_return_amount;
                if ($row->due <= 0) {

                    return '<span class="text-success"><b>' . __("Paid") . '</b></span>';
                } elseif ($row->due > 0 && $row->due < $payable) {

                    return '<span class="text-primary"><b>' . __("Partial") . '</b></span>';
                } elseif ($payable == $row->due) {

                    return '<span class="text-danger"><b>' . __("Due") . '</b></span>';
                }
            })->editColumn('created_by', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })
            ->rawColumns(['action', 'date', 'invoice_id', 'branch', 'total_purchase_amount', 'paid', 'due', 'purchase_return_amount', 'payment_status', 'created_by'])
            ->make(true);
    }

    public function addPurchase(object $request, object $codeGenerator, string $invoicePrefix): ?object
    {
        $__invoicePrefix = $invoicePrefix != null ? $invoicePrefix : 'PI';
        $invoiceId = $codeGenerator->generateMonthAndTypeWise(table: 'purchases', column: 'invoice_id', typeColName: 'purchase_status', typeValue: PurchaseStatus::Purchase->value, prefix: $__invoicePrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

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
        $addPurchase->total_qty = $request->total_qty;
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

    public function updatePurchase(object $request, object $updatePurchase): object
    {
        foreach ($updatePurchase->purchaseProducts as $purchaseProduct) {

            $purchaseProduct->delete_in_update = 1;
            $purchaseProduct->save();
        }

        $updatePurchase->warehouse_id = isset($request->warehouse_count) ? $request->warehouse_id : null;

        // update purchase total information
        $updatePurchase->supplier_account_id = $request->supplier_account_id;
        $updatePurchase->purchase_account_id = $request->purchase_account_id;
        $updatePurchase->pay_term = $request->pay_term;
        $updatePurchase->pay_term_number = $request->pay_term_number;
        $updatePurchase->total_item = $request->total_item;
        $updatePurchase->total_qty = $request->total_qty;
        $updatePurchase->net_total_amount = $request->net_total_amount;
        $updatePurchase->order_discount = $request->order_discount ? $request->order_discount : 0.00;
        $updatePurchase->order_discount_type = $request->order_discount_type;
        $updatePurchase->order_discount_amount = $request->order_discount_amount;
        $updatePurchase->purchase_tax_ac_id = $request->purchase_tax_ac_id;
        $updatePurchase->purchase_tax_percent = $request->purchase_tax_percent ? $request->purchase_tax_percent : 0.00;
        $updatePurchase->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0.00;
        $updatePurchase->total_purchase_amount = $request->total_purchase_amount;
        $updatePurchase->purchase_note = $request->purchase_note;
        $updatePurchase->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $updatePurchase->date = $request->date;
        $time = date(' H:i:s', strtotime($updatePurchase->report_date));
        $updatePurchase->report_date = date('Y-m-d H:i:s', strtotime($request->date . $time));
        $updatePurchase->shipment_details = $request->shipment_details;
        $updatePurchase->save();

        return $updatePurchase;
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
        $purchase->is_return_available = $totalReturn->sum('total_returned_amount') > 0 ? BooleanType::True->value : BooleanType::False->value;
        $purchase->save();

        return $purchase;
    }

    public function deletePurchase(int $id): array|object
    {
        // get deleting purchase row
        $deletePurchase = $this->singlePurchase(id: $id, with: [
            'accountingVouchers',
            'purchaseProducts',
            'purchaseProducts.product',
            'purchaseProducts.variant',
            'purchaseProducts.purchaseSaleChains',
        ]);

        if (count($deletePurchase->accountingVouchers) > 0) {

            return ['pass' => false, 'msg' => __("Purchase can not be deleted. There is one or more payment which is against this purchase.")];
        }

        foreach ($deletePurchase->purchaseProducts as $purchaseProduct) {

            if (count($purchaseProduct->purchaseSaleChains) > 0) {

                $variant = $purchaseProduct->variant ? ' - ' . $purchaseProduct->variant->name : '';
                $product = $purchaseProduct->product->name . $variant;

                return ['pass' => false, 'msg' => __("Can not delete is purchase. Mismatch between sold and purchase stock account method. Product:") . $product];
            }
        }

        // foreach ($deletePurchase->purchaseProducts as $purchaseProduct) {

        //     $SupplierProduct = SupplierProduct::where('supplier_id', $deletePurchase->supplier_id)
        //         ->where('product_id', $purchaseProduct->product_id)
        //         ->where('product_variant_id', $purchaseProduct->product_variant_id)
        //         ->first();

        //     if ($SupplierProduct) {

        //         $SupplierProduct->label_qty -= $purchase_product->quantity;
        //         $SupplierProduct->save();
        //     }
        // }

        $deletePurchase->delete();

        return $deletePurchase;
    }

    public function purchaseByAnyConditions(?array $with = null): ?object
    {
        $query = Purchase::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function singlePurchase(int $id, ?array $with = null): ?object
    {
        $query = Purchase::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function restrictions(object $request, bool $checkSupplierChangeRestriction = false, ?int $purchaseId = null): array
    {
        if (!isset($request->product_ids)) {

            return ['pass' => false, 'msg' => __("Product table is empty.")];
        } elseif (count($request->product_ids) > 60) {

            return ['pass' => false, 'msg' => __("Purchase invoice products must be less than 60 or equal.")];
        }

        if ($checkSupplierChangeRestriction == true) {

            $purchase = $this->singlePurchase(id: $purchaseId, with: ['references']);

            if (count($purchase->references)) {

                if ($purchase->supplier_account_id != $request->supplier_account_id) {

                    return ['pass' => false, 'msg' => __("Supplier can not be changed. One or more payments is exists against this purchase.")];
                }
            }
        }

        return ['pass' => true];
    }

    private function createPurchaseAction($row)
    {
        $html = '<div class="btn-group" role="group">';
        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

        $html .= '<a href="' . route('purchases.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __("View") . '</a>';

        if (auth()->user()->can('purchase_edit')) {

            $html .= '<a href="' . route('purchases.edit', [$row->id]) . ' " class="dropdown-item">' . __("Edit") . '</a>';
        }

        if (auth()->user()->can('purchase_delete')) {

            $html .= '<a href="' . route('purchase.delete', $row->id) . '" class="dropdown-item" id="delete">' . __("Delete") . '</a>';
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
