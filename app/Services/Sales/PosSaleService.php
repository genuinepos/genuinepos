<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Models\Sales\Sale as PosSale;
use App\Enums\PaymentStatus;
use App\Enums\SaleScreenType;
use App\Enums\ShipmentStatus;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PosSaleService
{
    public function posSalesTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $posSales = '';

        $query = DB::table('sales');
        $query->leftJoin('sales as salesOrder', 'sales.sales_order_id', 'salesOrder.id');
        $query->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id');
        $query->leftJoin('branches', 'sales.branch_id', 'branches.id');
        $query->leftJoin('currencies', 'branches.currency_id', 'currencies.id');

        $query->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');
        $query->leftJoin('users as created_by', 'sales.created_by_id', 'created_by.id');
        $query->where('sales.status', SaleStatus::Final->value);

        $this->filteredQuery(request: $request, query: $query);

        $posSales = $query->select(
            'sales.id',
            'sales.branch_id',
            'sales.invoice_id',
            'sales.date',
            'sales.total_item',
            'sales.total_qty',
            'sales.total_invoice_amount',
            'sales.sale_return_amount',
            'sales.paid as received_amount',
            'sales.due',
            'sales.is_return_available',
            'sales.shipment_status',
            'sales.sale_screen',
            'salesOrder.id as sales_order_id',
            'salesOrder.order_id',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'customers.name as customer_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
            'currencies.currency_rate as c_rate'
        )->orderBy('sales.sale_date_ts', 'desc');

        $dataTables = DataTables::of($posSales);

        $dataTables->addColumn('action', function ($row) {

            $html = '<div class="btn-group" role="group">';
            $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
            $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
            $html .= '<a href="' . route('sales.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

            if (auth()->user()->branch_id == $row->branch_id) {

                if (auth()->user()->can('pos_edit')) {

                    $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id, $row->sale_screen]) . '">' . __('Edit') . '</a>';
                }

                if (auth()->user()->can('pos_delete')) {

                    $html .= '<a href="' . route('sales.pos.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
                }
            }

            if (auth()->user()->can('shipment_access')) {

                $html .= '<a class="dropdown-item" id="editShipmentDetails" href="' . route('sale.shipments.edit', [$row->id]) . '">' . __('Edit Shipment Details') . '</a>';
            }

            $html .= '</div>';
            $html .= '</div>';

            return $html;
        });

        $dataTables->editColumn('date', function ($row) use ($generalSettings) {

            $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

            return date($__date_format, strtotime($row->date));
        });

        $dataTables->editColumn('invoice_id', function ($row) {

            $html = '';
            $html .= $row->invoice_id;
            $html .= $row->shipment_status != ShipmentStatus::NoStatus->value && $row->shipment_status != ShipmentStatus::Cancelled->value ? ' <i class="fas fa-shipping-fast text-dark"></i>' : '';
            $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo text-white"></i></span>' : '';

            $link = '';
            $link .= '<a href="' . route('sales.show', [$row->id]) . '" id="details_btn">' . $html . '</a>';

            return $link;
        });

        $dataTables->editColumn('branch', function ($row) use ($generalSettings) {

            if ($row->branch_id) {

                if ($row->parent_branch_name) {

                    return $row->parent_branch_name . '(' . $row->branch_area_name . ')';
                } else {

                    return $row->branch_name . '(' . $row->branch_area_name . ')';
                }
            } else {

                return $generalSettings['business_or_shop__business_name'];
            }
        });

        $dataTables->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer');

        $dataTables->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . \App\Utils\Converter::format_in_bdt($row->total_item) . '</span>');

        $dataTables->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>');

        $dataTables->editColumn('total_invoice_amount', fn ($row) => '<span class="total_invoice_amount" data-value="' . curr_cnv($row->total_invoice_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->total_invoice_amount, $row->c_rate, $row->branch_id)) . '</span>');

        $dataTables->editColumn('received_amount', fn ($row) => '<span class="paid received_amount text-success" data-value="' . curr_cnv($row->received_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->received_amount, $row->c_rate, $row->branch_id)) . '</span>');

        $dataTables->editColumn('sale_return_amount', fn ($row) => '<span class="sale_return_amount" data-value="' . curr_cnv($row->sale_return_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->sale_return_amount, $row->c_rate, $row->branch_id)) . '</span>');

        $dataTables->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="' . curr_cnv($row->due, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->due, $row->c_rate, $row->branch_id)) . '</span>');

        $dataTables->editColumn('payment_status', function ($row) {

            $receivable = $row->total_invoice_amount - $row->sale_return_amount;

            if ($row->due <= 0) {

                return '<span class="text-success"><b>' . __('Paid') . '</span>';
            } elseif ($row->due > 0 && $row->due < $receivable) {

                return '<span class="text-primary"><b>' . __('Partial') . '</b></span>';
            } elseif ($receivable == $row->due) {

                return '<span class="text-danger"><b>' . __('Due') . '</b></span>';
            }
        });

        $dataTables->editColumn('created_by', function ($row) {

            return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
        });

        $dataTables->rawColumns(['action', 'date', 'total_item', 'total_qty', 'total_invoice_amount', 'received_amount', 'invoice_id', 'branch', 'customer', 'due', 'sale_return_amount', 'payment_status', 'created_by']);

        return $dataTables->make(true);
    }

    public function addPosSale(object $request, int $saleScreenType, object $codeGenerator, ?string $invoicePrefix, ?string $quotationPrefix, ?string $dateFormat): object
    {
        $transId = '';
        if ($request->status == SaleStatus::Final->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'invoice_id', prefix: $invoicePrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        } elseif ($request->status == SaleStatus::Quotation->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'quotation_id', prefix: $quotationPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        } elseif ($request->status == SaleStatus::Draft->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'draft_id', prefix: 'DRF', splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        } elseif ($request->status == SaleStatus::Hold->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'hold_invoice_id', prefix: 'HINV', splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        } elseif ($request->status == SaleStatus::Suspended->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'suspend_id', prefix: 'SPND', splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        }

        $addSale = new PosSale();
        $addSale->invoice_id = $request->status == SaleStatus::Final->value ? $transId : null;
        $addSale->quotation_id = $request->status == SaleStatus::Quotation->value ? $transId : null;
        $addSale->draft_id = $request->status == SaleStatus::Draft->value ? $transId : null;
        $addSale->hold_invoice_id = $request->status == SaleStatus::Hold->value ? $transId : null;
        $addSale->suspend_id = $request->status == SaleStatus::Suspended->value ? $transId : null;
        $addSale->created_by_id = auth()->user()->id;
        $addSale->sale_account_id = $request->sale_account_id;
        $addSale->branch_id = auth()->user()->branch_id;
        $addSale->customer_account_id = $request->customer_account_id;
        $addSale->status = $request->status;
        $addSale->date = date($dateFormat);
        $addSale->date_ts = date('Y-m-d H:i:s');
        $addSale->sale_date_ts = $request->status == SaleStatus::Final->value ? date('Y-m-d H:i:s') : null;
        $addSale->quotation_date_ts = $request->status == SaleStatus::Quotation->value ? date('Y-m-d H:i:s') : null;
        $addSale->draft_date_ts = $request->status == SaleStatus::Draft->value ? date('Y-m-d H:i:s') : null;
        $addSale->quotation_status = $request->status == SaleStatus::Quotation->value ? BooleanType::True->value : 0;
        $addSale->draft_status = $request->status == SaleStatus::Draft->value ? BooleanType::True->value : 0;
        $addSale->total_item = $request->total_item;
        $addSale->total_qty = $request->total_qty;
        $addSale->total_sold_qty = $request->status == SaleStatus::Final->value ? $request->total_qty : 0;
        $addSale->total_quotation_qty = $request->status == SaleStatus::Quotation->value ? $request->total_qty : 0;
        $addSale->net_total_amount = $request->net_total_amount;
        $addSale->order_discount_type = $request->order_discount_type;
        $addSale->order_discount = $request->order_discount ? $request->order_discount : 0;
        $addSale->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0;
        $addSale->sale_tax_ac_id = $request->sale_tax_ac_id;
        $addSale->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0;
        $addSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $addSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $addSale->change_amount = $request->change_amount > 0 ? $request->change_amount : 0.00;
        $addSale->total_invoice_amount = $request->total_invoice_amount;
        $addSale->due = $request->total_invoice_amount;
        $addSale->sale_screen = $saleScreenType;
        $addSale->save();

        return $addSale;
    }

    public function updatePosSale(object $updateSale, object $request, object $codeGenerator, ?string $invoicePrefix, ?string $quotationPrefix, ?string $dateFormat): object
    {
        foreach ($updateSale->saleProducts as $saleProduct) {

            $saleProduct->is_delete_in_update = BooleanType::True->value;
            $saleProduct->save();
        }

        $transId = '';
        if ($request->status == SaleStatus::Final->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'invoice_id', prefix: $invoicePrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        } elseif ($request->status == SaleStatus::Quotation->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'quotation_id', prefix: $quotationPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        } elseif ($request->status == SaleStatus::Draft->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'draft_id', prefix: 'DRF', splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        } elseif ($request->status == SaleStatus::Hold->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'hold_invoice_id', prefix: 'HINV', splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        } elseif ($request->status == SaleStatus::Suspended->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'suspend_id', prefix: 'SPND', splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        }

        $updateSale->invoice_id = $request->status == SaleStatus::Final->value && !isset($updateSale->invoice_id) ? $transId : $updateSale->invoice_id;
        $updateSale->quotation_id = ($request->status == SaleStatus::Quotation->value && !isset($updateSale->quotation_id) ? $transId : $updateSale->quotation_id);
        $updateSale->draft_id = $request->status == SaleStatus::Draft->value && !isset($updateSale->draft_id) ? $transId : $updateSale->draft_id;
        $updateSale->hold_invoice_id = $request->status == SaleStatus::Hold->value && !isset($updateSale->hold_invoice_id) ? $transId : $updateSale->hold_invoice_id;
        $updateSale->suspend_id = $request->status == SaleStatus::Suspended->value && !isset($updateSale->suspend_id) ? $transId : $updateSale->suspend_id;
        $updateSale->customer_account_id = $request->customer_account_id;
        $updateSale->status = $request->status;
        $updateSale->quotation_date_ts = $request->status == SaleStatus::Quotation->value && !isset($updateSale->quotation_date_ts) ? date('Y-m-d H:i:s') : $updateSale->quotation_date_ts;
        $updateSale->sale_date_ts = $request->status == SaleStatus::Final->value && !isset($updateSale->sale_date_ts) ? date('Y-m-d H:i:s') : $updateSale->sale_date_ts;
        $updateSale->draft_date_ts = $request->status == SaleStatus::Draft->value && !isset($updateSale->draft_date_ts) ? date('Y-m-d H:i:s') : $updateSale->draft_date_ts;
        $updateSale->draft_status = $request->status == SaleStatus::Draft->value ? BooleanType::True->value : $updateSale->draft_status;
        $updateSale->quotation_status = $request->status == SaleStatus::Quotation->value ? BooleanType::True->value : $updateSale->quotation_status;
        $updateSale->total_item = $request->total_item;
        $updateSale->total_qty = $request->total_qty;
        $updateSale->total_sold_qty = $request->status == SaleStatus::Final->value ? $request->total_qty : $updateSale->total_sold_qty;
        $updateSale->total_quotation_qty = $request->status == SaleStatus::Quotation->value ? $request->total_qty : $updateSale->total_quotation_qty;
        $updateSale->total_quotation_qty = $updateSale->quotation_status == BooleanType::True->value ? $request->total_qty : $updateSale->total_quotation_qty;
        $updateSale->net_total_amount = $request->net_total_amount;
        $updateSale->order_discount_type = $request->order_discount_type;
        $updateSale->order_discount = $request->order_discount;
        $updateSale->order_discount_amount = $request->order_discount_amount;
        $updateSale->sale_tax_ac_id = $request->sale_tax_ac_id;
        $updateSale->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0;
        $updateSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $updateSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $updateSale->change_amount = $request->change_amount > 0 ? $request->change_amount : 0;
        $updateSale->total_invoice_amount = $request->total_invoice_amount ? $request->total_invoice_amount : 0;
        $updateSale->save();

        return $updateSale;
    }

    public function printTemplateBySaleStatusForStore(object $request, object $sale, object $customerCopySaleProducts): mixed
    {
        $printPageSize = $request->print_page_size;
        if ($request->status == SaleStatus::Final->value) {

            $changeAmount = $request->change_amount > 0 ? $request->change_amount : 0;
            $receivedAmount = $request->received_amount;

            return view('sales.print_templates.sale_print', compact('sale', 'receivedAmount', 'changeAmount', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($request->status == SaleStatus::Draft->value) {

            $draft = $sale;
            return view('sales.print_templates.draft_print', compact('draft', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($request->status == SaleStatus::Quotation->value) {

            $quotation = $sale;
            return view('sales.print_templates.quotation_print', compact('quotation', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($request->status == SaleStatus::Hold->value) {

            return response()->json(['holdInvoiceMsg' => __('Invoice is hold.')]);
        } elseif ($request->status == SaleStatus::Suspended->value) {

            return response()->json(['suspendedInvoiceMsg' => __('Invoice is suspended.')]);
        }
    }

    public function printTemplateBySaleStatusForUpdate(object $request, object $sale, object $customerCopySaleProducts): mixed
    {
        $printPageSize = $request->print_page_size;
        if ($request->status == SaleStatus::Final->value) {

            $changeAmount = $request->change_amount > 0 ? $request->change_amount : 0;
            $receivedAmount = $request->received_amount;
            return view('sales.print_templates.sale_print', compact('sale', 'receivedAmount', 'changeAmount', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($request->status == SaleStatus::Draft->value) {

            $draft = $sale;
            return view('sales.print_templates.draft_print', compact('draft', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($request->status == SaleStatus::Quotation->value) {

            $quotation = $sale;
            return view('sales.print_templates.quotation_print', compact('quotation', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($request->status == SaleStatus::Hold->value) {

            return response()->json(['holdInvoiceMsg' => __('Invoice is hold.')]);
        } elseif ($request->status == SaleStatus::Suspended->value) {

            return response()->json(['suspendedInvoiceMsg' => __('Invoice is suspended.')]);
        }
    }

    private function filteredQuery(object $request, object $query)
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
            $query->whereBetween('sales.sale_date_ts', $date_range); // Final
        }

        $query->where('sales.sale_screen', SaleScreenType::PosSale->value);

        if (auth()->user()->can('view_own_sale')) {

            $query->where('sales.created_by_id', auth()->user()->id);
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
