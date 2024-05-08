<?php

namespace App\Http\Controllers\TransferStocks;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferStocks\TransferStockReceiveFromBranchRequest;
use App\Interfaces\TransferStocks\ReceiveStockFromBranchControllerMethodContainersInterface;

class ReceiveStockFromBranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request, ReceiveStockFromBranchControllerMethodContainersInterface $receiveStockFromBranchControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('transfer_stock_receive_from_warehouse') || config('generalSettings')['subscription']->features['transfer_stocks'] == BooleanType::False->value, 403);

        abort_if(
            config('generalSettings')['subscription']->has_business == BooleanType::False->value &&
                config('generalSettings')['subscription']->current_shop_count == 1 &&
                config('generalSettings')['subscription']->features['warehouse_count'] == 0,
            403
        );

        $indexMethodContainer = $receiveStockFromBranchControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;;
        }

        return view('transfer_stocks.receive_stocks.from_branch.index');
    }

    public function create($id, ReceiveStockFromBranchControllerMethodContainersInterface $receiveStockFromBranchControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('transfer_stock_receive_from_warehouse') || config('generalSettings')['subscription']->features['transfer_stocks'] == BooleanType::False->value, 403);

        abort_if(
            config('generalSettings')['subscription']->has_business == BooleanType::False->value &&
                config('generalSettings')['subscription']->current_shop_count == 1 &&
                config('generalSettings')['subscription']->features['warehouse_count'] == 0,
            403
        );

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
