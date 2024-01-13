<?php

namespace App\Services\Sales;

use App\Enums\BooleanType;
use App\Enums\SaleStatus;
use App\Models\Sales\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DraftService
{
    public function draftListTable($request)
    {
        $generalSettings = config('generalSettings');
        $quotations = '';

        $query = DB::table('sales')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('users as created_by', 'sales.created_by_id', 'created_by.id')
            ->where('sales.status', SaleStatus::Draft->value);

        $this->filteredQuery($request, $query);

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            if (auth()->user()->can('view_own_sale')) {

                $query->where('sales.created_by_id', auth()->user()->id);
            }

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }

        $quotations = $query->select(
            'sales.id',
            'sales.branch_id',
            'sales.draft_id',
            'sales.date',
            'sales.total_item',
            'sales.total_qty',
            'sales.total_invoice_amount',
            'sales.order_status',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'customers.name as customer_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        )->orderBy('sales.draft_date_ts', 'desc');

        return DataTables::of($quotations)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="' . route('sale.drafts.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('sale_draft')) {

                        $html .= '<a class="dropdown-item" href="' . route('sale.drafts.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                    }
                }

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('sale_draft')) {

                        $html .= '<a href="' . route('sales.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
                    }
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                return date($__date_format, strtotime($row->date));
            })
            ->editColumn('draft_id', function ($row) {

                return '<a href="' . route('sale.drafts.show', [$row->id]) . '" id="details_btn">' . $row->draft_id . '</a>';
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
            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . \App\Utils\Converter::format_in_bdt($row->total_item) . '</span>')

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>')

            ->editColumn('total_invoice_amount', fn ($row) => '<span class="total_invoice_amount" data-value="' . $row->total_invoice_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_invoice_amount) . '</span>')

            ->editColumn('created_by', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })

            ->rawColumns(['action', 'date', 'total_item', 'total_qty', 'total_invoice_amount', 'draft_id', 'branch', 'customer', 'created_by'])
            ->make(true);
    }

    public function updateDraft(object $request, object $updateDraft, object $codeGenerator, ?string $salesOrderPrefix, ?string $invoicePrefix, ?string $quotationPrefix): object
    {
        foreach ($updateDraft->saleProducts as $saleProduct) {

            $saleProduct->is_delete_in_update = 1;
            $saleProduct->save();
        }

        $time = date(' H:i:s', strtotime($updateDraft->date_ts));

        if ($request->status == SaleStatus::Order->value) {

            $orderId = $codeGenerator->generateMonthWise(table: 'sales', column: 'order_id', prefix: $salesOrderPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
            $updateDraft->order_id = $orderId;
            $updateDraft->order_status = BooleanType::True->value;
            $updateDraft->total_ordered_qty = $updateDraft->total_qty;
            $updateDraft->order_date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        } elseif ($request->status == SaleStatus::Quotation->value) {

            $quotationId = $codeGenerator->generateMonthWise(table: 'sales', column: 'quotation_id', prefix: $quotationPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
            $updateDraft->quotation_id = $quotationId;
            $updateDraft->quotation_status = BooleanType::True->value;
            $updateDraft->total_quotation_qty = $updateDraft->total_qty;
            $updateDraft->quotation_date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        } elseif ($request->status == SaleStatus::Final->value) {

            $invoiceId = $codeGenerator->generateMonthWise(table: 'sales', column: 'invoice_id', prefix: $invoicePrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
            $updateDraft->invoice_id = $invoiceId;
            $updateDraft->total_sold_qty = $updateDraft->total_qty;
            $updateDraft->sale_date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        }

        $updateDraft->status = $request->status;
        $updateDraft->sale_account_id = $request->sale_account_id;
        $updateDraft->customer_account_id = $request->customer_account_id;
        $updateDraft->pay_term = $request->pay_term;
        $updateDraft->pay_term_number = $request->pay_term_number;
        $updateDraft->date = $request->date;
        $updateDraft->date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        $updateDraft->draft_date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        $updateDraft->total_item = $request->total_item;
        $updateDraft->total_qty = $request->total_qty;
        $updateDraft->net_total_amount = $request->net_total_amount;
        $updateDraft->order_discount_type = $request->order_discount_type;
        $updateDraft->order_discount = $request->order_discount;
        $updateDraft->order_discount_amount = $request->order_discount_amount;
        $updateDraft->sale_tax_ac_id = $request->sale_tax_ac_id;
        $updateDraft->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0;
        $updateDraft->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $updateDraft->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $updateDraft->shipment_details = $request->shipment_details;
        $updateDraft->shipment_address = $request->shipment_address;
        $updateDraft->shipment_status = $request->shipment_status ? $request->shipment_status : 0;
        $updateDraft->delivered_to = $request->delivered_to;
        $updateDraft->note = $request->note;
        $updateDraft->total_invoice_amount = $request->total_invoice_amount;
        $updateDraft->due = $request->total_invoice_amount;
        $updateDraft->save();

        return $updateDraft;
    }

    private function filteredQuery(object $request, object $query): object
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

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.draft_date_ts', $date_range); // Final
        }

        return $query;
    }

    public function singleDraft(int $id, array $with = null): ?object
    {
        $query = Sale::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
