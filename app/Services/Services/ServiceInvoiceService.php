<?php

namespace App\Services\Services;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Enums\PaymentStatus;
use App\Enums\SaleScreenType;
use App\Enums\ShipmentStatus;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ServiceInvoiceService
{
    public function serviceInvoicesListTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $sales = '';

        $query = DB::table('sales');
        $query->leftJoin('sales as salesOrder', 'sales.sales_order_id', 'salesOrder.id');
        $query->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id');
        $query->leftJoin('branches', 'sales.branch_id', 'branches.id');

        $query->leftJoin('service_job_cards', 'sales.id', 'service_job_cards.sale_id');
        $query->leftJoin('service_devices', 'service_job_cards.device_id', 'service_devices.id');
        $query->leftJoin('service_device_models', 'service_job_cards.device_model_id', 'service_device_models.id');
        $query->leftJoin('service_status', 'service_job_cards.status_id', 'service_status.id');

        $query->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');
        $query->leftJoin('currencies', 'branches.currency_id', 'currencies.id');
        $query->leftJoin('users as created_by', 'sales.created_by_id', 'created_by.id');
        $query->where('sales.status', SaleStatus::Final->value);

        $this->filteredQuery(request: $request, query: $query, saleScreen: SaleScreenType::ServicePosSale->value);

        $jobCardData = [];

        $sales = $query->select(
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

            'service_job_cards.id as job_card_id',
            'service_job_cards.job_no',
            'service_job_cards.delivery_date_ts',
            'service_job_cards.serial_no',
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
            'currencies.currency_rate as c_rate'
        )->orderBy('sales.sale_date_ts', 'desc');

        $dataTables = DataTables::of($sales);

        $dataTables->addColumn('action', function ($row) {

            $html = '<div class="btn-group" role="group">';
            $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
            $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
            $html .= '<a href="' . route('sales.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

            if (auth()->user()->branch_id == $row->branch_id) {

                if (auth()->user()->can('service_invoices_edit')) {

                    $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id, $row->sale_screen]) . '">' . __('Edit') . '</a>';
                }

                if (auth()->user()->can('service_invoices_delete')) {

                    $html .= '<a href="' . route('services.invoices.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
                }
            }

            if (auth()->user()->can('shipment_access') && config('generalSettings')['subscription']->features['sales'] == BooleanType::True->value) {

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

        $dataTables->editColumn('delivery_date', function ($row) use ($generalSettings) {

            $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

            return isset($row->delivery_date_ts) ? date($__date_format, strtotime($row->delivery_date_ts)) : '';
        });

        $dataTables->editColumn('job_no', function ($row) use ($generalSettings) {

            if (isset($row->job_no)) {

                return '<a href="' . route('services.job.cards.show', [$row->job_card_id]) . '" id="details_btn">' . $row->job_no . '</a>';
            }
        });

        $dataTables->editColumn('status_name', function ($row) use ($generalSettings) {

            if (isset($row->status_name)) {

                return '<span class="fw-bold" style="color:' . $row->status_color_code . ';">' . $row->status_name . '</span>';
            }
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

        $dataTables->rawColumns(['action', 'date', 'delivery_date', 'job_no', 'status_name', 'total_item', 'total_qty', 'total_invoice_amount', 'received_amount', 'invoice_id', 'branch', 'customer', 'due', 'sale_return_amount', 'payment_status', 'created_by']);

        return $dataTables->make(true);
    }

    private function filteredQuery(object $request, object $query, int $saleScreen = null)
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

        $query->where('sales.sale_screen', $saleScreen);

        if (auth()->user()->can('view_only_won_transactions')) {

            $query->where('sales.created_by_id', auth()->user()->id);
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
