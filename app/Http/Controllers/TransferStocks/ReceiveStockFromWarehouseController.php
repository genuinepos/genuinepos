<?php

namespace App\Http\Controllers\TransferStocks;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferStocks\TransferStockReceiveFromWarehouseRequest;
use App\Http\Requests\TransferStocks\TransferStockReceiveFromWarehouseIndexRequest;
use App\Http\Requests\TransferStocks\TransferStockReceiveFromWarehouseCreateRequest;
use App\Interfaces\TransferStocks\ReceiveStockFromWarehouseControllerMethodContainersInterface;

class ReceiveStockFromWarehouseController extends Controller
{
    public function index(TransferStockReceiveFromWarehouseIndexRequest $request, ReceiveStockFromWarehouseControllerMethodContainersInterface $receiveStockFromWarehouseControllerMethodContainersInterface)
    {
        $indexMethodContainer = $receiveStockFromWarehouseControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('transfer_stocks.receive_stocks.from_warehouse.index', compact('warehouses'));
    }

    public function create($id, TransferStockReceiveFromWarehouseCreateRequest $request, ReceiveStockFromWarehouseControllerMethodContainersInterface $receiveStockFromWarehouseControllerMethodContainersInterface)
    {
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
