<?php

namespace App\Services\TransferStocks;

use Carbon\Carbon;
use App\Enums\IsDeleteInUpdate;
use App\Enums\TransferStockType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Enums\TransferStockReceiveStatus;
use App\Models\TransferStocks\TransferStock;

class TransferStockService
{
    function transferStockTable(int $type, object $request): object
    {
        $generalSettings = config('generalSettings');

        $transferStocks = '';

        $query = DB::table('transfer_stocks')
            ->leftJoin('branches', 'transfer_stocks.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('branches as sender_branch', 'transfer_stocks.receiver_branch_id', 'sender_branch.id')
            ->leftJoin('branches as sender_branch_parent', 'sender_branch.parent_branch_id', 'sender_branch_parent.id')
            ->leftJoin('branches as receiver_branch', 'transfer_stocks.receiver_branch_id', 'receiver_branch.id')
            ->leftJoin('branches as receiver_branch_parent', 'receiver_branch.parent_branch_id', 'receiver_branch_parent.id')
            ->leftJoin('warehouses as sender_warehouse', 'transfer_stocks.sender_warehouse_id', 'sender_warehouse.id')
            ->leftJoin('warehouses as receiver_warehouse', 'transfer_stocks.receiver_warehouse_id', 'receiver_warehouse.id')
            ->leftJoin('users as send_by', 'transfer_stocks.send_by_id', 'send_by.id')
            ->where('transfer_stocks.type', $type);

        $this->filter($request, $query);

        $transferStocks = $query->select(
            'transfer_stocks.*',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
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
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __("Action") . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                if ($row->type == TransferStockType::WarehouseToBranch->value) {

                    $html .= '<a href="' . route('transfer.stock.warehouse.to.branch.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __("View") . '</a>';
                } else if ($row->type == TransferStockType::BranchToWarehouse->value) {

                    $html .= '<a href="' . route('transfer.stock.branch.to.warehouse.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __("View") . '</a>';
                } else if ($row->type == TransferStockType::BranchToBranch->value) {

                    $html .= '<a href="' . route('transfer.stock.branch.to.branch.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __("View") . '</a>';
                }

                if (auth()->user()->branch_id == $row->branch_id) {

                    if ($row->type == TransferStockType::WarehouseToBranch->value) {

                        $html .= '<a href="' . route('transfer.stock.warehouse.to.branch.edit', [$row->id]) . '" class="dropdown-item">' . __("Edit") . '</a>';
                    } else if ($row->type == TransferStockType::BranchToWarehouse->value) {

                        $html .= '<a href="' . route('transfer.stock.branch.to.warehouse.edit', [$row->id]) . '" class="dropdown-item">' . __("Edit") . '</a>';
                    } else if ($row->type == TransferStockType::BranchToBranch->value) {

                        $html .= '<a href="' . route('transfer.stock.branch.to.branch.edit', [$row->id]) . '" class="dropdown-item">' . __("Edit") . '</a>';
                    }
                }

                if (auth()->user()->branch_id == $row->branch_id) {

                    if ($row->type == TransferStockType::WarehouseToBranch->value) {

                        $html .= '<a href="' . route('transfer.stock.warehouse.to.branch.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __("Delete") . '</a>';
                    } else if ($row->type == TransferStockType::BranchToWarehouse->value) {

                        $html .= '<a href="' . route('transfer.stock.branch.to.warehouse.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __("Delete") . '</a>';
                    } else if ($row->type == TransferStockType::BranchToBranch->value) {

                        $html .= '<a href="' . route('transfer.stock.branch.to.branch.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __("Delete") . '</a>';
                    }
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business__date_format']);

                return date($__date_format, strtotime($row->date));
            })
            ->editColumn('voucher_no', function ($row) {

                if ($row->type == TransferStockType::WarehouseToBranch->value) {

                    return '<a href="' . route('transfer.stock.warehouse.to.branch.show', [$row->id]) . '" id="details_btn">' . $row->voucher_no . '</a>';
                } else if ($row->type == TransferStockType::BranchToWarehouse->value) {

                    return '<a href="' . route('transfer.stock.branch.to.warehouse.show', [$row->id]) . '" id="details_btn">' . $row->voucher_no . '</a>';
                } else if ($row->type == TransferStockType::BranchToBranch->value) {

                    return '<a href="' . route('transfer.stock.branch.to.branch.show', [$row->id]) . '" id="details_btn">' . $row->voucher_no . '</a>';
                }
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->parent_branch_area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->branch_area_name . ')';
                    }
                } else {

                    return $generalSettings['business__shop_name'];
                }
            })
            ->editColumn('send_from', function ($row) use ($generalSettings) {

                $senderBranch = '';
                $senderWarehouse = '';
                // if ($row->type == TransferStockType::BranchToBranch->value) {

                if ($row->sender_branch_id) {

                    if ($row->sender_parent_branch_name) {

                        $senderBranch = '<strong>Send From:</strong> ' . $row->sender_parent_branch_name . '(' . $row->sender_branch_area_name . ')';
                    } else {

                        $senderBranch = '<strong>Send From:</strong> ' . $senderBranch = $row->sender_branch_name . '(' . $row->sender_branch_area_name . ')';
                    }
                } else {

                    $senderBranch = '<strong>Send From:</strong> ' . $generalSettings['business__shop_name'];
                }


                if ($row->sender_warehouse_id) {

                    $senderWarehouse = '<strong>At:</strong> ' . $row->sender_warehouse_name . '(' . $row->sender_warehouse_code . ')';
                }

                return '<p class="m-0 p-0">' . $senderBranch  . '</p><p class="m-0 p-0">' . $senderWarehouse . '</p>';
                // }

                // if ($row->sender_warehouse_id) {

                //     return $row->sender_warehouse_name . '(' . $row->sender_warehouse_code . ')';
                // }

                // if ($row->sender_branch_id) {

                //     if ($row->sender_parent_branch_name) {

                //         return $row->sender_parent_branch_name . '(' . $row->sender_branch_area_name . ')';
                //     } else {

                //         return $row->sender_branch_name . '(' . $row->sender_branch_area_name . ')';
                //     }
                // } else {

                //     return $generalSettings['business__shop_name'];
                // }
            })
            ->editColumn('send_to', function ($row) use ($generalSettings) {

                $receiverBranch = '';
                $receiverWarehouse = '';
                // if ($row->type == TransferStockType::BranchToBranch->value) {

                if ($row->receiver_branch_id) {

                    if ($row->receiver_parent_branch_name) {

                        $receiverBranch = '<strong>Send To:</strong> ' . $row->receiver_parent_branch_name . '(' . $row->receiver_branch_area_name . ')';
                    } else {

                        $receiverBranch = '<strong>Send To:</strong> ' . $row->receiver_branch_name . '(' . $row->receiver_branch_area_name . ')';
                    }
                } else {

                    $receiverBranch = '<strong>Send To:</strong> ' . $generalSettings['business__shop_name'];
                }

                if ($row->receiver_warehouse_id) {

                    $receiverWarehouse = '<strong>Receive At:</strong> ' . $row->receiver_warehouse_name . '(' . $row->receiver_warehouse_code . ')';
                }

                return '<p class="m-0 p-0">' . $receiverBranch  . '</p><p class="m-0 p-0">' . $receiverWarehouse . '</p>';
                // }

                // if ($row->receiver_warehouse_id) {

                //     return $row->receiver_warehouse_name . '(' . $row->receiver_warehouse_code . ')';
                // }

                // if ($row->receiver_branch_id) {

                //     if ($row->receiver_parent_branch_name) {

                //         return $row->receiver_parent_branch_name . '(' . $row->receiver_branch_area_name . ')';
                //     } else {

                //         return $row->receiver_branch_name . '(' . $row->receiver_branch_area_name . ')';
                //     }
                // } else {

                //     return $generalSettings['business__shop_name'];
                // }
            })

            ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . \App\Utils\Converter::format_in_bdt($row->total_item) . '</span>')

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>')

            ->editColumn('total_stock_value', fn ($row) => '<span class="total_stock_value" data-value="' . $row->total_stock_value . '">' . \App\Utils\Converter::format_in_bdt($row->total_stock_value) . '</span>')

            ->editColumn('total_send_qty', fn ($row) => '<span class="total_send_qty" data-value="' . $row->total_send_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_send_qty) . '</span>')

            ->editColumn('total_received_qty', fn ($row) => '<span class="total_received_qty text-success" data-value="' . $row->total_received_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_received_qty) . '</span>')

            ->editColumn('total_pending_qty', fn ($row) => '<span class="total_pending_qty text-danger" data-value="' . $row->total_pending_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_pending_qty) . '</span>')

            ->editColumn('receive_status', function ($row) {

                if ($row->receive_status == TransferStockReceiveStatus::Completed->value) {

                    return '<span class="text-success">' . __("Completed") . '</span>';
                } elseif ($row->receive_status == TransferStockReceiveStatus::Partial->value) {

                    return '<span class="text-primary">' . __("Partial") . '</span>';
                } elseif ($row->receive_status == TransferStockReceiveStatus::Pending->value) {

                    return '<span class="text-danger">' . __("Pending") . '</span>';
                }
            })

            ->editColumn('send_by', function ($row) {

                return $row->send_prefix . ' ' . $row->send_name . ' ' . $row->send_last_name;
            })

            ->rawColumns(['action', 'date', 'voucher_no', 'branch', 'send_from', 'send_to', 'total_item', 'total_qty', 'total_stock_value', 'total_send_qty', 'branch', 'total_received_qty', 'due', 'total_pending_qty', 'receive_status', 'send_by'])
            ->make(true);
    }

    function addTransferStock(object $request, int $transferStockType, object $codeGenerator, $voucherPrefix): object
    {
        $voucherNo = $codeGenerator->generateMonthAndTypeWise(table: 'transfer_stocks', column: 'voucher_no', typeColName: 'type', typeValue: TransferStockType::tryFrom($transferStockType)->value, prefix: $voucherPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $addTransferStock = new TransferStock();
        $addTransferStock->voucher_no = $voucherNo;
        $addTransferStock->branch_id = auth()->user()->branch_id;

        if ($transferStockType == TransferStockType::WarehouseToBranch->value) {

            $addTransferStock->sender_branch_id = auth()->user()->branch_id;
            $addTransferStock->sender_warehouse_id = $request->sender_warehouse_id;
            $addTransferStock->receiver_branch_id = auth()->user()->branch_id;
        } else if ($transferStockType == TransferStockType::BranchToWarehouse->value) {

            $addTransferStock->sender_branch_id = auth()->user()->branch_id;
            $addTransferStock->receiver_branch_id = auth()->user()->branch_id;
            $addTransferStock->receiver_warehouse_id = $request->receiver_warehouse_id;
        } else if ($transferStockType == TransferStockType::BranchToBranch->value) {

            $addTransferStock->sender_branch_id = auth()->user()->branch_id;
            $addTransferStock->sender_warehouse_id = $request->sender_warehouse_id;
            $addTransferStock->receiver_branch_id = $request->receiver_branch_id == 'NULL' ? null : $request->receiver_branch_id;
            $addTransferStock->receiver_warehouse_id = $request->receiver_warehouse_id;
        }

        $addTransferStock->type = $transferStockType;
        $addTransferStock->total_item = $request->total_item;
        $addTransferStock->total_qty = $request->total_qty;
        $addTransferStock->total_stock_value = $request->total_stock_value;
        $addTransferStock->total_send_qty = $request->total_qty;
        $addTransferStock->total_pending_qty = $request->total_qty;
        $addTransferStock->receive_status = TransferStockReceiveStatus::Pending->value;
        $addTransferStock->transfer_note = $request->transfer_note;
        $addTransferStock->date = $request->date;
        $addTransferStock->date_ts = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addTransferStock->send_by_id = auth()->user()->id;
        $addTransferStock->save();

        return $addTransferStock;
    }

    function updateTransferStock(object $request, int $transferStockType, int $id): object
    {
        $updateTransferStock = $this->singleTransferStock(id: $id, with: ['transferStockProducts']);

        $previousSenderWarehouseId = $updateTransferStock->sender_warehouse_id;
        $previousReceiverWarehouseId = $updateTransferStock->receiver_warehouse_id;
        $previousSenderBranchId = $updateTransferStock->sender_branch_id;
        $previousReceiverBranchId = $updateTransferStock->receiver_branch_id;

        foreach ($updateTransferStock->transferStockProducts as $transferStockProduct) {

            $transferStockProduct->is_delete_in_update = IsDeleteInUpdate::Yes->value;
            $transferStockProduct->save();
        }

        if ($transferStockType == TransferStockType::WarehouseToBranch->value) {

            $updateTransferStock->sender_warehouse_id = $request->sender_warehouse_id;
        } else if ($transferStockType == TransferStockType::BranchToWarehouse->value) {

            $updateTransferStock->receiver_warehouse_id = $request->receiver_warehouse_id;
        } else if ($transferStockType == TransferStockType::BranchToBranch->value) {

            $updateTransferStock->sender_warehouse_id = $request->sender_warehouse_id;
            $updateTransferStock->receiver_branch_id = $request->receiver_branch_id == 'NULL' ? null : $request->receiver_branch_id;
            $updateTransferStock->receiver_warehouse_id = $request->receiver_warehouse_id;
        }

        $updateTransferStock->total_item = $request->total_item;
        $updateTransferStock->total_stock_value = $request->total_stock_value;
        $updateTransferStock->transfer_note = $request->transfer_note;
        $updateTransferStock->date = $request->date;
        $time = date(' H:i:s', strtotime($updateTransferStock->date_ts));
        $updateTransferStock->date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        $updateTransferStock->save();

        $updateTransferStock->previous_sender_warehouse_id = $previousSenderWarehouseId;
        $updateTransferStock->previous_receiver_warehouse_id = $previousReceiverWarehouseId;
        $updateTransferStock->previous_sender_branch_id = $previousSenderBranchId;
        $updateTransferStock->previous_receiver_branch_id = $previousReceiverBranchId;

        return $updateTransferStock;
    }

    function updateTransferStockReceiveStatus(object $transferStock): void
    {
        $calc = DB::table('transfer_stock_products')->where('transfer_stock_products.transfer_stock_id', $transferStock->id)
            ->select(
                DB::raw('SUM(send_qty) as total_send_qty'),
                DB::raw('SUM(received_qty) as total_received_qty'),
                DB::raw('SUM(pending_qty) as total_pending_qty')
            )->groupBy('transfer_stock_products.transfer_stock_id')->get();

        $transferStock->total_qty = $calc->sum('total_send_qty');
        $transferStock->total_send_qty = $calc->sum('total_send_qty');
        $transferStock->total_received_qty = $calc->sum('total_received_qty');
        $transferStock->total_pending_qty = $calc->sum('total_pending_qty');

        $totalSendQty = $calc->sum('total_send_qty');
        $totalReceivedQty = $calc->sum('total_received_qty');
        $totalPendingQty = $calc->sum('total_pending_qty');

        if ($totalReceivedQty == 0) {

            $transferStock->receive_status = TransferStockReceiveStatus::Pending->value;
        } elseif ($totalReceivedQty > 0 && $totalReceivedQty < $totalSendQty) {

            $transferStock->receive_status = TransferStockReceiveStatus::Partial->value;
        } elseif ($totalReceivedQty == $totalSendQty) {

            $transferStock->receive_status = TransferStockReceiveStatus::Completed->value;
        }

        $transferStock->save();
    }

    public function deleteTransferStock(int $id): array|object
    {
        $deleteTransferStock = $this->singleTransferStock(id: $id, with: ['transferStockProducts']);

        if (!is_null($deleteTransferStock)) {

            if (
                $deleteTransferStock->receive_status == TransferStockReceiveStatus::Partial->value ||
                $deleteTransferStock->receive_status == TransferStockReceiveStatus::Completed->value
            ) {
                return ['pass' => false, 'msg' => 'Transfer stock can not be deleted. Transfer stock receiving status is ' . TransferStockReceiveStatus::tryFrom($deleteTransferStock->receive_status)->name];
            }

            $deleteTransferStock->delete();

            return $deleteTransferStock;
        }
    }

    public function unsetOptionKeyValueOfTransferStockObject(object $transferStock): void
    {
        unset($transferStock->previous_sender_warehouse_id);
        unset($transferStock->previous_receiver_warehouse_id);
        unset($transferStock->previous_sender_branch_id);
        unset($transferStock->previous_receiver_branch_id);
    }

    public function singleTransferStock(?int $id, array $with = null)
    {
        $query = TransferStock::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    private function filter(object $request, object $query): object
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('transfer_stocks.branch_id', null);
            } else {

                $query->where('transfer_stocks.branch_id', $request->branch_id);
            }
        }

        if ($request->receive_status != '') {

            $query->where('transfer_stocks.receive_status', $request->receive_status);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('transfer_stocks.date_ts', $date_range); // Final
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $query->where('transfer_stocks.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
