<?php

namespace App\Http\Controllers\TransferStocks;

use App\Enums\DayBookVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Http\Controllers\Controller;
use App\Services\Accounts\DayBookService;
use App\Services\Products\ProductLedgerService;
use App\Services\Products\ProductStockService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Setups\BranchService;
use App\Services\TransferStocks\ReceiveStockFromBranchService;
use App\Services\TransferStocks\TransferStockProductService;
use App\Services\TransferStocks\TransferStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiveStockFromBranchController extends Controller
{
    public function __construct(
        private ReceiveStockFromBranchService $receiveStockFromBranchService,
        private TransferStockService $transferStockService,
        private TransferStockProductService $transferStockProductService,
        private BranchService $branchService,
        private ProductStockService $productStockService,
        private DayBookService $dayBookService,
        private ProductLedgerService $productLedgerService,
        private PurchaseProductService $purchaseProductService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('transfer_stock_receive_from_warehouse') || config('generalSettings')['subscription']->features['transfer_stocks'] == 0, 403);

        abort_if(
            config('generalSettings')['subscription']->has_business == 0 &&
            config('generalSettings')['subscription']->current_shop_count == 1 &&
            config('generalSettings')['subscription']->features['warehouse_count'] == 0
        , 403);

        if ($request->ajax()) {

            return $this->receiveStockFromBranchService->receivableTransferredStockTable(request: $request);
        }

        return view('transfer_stocks.receive_stocks.from_branch.index');
    }

    public function create($transferStockId)
    {
        abort_if(!auth()->user()->can('transfer_stock_receive_from_warehouse') || config('generalSettings')['subscription']->features['transfer_stocks'] == 0, 403);

        abort_if(
            config('generalSettings')['subscription']->has_business == 0 &&
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
                'transferStockProducts',
                'transferStockProducts.product',
                'transferStockProducts.variant',
                'transferStockProducts.unit',
            ]
        );

        return view('transfer_stocks.receive_stocks.from_branch.create', compact('transferStock'));
    }

    public function receive($transferStockId, Request $request)
    {
        abort_if(!auth()->user()->can('transfer_stock_receive_from_warehouse') || config('generalSettings')['subscription']->features['transfer_stocks'] == 0, 403);

        abort_if(
            config('generalSettings')['subscription']->has_business == 0 &&
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

            $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::ReceivedStock->value, date: $transferStock->receive_date, accountId: null, transId: $transferStock->id, amount: $transferStock->received_stock_value, amountType: 'debit');

            foreach ($request->transfer_stock_product_ids as $index => $transfer_stock_product_id) {

                $updateTransferStockProductQty = $this->transferStockProductService->updateTransferStockProductQty(request: $request, transferStockProductId: $transfer_stock_product_id, index: $index);

                if ($transferStock->sender_branch_id != $transferStock->receiver_branch_id) {

                    $this->purchaseProductService->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(transColName: 'transfer_stock_product_id', transId: $updateTransferStockProductQty->id, branchId: $transferStock->receiver_branch_id, productId: $updateTransferStockProductQty->product_id, variantId: $updateTransferStockProductQty->variant_id, quantity: $updateTransferStockProductQty->received_qty, unitCostIncTax: $updateTransferStockProductQty->unit_cost_inc_tax, sellingPrice: 0, subTotal: $updateTransferStockProductQty->subtotal, createdAt: date('Y-m-d H:i:s'));
                }

                $this->productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::ReceiveStock->value, date: $transferStock->receive_date, productId: $updateTransferStockProductQty->product_id, transId: $updateTransferStockProductQty->id, rate: $updateTransferStockProductQty->unit_cost_inc_tax, quantityType: 'in', quantity: $updateTransferStockProductQty->received_qty, subtotal: $updateTransferStockProductQty->received_subtotal, variantId: $updateTransferStockProductQty->variant_id, branchId: $transferStock->receiver_branch_id);

                $this->productStockService->adjustMainProductAndVariantStock(productId: $updateTransferStockProductQty->product_id, variantId: $updateTransferStockProductQty->variant_id);

                $this->productStockService->adjustBranchAllStock(productId: $updateTransferStockProductQty->product_id, variantId: $updateTransferStockProductQty->variant_id, branchId: $transferStock->sender_branch_id);

                $this->productStockService->adjustBranchStock(productId: $updateTransferStockProductQty->product_id, variantId: $updateTransferStockProductQty->variant_id, branchId: $transferStock->receiver_branch_id);
            }

            $this->transferStockService->updateTransferStockReceiveStatus(transferStock: $transferStock);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Stock is received successfully.'));
    }
}
