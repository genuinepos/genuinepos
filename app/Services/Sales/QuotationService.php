<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Models\Sales\Sale;
use App\Enums\SaleScreenType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class QuotationService
{
    public function quotationListTable(object $request, ?int $saleScreenType = null)
    {
        $generalSettings = config('generalSettings');
        $quotations = '';

        $query = DB::table('sales')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('users as created_by', 'sales.created_by_id', 'created_by.id')
            ->where('sales.quotation_status', BooleanType::True->value);

        if ($saleScreenType == SaleScreenType::ServiceQuotation->value) {

            $query->whereIn('sales.sale_screen', [SaleScreenType::ServiceQuotation->value, SaleScreenType::ServicePosSale->value]);
        } else {

            $query->whereIn('sales.sale_screen', [SaleScreenType::AddSale->value, SaleScreenType::PosSale->value]);
        }

        $this->filteredQuery($request, $query);

        $quotations = $query->select(
            'sales.id',
            'sales.branch_id',
            'sales.quotation_id',
            'sales.date',
            'sales.total_item',
            'sales.total_qty',
            'sales.total_invoice_amount',
            'sales.order_status',
            'sales.sale_screen',

            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'customers.name as customer_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        )->orderBy('sales.quotation_date_ts', 'desc');

        return DataTables::of($quotations)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="' . route('sale.quotations.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

                if (auth()->user()->branch_id == $row->branch_id) {

                    if ($row->sale_screen == SaleScreenType::ServiceQuotation->value || $row->sale_screen == SaleScreenType::ServicePosSale->value) {
                        if (auth()->user()->can('sale_quotation')) {

                            if ($row->sale_screen == SaleScreenType::ServiceQuotation->value) {

                                $html .= '<a class="dropdown-item" href="' . route('services.quotations.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                            } else {

                                $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id, $row->sale_screen]) . '">' . __('Edit') . '</a>';
                            }
                        }
                    } else {

                        if (auth()->user()->can('sale_quotation')) {

                            if ($row->sale_screen == SaleScreenType::AddSale->value) {

                                $html .= '<a class="dropdown-item" href="' . route('sale.quotations.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                            } else {

                                $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                            }
                        }
                    }
                }

                if (auth()->user()->branch_id == $row->branch_id) {

                    if ($row->sale_screen == SaleScreenType::ServiceQuotation->value || $row->sale_screen == SaleScreenType::ServicePosSale->value) {
                        if (auth()->user()->can('sale_quotation')) {

                            $html .= '<a href="' . route('services.quotations.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
                        }
                    } else {

                        if (auth()->user()->can('sale_quotation')) {

                            $html .= '<a href="' . route('sales.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
                        }
                    }
                }

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('sale_quotation') && $row->sale_screen != SaleScreenType::ServiceQuotation->value) {

                        $html .= '<a href="' . route('sale.quotations.status.edit', [$row->id]) . '" class="dropdown-item" id="changeQuotationStatusBtn">' . __('Change Current Status') . '</a>';
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
            ->editColumn('quotation_id', function ($row) {

                return '<a href="' . route('sale.quotations.show', [$row->id]) . '" id="details_btn">' . $row->quotation_id . '</a>';
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
            ->editColumn('current_status', function ($row) {

                if ($row->order_status == 1) {

                    return '<span class="badge badge-sm bg-primary">' . __('Ordered') . '</span>';
                } else {

                    return '<span class="badge badge-sm bg-info">' . __('Quotation') . '</span>';
                }
            })
            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . \App\Utils\Converter::format_in_bdt($row->total_item) . '</span>')

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>')

            ->editColumn('total_invoice_amount', fn ($row) => '<span class="total_invoice_amount" data-value="' . $row->total_invoice_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_invoice_amount) . '</span>')

            ->editColumn('created_by', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })

            ->rawColumns(['action', 'date', 'total_item', 'total_qty', 'total_invoice_amount', 'quotation_id', 'branch', 'current_status', 'customer', 'created_by'])
            ->make(true);
    }

    public function updateQuotation(object $request, object $updateQuotation): object
    {
        foreach ($updateQuotation->saleProducts as $saleProduct) {

            $saleProduct->is_delete_in_update = BooleanType::True->value;
            $saleProduct->save();
        }

        $updateQuotation->sale_account_id = $request->sale_account_id;
        $updateQuotation->customer_account_id = $request->customer_account_id;
        $updateQuotation->pay_term = $request->pay_term;
        $updateQuotation->pay_term_number = $request->pay_term_number;
        $updateQuotation->date = $request->date;
        $time = date(' H:i:s', strtotime($updateQuotation->date_ts));
        $updateQuotation->date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        $updateQuotation->quotation_date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        $updateQuotation->total_item = $request->total_item;
        $updateQuotation->total_qty = $request->total_qty;
        $updateQuotation->total_ordered_qty = $request->total_qty;
        $updateQuotation->net_total_amount = $request->net_total_amount;
        $updateQuotation->order_discount_type = $request->order_discount_type;
        $updateQuotation->order_discount = $request->order_discount;
        $updateQuotation->order_discount_amount = $request->order_discount_amount;
        $updateQuotation->sale_tax_ac_id = $request->sale_tax_ac_id;
        $updateQuotation->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0;
        $updateQuotation->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $updateQuotation->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $updateQuotation->shipment_details = $request->shipment_details;
        $updateQuotation->shipment_address = $request->shipment_address;
        $updateQuotation->shipment_status = $request->shipment_status ? $request->shipment_status : 0;
        $updateQuotation->delivered_to = $request->delivered_to;
        $updateQuotation->note = $request->note;
        $updateQuotation->total_invoice_amount = $request->total_invoice_amount;
        $updateQuotation->save();

        return $updateQuotation;
    }

    public function updateQuotationStatus(object $request, int $id, object $codeGenerator, string $salesOrderPrefix = null): array|object
    {
        $quotation = $this->singleQuotation(id: $id);

        $restrictions = $this->restrictions(request: $request, quotation: $quotation);

        if (isset($restrictions['pass']) && $restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        if ($quotation->status != $request->status) {

            if ($request->status == SaleStatus::Order->value) {

                $orderId = $codeGenerator->generateMonthWise(table: 'sales', column: 'order_id', prefix: $salesOrderPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

                $quotation->status = SaleStatus::Order->value;
                $quotation->order_id = $quotation->order_id == null ? $orderId : $quotation->order_id;
                $quotation->order_status = BooleanType::True->value;
                $quotation->total_ordered_qty = $quotation->total_quotation_qty;
                $quotation->order_date_ts = !isset($quotation->order_date) ? date('Y-m-d H:i:s') : $quotation->order_date;
                $quotation->save();
            } else {

                $quotation->status = SaleStatus::Quotation->value;
                $quotation->order_id = null;
                $quotation->order_status = BooleanType::False->value;
                $quotation->order_date_ts = null;
                $quotation->save();
            }
        }

        return $quotation;
    }

    public function restrictions(object $request, object $quotation): array
    {
        $currentStatus = SaleStatus::tryFrom($quotation->status)->value;
        if ($currentStatus == SaleStatus::Order->value && $request->status == SaleStatus::Quotation->value) {

            if ($quotation->total_delivered_qty > 0) {

                return ['pass' => false, 'msg' => __('Quotation current status can not be changed to quotation again. Now current status is Order and Invoice is exists against the Order')];
            } elseif ($quotation->paid > 0) {

                return ['pass' => false, 'msg' => __('Quotation current status can not be changed to quotation again. Now current status is order and receipt voucher is exists against the order')];
            }
        }

        return ['pass' => true];
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
            $query->whereBetween('sales.order_date_ts', $date_range); // Final
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            if (auth()->user()->can('view_own_sale')) {

                $query->where('sales.created_by_id', auth()->user()->id);
            }

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }

    public function singleQuotation(int $id, array $with = null): ?object
    {
        $query = Sale::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
