<?php

namespace App\Services\TransferStocks;

use App\Enums\BooleanType;
use App\Enums\TransferStockReceiveStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReceiveStockFromWarehouseService
{
    public function receivableTransferredStockTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $transferStocks = '';

        $query = DB::table('transfer_stocks')
            ->leftJoin('branches', 'transfer_stocks.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('branches as sender_branch', 'transfer_stocks.sender_branch_id', 'sender_branch.id')
            ->leftJoin('branches as sender_branch_parent', 'sender_branch.parent_branch_id', 'sender_branch_parent.id')
            ->leftJoin('branches as receiver_branch', 'transfer_stocks.receiver_branch_id', 'receiver_branch.id')
            ->leftJoin('branches as receiver_branch_parent', 'receiver_branch.parent_branch_id', 'receiver_branch_parent.id')
            ->leftJoin('warehouses as sender_warehouse', 'transfer_stocks.sender_warehouse_id', 'sender_warehouse.id')
            ->leftJoin('warehouses as receiver_warehouse', 'transfer_stocks.receiver_warehouse_id', 'receiver_warehouse.id')
            ->leftJoin('users as send_by', 'transfer_stocks.send_by_id', 'send_by.id');

        $this->filter($request, $query);

        $transferStocks = $query->select(
            'transfer_stocks.*',
            'branches.name as branch_name',
            'branches.area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'sender_branch.name as sender_branch_name',
            'sender_branch.area_name as sender_branch_area_name',
            'sender_branch.branch_code as sender_branch_code',
            'sender_branch_parent.name as sender_parent_branch_name',
            'receiver_branch.name as receiver_branch_name',
            'receiver_branch.area_name as receiver_branch_area_name',
            'receiver_branch.branch_code as receiver_branch_code',
            'receiver_branch_parent.name as receiver_parent_branch_name',
            'sender_warehouse.warehouse_name as sender_warehouse_name',
            'sender_warehouse.warehouse_code as sender_warehouse_code',
            'receiver_warehouse.warehouse_name as receiver_warehouse_name',
            'receiver_warehouse.warehouse_code as receiver_warehouse_code',
            'send_by.prefix as send_prefix',
            'send_by.name as send_name',
            'send_by.last_name as send_last_name',
        )->orderBy('transfer_stocks.date_ts', 'desc');

        return DataTables::of($transferStocks)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                $html .= '<a href="' . route('transfer.stocks.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

                $html .= '<a href="' . route('receive.stock.from.warehouse.create', [$row->id]) . '" class="dropdown-item">' . __('Process To Receive') . '</a>';
                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                return date($__date_format, strtotime($row->date));
            })
            ->editColumn('voucher_no', function ($row) {

                return '<a href="' . route('transfer.stocks.show', [$row->id]) . '" id="details_btn">' . $row->voucher_no . '</a>';
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
            ->editColumn('send_from', function ($row) use ($generalSettings) {
                $senderBranch = '';
                $senderWarehouse = '';
                if ($row->sender_branch_id) {

                    if ($row->sender_parent_branch_name) {

                        $senderBranch = '<strong>' . __('Send From') . ':</strong> ' . $row->sender_parent_branch_name . '(' . $row->sender_branch_area_name . ')';
                    } else {

                        $senderBranch = '<strong>' . __('Send From') . ':</strong> ' . $senderBranch = $row->sender_branch_name . '(' . $row->sender_branch_area_name . ')';
                    }
                } else {

                    $senderBranch = '<strong>' . __('Send From') . ':</strong> ' . $generalSettings['business_or_shop__business_name'];
                }

                if ($row->sender_warehouse_id) {

                    $senderWarehouse = '<strong>' . __('At') . ':</strong> ' . $row->sender_warehouse_name . '(' . $row->sender_warehouse_code . ')';
                }

                return '<p class="m-0 p-0">' . $senderBranch . '</p><p class="m-0 p-0">' . $senderWarehouse . '</p>';
            })
            ->editColumn('send_to', function ($row) use ($generalSettings) {
                $receiverBranch = '';
                $receiverWarehouse = '';
                if ($row->receiver_branch_id) {

                    if ($row->receiver_parent_branch_name) {

                        $receiverBranch = '<strong>' . __('Send To') . ':</strong> ' . $row->receiver_parent_branch_name . '(' . $row->receiver_branch_area_name . ')';
                    } else {

                        $receiverBranch = '<strong>' . __('Send To') . ':</strong> ' . $row->receiver_branch_name . '(' . $row->receiver_branch_area_name . ')';
                    }
                } else {

                    $receiverBranch = '<strong>' . __('Send To') . ':</strong> ' . $generalSettings['business_or_shop__business_name'];
                }

                if ($row->receiver_warehouse_id) {

                    $receiverWarehouse = '<strong>' . __('Receive At') . ':</strong> ' . $row->receiver_warehouse_name . '(' . $row->receiver_warehouse_code . ')';
                }

                return '<p class="m-0 p-0">' . $receiverBranch . '</p><p class="m-0 p-0">' . $receiverWarehouse . '</p>';
            })

            ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . \App\Utils\Converter::format_in_bdt($row->total_item) . '</span>')

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>')

            ->editColumn('total_stock_value', fn ($row) => '<span class="total_stock_value" data-value="' . $row->total_stock_value . '">' . \App\Utils\Converter::format_in_bdt($row->total_stock_value) . '</span>')

            ->editColumn('total_send_qty', fn ($row) => '<span class="total_send_qty" data-value="' . $row->total_send_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_send_qty) . '</span>')

            ->editColumn('total_received_qty', fn ($row) => '<span class="total_received_qty text-success" data-value="' . $row->total_received_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_received_qty) . '</span>')

            ->editColumn('total_pending_qty', fn ($row) => '<span class="total_pending_qty text-danger" data-value="' . $row->total_pending_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_pending_qty) . '</span>')

            ->editColumn('receive_status', function ($row) {

                if ($row->receive_status == TransferStockReceiveStatus::Completed->value) {

                    return '<span class="text-success">' . __('Completed') . '</span>';
                } elseif ($row->receive_status == TransferStockReceiveStatus::Partial->value) {

                    return '<span class="text-primary">' . __('Partial') . '</span>';
                } elseif ($row->receive_status == TransferStockReceiveStatus::Pending->value) {

                    return '<span class="text-danger">' . __('Pending') . '</span>';
                }
            })

            ->editColumn('send_by', function ($row) {

                return $row->send_prefix . ' ' . $row->send_name . ' ' . $row->send_last_name;
            })

            ->rawColumns(['action', 'date', 'voucher_no', 'branch', 'send_from', 'send_to', 'total_item', 'total_qty', 'total_stock_value', 'total_send_qty', 'branch', 'total_received_qty', 'due', 'total_pending_qty', 'receive_status', 'send_by'])
            ->make(true);
    }

    private function filter(object $request, object $query): object
    {
        if ($request->receive_status != '') {

            $query->where('transfer_stocks.receive_status', $request->receive_status);
        }

        if ($request->warehouse_id) {

            $query->where('transfer_stocks.receiver_warehouse_id', $request->warehouse_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('transfer_stocks.date_ts', $date_range); // Final
        }

        $query->where('transfer_stocks.receiver_warehouse_id', '!=', null)
            ->where('receiver_warehouse.branch_id', auth()->user()->branch_id)
            ->orWhere('receiver_warehouse.is_global', BooleanType::True->value);

        return $query;
    }
}
