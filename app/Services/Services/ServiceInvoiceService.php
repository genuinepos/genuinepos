<?php

namespace App\Services\Services;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Enums\SaleScreenType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ServiceInvoiceService
{
    public function serviceInvoicesTable($request): object
    {
        $generalSettings = config('generalSettings');
        $invoices = '';

        $query = DB::table('sales')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('service_job_cards', 'sales.id', 'service_job_cards.sale_id')
            ->leftJoin('service_devices', 'service_job_cards.device_id', 'service_devices.id')
            ->leftJoin('service_device_models', 'service_job_cards.device_model_id', 'service_device_models.id')
            ->leftJoin('service_status', 'service_job_cards.status_id', 'service_status.id')
            ->leftJoin('users as created_by', 'sales.created_by_id', 'created_by.id')
            ->where('sales.status', SaleStatus::Final->value);

        $this->filteredQuery(request: $request, query: $query, customerAccountId: $customerAccountId, saleScreen: SaleScreenType::ServicePosSale->value);

        $invoices = $query->select(
            'sales.id',
            'sales.branch_id',
            'sales.invoice_id',
            'sales.date',
            'sales.total_invoice_amount',
            'sales.sale_return_amount',
            'sales.paid as received_amount',
            'sales.due',
            'sales.is_return_available',
            'service_devices.name as device_name',
            'service_device_models.name as device_model_name',
            'service_status.name as status_name',
            'service_status.color_code as status_color_code',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'customers.name as customer_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        )->orderBy('sales.sale_date_ts', 'desc');

        return DataTables::of($sales)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="' . route('sales.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('pos_edit')) {

                        $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                    }

                    if (auth()->user()->can('delete_add_sale')) {

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
            ->editColumn('invoice_id', function ($row) {

                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->shipment_status != ShipmentStatus::NoStatus->value && $row->shipment_status != ShipmentStatus::Cancelled->value ? ' <i class="fas fa-shipping-fast text-dark"></i>' : '';
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo text-white"></i></span>' : '';

                $link = '';
                $link .= '<a href="' . route('sales.show', [$row->id]) . '" id="details_btn">' . $html . '</a>';

                return $link;
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->branch_area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->branch_area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })
            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . \App\Utils\Converter::format_in_bdt($row->total_item) . '</span>')

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>')

            ->editColumn('total_invoice_amount', fn ($row) => '<span class="total_invoice_amount" data-value="' . $row->total_invoice_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_invoice_amount) . '</span>')

            ->editColumn('received_amount', fn ($row) => '<span class="paid received_amount text-success" data-value="' . $row->received_amount . '">' . \App\Utils\Converter::format_in_bdt($row->received_amount) . '</span>')

            ->editColumn('sale_return_amount', fn ($row) => '<span class="sale_return_amount" data-value="' . $row->sale_return_amount . '">' . \App\Utils\Converter::format_in_bdt($row->sale_return_amount) . '</span>')

            ->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="' . $row->due . '">' . \App\Utils\Converter::format_in_bdt($row->due) . '</span>')

            ->editColumn('payment_status', function ($row) {

                $receivable = $row->total_invoice_amount - $row->sale_return_amount;

                if ($row->due <= 0) {

                    return '<span class="text-success"><b>' . __('Paid') . '</span>';
                } elseif ($row->due > 0 && $row->due < $receivable) {

                    return '<span class="text-primary"><b>' . __('Partial') . '</b></span>';
                } elseif ($receivable == $row->due) {

                    return '<span class="text-danger"><b>' . __('Due') . '</b></span>';
                }
            })

            ->editColumn('created_by', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })

            ->rawColumns(['action', 'date', 'total_item', 'total_qty', 'total_invoice_amount', 'received_amount', 'invoice_id', 'branch', 'customer', 'due', 'sale_return_amount', 'payment_status', 'created_by'])
            ->make(true);
    }
}
