<?php

namespace App\Services\Purchases;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Purchases\PurchaseReturn;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseReturnService
{
    function purchaseReturnsTable(): object
    {
        $returns = '';
        $generalSettings = config('generalSettings');

        $query = DB::table('purchase_returns')
            ->leftJoin('purchases', 'purchase_returns.purchase_id', 'purchases.id')
            ->leftJoin('branches', 'purchase_returns.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('account_suppliers', 'purchase_returns.supplier_account_id', 'suppliers.id')
            ->leftJoin('users as createdBy', 'purchases.created_by_id', 'createdBy.id');;

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('purchase_returns.branch_id', null);
            } else {

                $query->where('purchase_returns.branch_id', $request->branch_id);
            }
        }

        if ($request->supplier_account_id) {

            $query->where('purchase_returns.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchase_returns.date_ts', $date_range); // Final
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $query->where('purchase_returns.branch_id', auth()->user()->branch_id);
        }

        $returns = $query->select(
            'purchase_returns.*',
            'purchases.invoice_id as parent_invoice_id',
            'branches.name as branch_name',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'suppliers.name as supplier_name',
            'createdBy.prefix as created_prefix',
            'createdBy.name as created_name',
            'createdBy.last_name as created_last_name',
        )->orderBy('purchase_returns.date_ts', 'desc');

        return DataTables::of($returns)

            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="' . route('purchase.returns.show', $row->id) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

                if (auth()->user()->can('purchase_return')) {

                    $html .= '<a href="' . route('purchase.returns.edit', $row->id) . '" class="dropdown-item">' . __('Edit') . '</a>';
                }

                if (auth()->user()->can('purchase_return')) {

                    if ($row->due > 0) {

                        $html .= '<a class="dropdown-item" href="#">' . __('Add Receipt') . '</a>';
                    }
                }

                if (auth()->user()->can('purchase_return')) {

                    $html .= '<a href="' . route('purchases.returns.delete', $row->id) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business__date_format']);
                return date($__date_format, strtotime($row->date));
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

            ->editColumn('payment_status', function ($row) {

                $totalReturnAmount = $row->total_return_amount;
                if ($row->due <= 0) {

                    return '<span class="text-success"><b>' . __("Paid") . '</b></span>';
                } elseif ($row->due > 0 && $row->due < $totalReturnAmount) {

                    return '<span class="text-primary"><b>' . __("Partial") . '</b></span>';
                } elseif ($totalReturnAmount == $row->due) {

                    return '<span class="text-danger"><b>' . __("Due") . '</b></span>';
                }
            })

            ->editColumn('total_qty', fn ($row) => App\Utils\Converter::format_in_bdt($row->total_qty))
            ->editColumn('net_total_amount', fn ($row) => App\Utils\Converter::format_in_bdt($row->net_total_amount))
            ->editColumn('return_discount', fn ($row) => App\Utils\Converter::format_in_bdt($row->return_discount))
            ->editColumn('return_tax_amount', fn ($row) => App\Utils\Converter::format_in_bdt($row->return_tax_amount))
            ->editColumn('total_return_amount', fn ($row) => App\Utils\Converter::format_in_bdt($row->total_return_amount))
            ->editColumn('received', fn ($row) => App\Utils\Converter::format_in_bdt($row->received_amount))
            ->editColumn('due', fn ($row) => App\Utils\Converter::format_in_bdt($row->due))

            ->editColumn('createdBy', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })

            ->rawColumns(['action', 'date', 'branch', 'payment_status', 'total_qty', 'net_total_amount', 'return_discount', 'return_tax_amount', 'total_return_amount', 'received', 'due', 'createdBy'])
            ->make(true);
    }

    public function restrictions(object $request): array
    {
        if (!isset($request->product_ids)) {

            return ['pass' => false, 'msg' => __("Product table is empty.")];
        } elseif (count($request->product_ids) > 60) {

            return ['pass' => false, 'msg' => __("Purchase invoice products must be less than 60 or equal.")];
        }

        if ($request->total_qty == 0) {

            return ['pass' => false, 'msg' => 'All product`s quantity is 0.'];
        }

        return ['pass' => true];
    }

    function addPurchaseReturn(object $request, object $codeGenerator, ?string $voucherPrefix = null): object
    {
        // generate invoice ID
        $voucherNo = $codeGenerator->generateMonthWise(table: 'purchase_returns', column: 'voucher_no', prefix: $voucherPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $addPurchaseReturn = new PurchaseReturn();
        $addPurchaseReturn->branch_id = auth()->user()->branch_id;
        $addPurchaseReturn->voucher_no = $voucherNo;
        $addPurchaseReturn->purchase_id = $request->purchase_id;
        $addPurchaseReturn->supplier_account_id = $request->supplier_account_id;
        $addPurchaseReturn->purchase_account_id = $request->purchase_account_id;
        $addPurchaseReturn->total_item = $request->total_item;
        $addPurchaseReturn->total_qty = $request->total_qty;
        $addPurchaseReturn->net_total_amount = $request->net_total_amount;
        $addPurchaseReturn->return_discount = $request->return_discount ? $request->return_discount : 0;
        $addPurchaseReturn->return_discount_type = $request->return_discount_type;
        $addPurchaseReturn->return_discount_amount = $request->return_discount_amount ? $request->return_discount_amount : 0;
        $addPurchaseReturn->return_tax_ac_id = $request->return_tax_ac_id;
        $addPurchaseReturn->return_tax_percent = $request->return_tax_percent ? $request->return_tax_percent : 0;
        $addPurchaseReturn->return_tax_amount = $request->return_tax_amount ? $request->return_tax_amount : 0;
        $addPurchaseReturn->total_return_amount = $request->total_return_amount;
        $addPurchaseReturn->due = $request->total_return_amount;
        $addPurchaseReturn->date = $request->date;
        $addPurchaseReturn->date_ts = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addPurchaseReturn->note = $request->note;
        $addPurchaseReturn->created_by_id = auth()->user()->id;
        $addPurchaseReturn->save();

        return $addPurchaseReturn;
    }

    public function singlePurchaseReturn(int $id, ?array $with = null): ?object
    {
        $query = PurchaseReturn::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function adjustPurchaseReturnVoucherAmounts($purchaseReturn)
    {
        $totalReceived = DB::table('voucher_description_references')
            ->where('voucher_description_references.purchase_return_id', $purchaseReturn->id)
            ->select(DB::raw('sum(voucher_description_references.amount) as total_received'))
            ->groupBy('voucher_description_references.purchase_return_id')
            ->get();

        // $totalReturnPaid = DB::table('purchase_payments')
        //     ->where('purchase_payments.purchase_id', $purchaseReturn->purchase_id)
        //     ->where('purchase_payments.payment_type', 2)
        //     ->select(DB::raw('sum(paid_amount) as total_paid'))
        //     ->groupBy('purchase_payments.purchase_id')
        //     ->get();

        $due = $purchaseReturn->total_return_amount - $totalReceived->sum('total_received');
        $purchaseReturn->received_amount = $totalReceived->sum('total_received');
        $purchaseReturn->due = $due;
        $purchaseReturn->save();
    }
}