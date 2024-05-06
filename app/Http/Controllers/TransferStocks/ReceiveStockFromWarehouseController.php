<?php

namespace App\Http\Controllers\TransferStocks;

use App\Enums\BooleanType;
use App\Enums\DayBookVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Http\Controllers\Controller;
use App\Services\Accounts\DayBookService;
use App\Services\Products\ProductLedgerService;
use App\Services\Products\ProductStockService;
use App\Services\Setups\BranchService;
use App\Services\Setups\WarehouseService;
use App\Services\TransferStocks\ReceiveStockFromWarehouseService;
use App\Services\TransferStocks\TransferStockProductService;
use App\Services\TransferStocks\TransferStockService;
use Illuminate\Http\Request;

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
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('transfer_stock_receive_from_warehouse') || config('generalSettings')['subscription']->features['transfer_stocks'] == BooleanType::False->value, 403);

        abort_if(
            config('generalSettings')['subscription']->has_business == BooleanType::False->value &&
            config('generalSettings')['subscription']->current_shop_count == 1 &&
            config('generalSettings')['subscription']->features['warehouse_count'] == 0
        , 403);

        if ($request->ajax()) {

            return $this->receiveStockFromWarehouseService->receivableTransferredStockTable(request: $request);
        }

        $warehouses = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        return view('transfer_stocks.receive_stocks.from_warehouse.index', compact('warehouses'));
    }

    public function create($transferStockId)
    {
        abort_if(!auth()->user()->can('transfer_stock_receive_from_warehouse') || config('generalSettings')['subscription']->features['transfer_stocks'] == BooleanType::False->value, 403);

        abort_if(
            config('generalSettings')['subscription']->has_business == BooleanType::False->value &&
            config('generalSettings')['subscription']->current_shop_count == 1 &&
            config('generalSettings')['subscription']->features['warehouse_count'] == 0
        , 403);

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

    public function receive($transferStockId, Request $request)
    {
        abort_if(!auth()->user()->can('transfer_stock_receive_from_warehouse') || config('generalSettings')['subscription']->features['transfer_stocks'] == BooleanType::False->value, 403);

        abort_if(
            config('generalSettings')['subscription']->has_business == BooleanType::False->value &&
            config('generalSettings')['subscription']->current_shop_count == 1 &&
            config('generalSettings')['subscription']->features['warehouse_count'] == 0
        , 403);

        try {
            DB::beginTransaction();

            $transferStock = $this->transferStockService->singleTransferStock(
                id: $transferStockId,
                with: ['transferStockProducts']
            );

            $transferStock->receive_date = $request->receive_date;
            $transferStock->received_stock_value = $request->received_stock_value;
            $transferStock->receiver_note = $request->receiver_note;
            $transferStock->save();

            $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::ReceivedStock->value, date: $transferStock->receive_date, accountId: null, transId: $transferStock->id, amount: $transferStock->received_stock_value, amountType: 'debit', productId: $transferStock?->transferStockProducts?->first()?->product_id, variantId: $transferStock?->transferStockProducts?->first()?->variant_id);

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

        return response()->json(__('Stock is received successfully.'));
    }
}
