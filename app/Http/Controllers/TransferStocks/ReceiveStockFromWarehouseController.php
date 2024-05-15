<?php

namespace App\Http\Controllers\TransferStocks;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferStocks\TransferStockReceiveFromWarehouseRequest;
use App\Interfaces\TransferStocks\ReceiveStockFromWarehouseControllerMethodContainersInterface;

class ReceiveStockFromWarehouseController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request, ReceiveStockFromWarehouseControllerMethodContainersInterface $receiveStockFromWarehouseControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('transfer_stock_receive_from_warehouse'), 403);

        $indexMethodContainer = $receiveStockFromWarehouseControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;;
        }

        extract($indexMethodContainer);

        return view('transfer_stocks.receive_stocks.from_warehouse.index', compact('warehouses'));
    }

    public function create($id, ReceiveStockFromWarehouseControllerMethodContainersInterface $receiveStockFromWarehouseControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('transfer_stock_receive_from_warehouse') , 403);

        $createMethodContainer = $receiveStockFromWarehouseControllerMethodContainersInterface->createMethodContainer(id: $id);

        extract($createMethodContainer);

        return view('transfer_stocks.receive_stocks.from_warehouse.create', compact('transferStock'));
    }

    public function receive($id, TransferStockReceiveFromWarehouseRequest $request, ReceiveStockFromWarehouseControllerMethodContainersInterface $receiveStockFromWarehouseControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $receiveStockFromWarehouseControllerMethodContainersInterface->receiveMethodContainer(id: $id, request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Stock is received successfully.'));
    }
}
