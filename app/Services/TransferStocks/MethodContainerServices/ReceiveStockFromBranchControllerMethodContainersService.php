<?php

namespace App\Services\TransferStocks\MethodContainerServices;

use App\Enums\DayBookVoucherType;
use App\Services\Branches\BranchService;
use App\Enums\ProductLedgerVoucherType;
use App\Services\Accounts\DayBookService;
use App\Services\Products\ProductStockService;
use App\Services\Products\ProductLedgerService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Products\ProductAccessBranchService;
use App\Services\TransferStocks\TransferStockService;
use App\Services\TransferStocks\TransferStockProductService;
use App\Services\TransferStocks\ReceiveStockFromBranchService;
use App\Interfaces\TransferStocks\ReceiveStockFromBranchControllerMethodContainersInterface;

class ReceiveStockFromBranchControllerMethodContainersService implements ReceiveStockFromBranchControllerMethodContainersInterface
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
        private ProductAccessBranchService $productAccessBranchService,
    ) {}

    public function indexMethodContainer(object $request): ?object
    {
        if ($request->ajax()) {

            return $this->receiveStockFromBranchService->receivableTransferredStockTable(request: $request);
        }

        return null;
    }

    public function createMethodContainer(int $id): array
    {
        $data = [];
        $data['transferStock'] = $this->transferStockService->singleTransferStock(
            id: $id,
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

        return $data;
    }

    public function receiveMethodContainer(int $id, object $request): void
    {
        $transferStock = $this->transferStockService->singleTransferStock(id: $id, with: ['transferStockProducts', 'receiverBranch']);

        $transferStock->receive_date = $request->receive_date;
        $transferStock->received_stock_value = $request->received_stock_value;
        $transferStock->receiver_note = $request->receiver_note;
        $transferStock->save();

        $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::ReceivedStock->value, date: $transferStock->receive_date, accountId: null, transId: $transferStock->id, amount: $transferStock->received_stock_value, amountType: 'debit', productId: $transferStock?->transferStockProducts?->first()?->product_id, variantId: $transferStock?->transferStockProducts?->first()?->variant_id);

        foreach ($request->transfer_stock_product_ids as $index => $transfer_stock_product_id) {

            $updateTransferStockProductQty = $this->transferStockProductService->updateTransferStockProductQty(request: $request, transferStockProductId: $transfer_stock_product_id, index: $index);

            if ($transferStock->sender_branch_id != $transferStock->receiver_branch_id) {

                $this->purchaseProductService->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(transColName: 'transfer_stock_product_id', transId: $updateTransferStockProductQty->id, branchId: $transferStock->receiver_branch_id, productId: $updateTransferStockProductQty->product_id, variantId: $updateTransferStockProductQty->variant_id, quantity: $updateTransferStockProductQty->received_qty, unitCostIncTax: $updateTransferStockProductQty->unit_cost_inc_tax, sellingPrice: 0, subTotal: $updateTransferStockProductQty->subtotal, createdAt: date('Y-m-d H:i:s'));
            }

            $this->productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::ReceiveStock->value, date: $transferStock->receive_date, productId: $updateTransferStockProductQty->product_id, transId: $updateTransferStockProductQty->id, rate: $updateTransferStockProductQty->unit_cost_inc_tax, quantityType: 'in', quantity: $updateTransferStockProductQty->received_qty, subtotal: $updateTransferStockProductQty->received_subtotal, variantId: $updateTransferStockProductQty->variant_id, branchId: $transferStock->receiver_branch_id);

            $this->productStockService->adjustMainProductAndVariantStock(productId: $updateTransferStockProductQty->product_id, variantId: $updateTransferStockProductQty->variant_id);

            $this->productStockService->adjustBranchAllStock(productId: $updateTransferStockProductQty->product_id, variantId: $updateTransferStockProductQty->variant_id, branchId: $transferStock->sender_branch_id);

            $this->productStockService->adjustBranchStock(productId: $updateTransferStockProductQty->product_id, variantId: $updateTransferStockProductQty->variant_id, branchId: $transferStock->receiver_branch_id);

            $receiverOwnBranchIdOrParentBranchId = $transferStock?->receiverBranch?->parent_branch_id ? $transferStock?->receiverBranch?->branch?->parent_branch_id : $transferStock?->receiverBranch?->id;

            $this->productAccessBranchService->addSingleProductBranchStock(productId: $updateTransferStockProductQty->product_id, branchId: $receiverOwnBranchIdOrParentBranchId);
        }

        $this->transferStockService->updateTransferStockReceiveStatus(transferStock: $transferStock);
    }
}
