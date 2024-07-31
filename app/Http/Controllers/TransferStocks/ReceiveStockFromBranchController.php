<?php

namespace App\Http\Controllers\TransferStocks;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferStocks\TransferStockReceiveFromBranchRequest;
use App\Http\Requests\TransferStocks\TransferStockReceiveFromBranchIndexRequest;
use App\Http\Requests\TransferStocks\TransferStockReceiveFromBranchCreateRequest;
use App\Interfaces\TransferStocks\ReceiveStockFromBranchControllerMethodContainersInterface;

class ReceiveStockFromBranchController extends Controller
{
    public function index(TransferStockReceiveFromBranchIndexRequest $request, ReceiveStockFromBranchControllerMethodContainersInterface $receiveStockFromBranchControllerMethodContainersInterface)
    {
        $indexMethodContainer = $receiveStockFromBranchControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        return view('transfer_stocks.receive_stocks.from_branch.index');
    }

    public function create($id, TransferStockReceiveFromBranchCreateRequest $request, ReceiveStockFromBranchControllerMethodContainersInterface $receiveStockFromBranchControllerMethodContainersInterface)
    {
        $createMethodContainer = $receiveStockFromBranchControllerMethodContainersInterface->createMethodContainer(id: $id);

        extract($createMethodContainer);

        return view('transfer_stocks.receive_stocks.from_branch.create', compact('transferStock'));
    }

    public function receive($id, TransferStockReceiveFromBranchRequest $request, ReceiveStockFromBranchControllerMethodContainersInterface $receiveStockFromBranchControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $receiveStockFromBranchControllerMethodContainersInterface->receiveMethodContainer(id: $id, request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Stock is received successfully.'));
    }
}
