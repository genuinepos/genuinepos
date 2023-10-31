<?php

namespace App\Http\Controllers\TransferStocks;

use Illuminate\Http\Request;
use App\Enums\DayBookVoucherType;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Enums\ProductLedgerVoucherType;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\WarehouseService;
use App\Services\Products\ProductStockService;
use App\Services\Products\ProductLedgerService;
use App\Services\TransferStocks\TransferStockService;
use App\Services\TransferStocks\TransferStockProductService;
use App\Services\TransferStocks\ReceiveStockFromWarehouseService;

class ReceiveStockFromWarehouseController extends Controller
{
    public function __construct(
        private ReceiveStockFromWarehouseService $receiveStockFromWarehouseService,
        private TransferStockService $transferStockService,
        private TransferStockProductService $transferStockProductService,
        private BranchService $branchService,
        private WarehouseService $warehouseService,
        private ProductStockService $productStockService,
        private DayBookService $dayBookService,
        private ProductLedgerService $productLedgerService,
    ) {
    }

    function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->receiveStockFromWarehouseService->receivableTransferredStockTable(request: $request);
        }

        $warehouses = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        return view('transfer_stocks.receive_stocks.from_warehouse.index', compact('warehouses'));
    }

    function create($transferStockId)
    {
        $transferStock = $this->transferStockService->singleTransferStock(
            id: $transferStockId,
            with: [
                'branch',
                'branch.parentBranch',
                'senderBranch',
                'senderBranch.parentBranch',
                'senderWarehouse',
                'receiverBranch',
                'receiverBranch.parentBranch',
                'receiverWarehouse',
                'transferStockProducts',
                'transferStockProducts.product',
                'transferStockProducts.variant',
                'transferStockProducts.unit',
            ]
        );

        return view('transfer_stocks.receive_stocks.from_warehouse.create', compact('transferStock'));
    }

    function receive($transferStockId, Request $request)
    {
        try {
            DB::beginTransaction();

            $transferStock = $transferStock = $this->transferStockService->singleTransferStock(
                id: $transferStockId,
                with: ['transferStockProducts']
            );

            $transferStock->receive_date = $request->receive_date;
            $transferStock->received_stock_value = $request->received_stock_value;
            $transferStock->receiver_note = $request->receiver_note;
            $transferStock->save();

            $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::ReceivedStock->value, date: $transferStock->receive_date, accountId: null, transId: $transferStock->id, amount: $transferStock->received_stock_value, amountType: 'debit');

            foreach ($request->transfer_stock_product_ids as $index => $transfer_stock_product_id) {

                $updateTransferStockProductQty = $this->transferStockProductService->updateTransferStockProductQty(request: $request, transferStockProductId: $transfer_stock_product_id, index: $index);

                $this->productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::ReceiveStock->value, date: $transferStock->receive_date, productId: $updateTransferStockProductQty->product_id, transId: $updateTransferStockProductQty->id, rate: $updateTransferStockProductQty->unit_cost_inc_tax, quantityType: 'in', quantity: $updateTransferStockProductQty->received_qty, subtotal: $updateTransferStockProductQty->received_subtotal, variantId: $updateTransferStockProductQty->variant_id, branchId: $transferStock->receiver_branch_id, warehouseId: $transferStock->receiver_warehouse_id);

                $this->productStockService->adjustMainProductAndVariantStock(productId: $updateTransferStockProductQty->product_id, variantId: $updateTransferStockProductQty->variant_id);

                $this->productStockService->adjustBranchAllStock(productId: $updateTransferStockProductQty->product_id, variantId: $updateTransferStockProductQty->variant_id, branchId: $transferStock->sender_branch_id);

                $this->productStockService->adjustWarehouseStock(productId: $updateTransferStockProductQty->product_id, variantId: $updateTransferStockProductQty->variant_id, warehouseId: $transferStock->receiver_warehouse_id);
            }

            $this->transferStockService->updateTransferStockReceiveStatus(transferStock: $transferStock);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Stock is received successfully."));
    }
}
