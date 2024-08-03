<?php

namespace App\Services\TransferStocks\MethodContainerServices;

use App\Enums\BooleanType;
use App\Enums\DayBookVoucherType;
use App\Services\Setups\BranchService;
use App\Enums\ProductLedgerVoucherType;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\WarehouseService;
use App\Services\Products\ProductStockService;
use App\Services\Products\ProductLedgerService;
use App\Services\TransferStocks\TransferStockService;
use App\Services\TransferStocks\TransferStockProductService;
use App\Interfaces\TransferStocks\TransferStockControllerMethodContainersInterface;

class TransferStockControllerMethodContainersService implements TransferStockControllerMethodContainersInterface
{
    public function __construct(
        private TransferStockService $transferStockService,
        private TransferStockProductService $transferStockProductService,
        private BranchService $branchService,
        private WarehouseService $warehouseService,
        private ProductStockService $productStockService,
        private DayBookService $dayBookService,
        private ProductLedgerService $productLedgerService,
    ) {
    }

    public function indexMethodContainer(object $request): array|object
    {
        $data = [];
        if ($request->ajax()) {

            return $this->transferStockService->transferStockTable(request: $request);
        }

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return $data;
    }

    public function showMethodContainer(int $id): ?array
    {
        $data = [];
        $data['transferStock'] = $this->transferStockService->singleTransferStock(
            id: $id,
            with: [
                'branch',
                'branch.parentBranch',
                'senderBranch',
                'senderBranch.parentBranch',
                'receiverBranch',
                'receiverBranch.branchCurrency',
                'receiverBranch.parentBranch',
                'receiverWarehouse',
                'transferStockProducts',
                'transferStockProducts.product',
                'transferStockProducts.variant',
                'transferStockProducts.unit',
                'sendBy',
            ]
        );

        return $data;
    }

    public function printMethodContainer(int $id, object $request): ?array
    {
        $data = [];
        $data['transferStock'] = $this->transferStockService->singleTransferStock(
            id: $id,
            with: [
                'branch',
                'branch.parentBranch',
                'senderBranch',
                'senderBranch.parentBranch',
                'receiverBranch',
                'receiverBranch.branchCurrency',
                'receiverBranch.parentBranch',
                'receiverWarehouse',
                'transferStockProducts',
                'transferStockProducts.product',
                'transferStockProducts.variant',
                'transferStockProducts.unit',
                'sendBy',
            ]
        );

        $data['printPageSize'] = $request->print_page_size;

        return $data;
    }

    public function createMethodContainer(): ?array
    {
        $data = [];
        $data['branchName'] = $this->branchService->branchName();

        $data['branches'] = $this->branchService->branches(with: ['parentBranch', 'branchCurrency:id,country,currency,code,symbol,currency_rate'])->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $data['warehouses'] = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        return $data;
    }

    public function storeMethodContainer(object $request, object $codeGenerator): array
    {
        $branchCode = auth()?->user()?->branch?->branch_code;
        $voucherPrefix = 'TS' . auth()?->user()?->branch?->branch_code;

        $addTransferStock = $this->transferStockService->addTransferStock(request: $request, codeGenerator: $codeGenerator, voucherPrefix: $voucherPrefix);

        $variantId = isset($request->variant_ids[0]) && $request->variant_ids[0] != 'noid' ? $request->variant_ids[0] : null;
        $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::TransferStock->value, date: $addTransferStock->date, accountId: null, transId: $addTransferStock->id, amount: $addTransferStock->total_stock_value, amountType: 'credit', productId: isset($request->product_ids[0]) ? $request->product_ids[0] : null, variantId: $variantId);

        foreach ($request->product_ids as $index => $product_id) {

            $addTransferStockProduct = $this->transferStockProductService->addTransferStockProduct(request: $request, transferStockId: $addTransferStock->id, index: $index);

            $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::TransferStock->value, date: $addTransferStock->date, productId: $addTransferStockProduct->product_id, transId: $addTransferStockProduct->id, rate: $addTransferStockProduct->unit_cost_inc_tax, quantityType: 'out', quantity: $addTransferStockProduct->send_qty, subtotal: $addTransferStockProduct->subtotal, variantId: $addTransferStockProduct->variant_id, warehouseId: $addTransferStock->sender_warehouse_id);

            $this->productStockService->adjustMainProductAndVariantStock(productId: $addTransferStockProduct->product_id, variantId: $addTransferStockProduct->variant_id);

            $this->productStockService->adjustBranchAllStock(productId: $addTransferStockProduct->product_id, variantId: $addTransferStockProduct->variant_id, branchId: $addTransferStock->sender_branch_id);

            if ($addTransferStock->sender_warehouse_id) {

                $this->productStockService->adjustWarehouseStock(productId: $addTransferStockProduct->product_id, variantId: $addTransferStockProduct->variant_id, warehouseId: $addTransferStock->sender_warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock(productId: $addTransferStockProduct->product_id, variantId: $addTransferStockProduct->variant_id, branchId: $addTransferStock->sender_branch_id);
            }
        }

        $transferStock = $this->transferStockService->singleTransferStock(
            id: $addTransferStock->id,
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
                'sendBy',
            ]
        );

        $printPageSize = $request->print_page_size;

        return ['transferStock' => $transferStock, 'printPageSize' => $printPageSize];
    }

    public function editMethodContainer(int $id): ?array
    {
        $data = [];
        $transferStock = $this->transferStockService->singleTransferStock(
            id: $id,
            with: [
                'branch',
                'branch.parentBranch',
                'senderBranch',
                'senderBranch.parentBranch',
                'receiverBranch',
                'receiverBranch.parentBranch',
                'receiverWarehouse',
                'transferStockProducts',
                'transferStockProducts.product',
                'transferStockProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
                'transferStockProducts.variant',
                'transferStockProducts.unit:id,name,code_name,base_unit_multiplier',
            ]
        );

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $data['warehouses'] = $this->warehouseService->warehouses()->where('branch_id', $transferStock->branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['selectedBranchWarehouses'] = $this->warehouseService->warehouses()->where('branch_id', $transferStock->receiver_branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['transferStock'] = $transferStock;

        return $data;
    }

    public function updateMethodContainer(int $id, object $request): void
    {
        $updateTransferStock = $this->transferStockService->updateTransferStock(request: $request, id: $id);

        $variantId = isset($request->variant_ids[0]) && $request->variant_ids[0] != 'noid' ? $request->variant_ids[0] : null;
        $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::TransferStock->value, date: $updateTransferStock->date, accountId: null, transId: $updateTransferStock->id, amount: $updateTransferStock->total_stock_value, amountType: 'credit', productId: (isset($request->product_ids[0]) ? $request->product_ids[0] : null), variantId: $variantId);

        foreach ($request->product_ids as $index => $product_id) {

            $updateTransferStockProduct = $this->transferStockProductService->updateTransferStockProduct(request: $request, transferStockId: $updateTransferStock->id, index: $index);

            $this->productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::TransferStock->value, date: $updateTransferStock->date, productId: $updateTransferStockProduct->product_id, transId: $updateTransferStockProduct->id, rate: $updateTransferStockProduct->unit_cost_inc_tax, quantityType: 'out', quantity: $updateTransferStockProduct->send_qty, subtotal: $updateTransferStockProduct->subtotal, variantId: $updateTransferStockProduct->variant_id, branchId: $updateTransferStock->sender_branch_id, warehouseId: $updateTransferStock->sender_warehouse_id, currentWarehouseId: $updateTransferStock->previous_sender_warehouse_id);

            $this->productStockService->adjustMainProductAndVariantStock(productId: $updateTransferStockProduct->product_id, variantId: $updateTransferStockProduct->variant_id);

            $this->productStockService->adjustBranchAllStock(productId: $updateTransferStockProduct->product_id, variantId: $updateTransferStockProduct->variant_id, branchId: $updateTransferStock->sender_branch_id);

            $this->productStockService->adjustBranchStock(productId: $updateTransferStockProduct->product_id, variantId: $updateTransferStockProduct->variant_id, branchId: $updateTransferStock->sender_branch_id);
        }

        $deleteUnusedTransferStockProducts = $this->transferStockProductService->transferStockProducts()->where('transfer_stock_id', $updateTransferStock->id)->where('is_delete_in_update', BooleanType::True->value)->get();

        foreach ($deleteUnusedTransferStockProducts as $deleteUnusedTransferStockProduct) {

            $deleteUnusedTransferStockProduct->delete();

            $this->productStockService->adjustMainProductAndVariantStock(productId: $deleteUnusedTransferStockProduct->product_id, variantId: $deleteUnusedTransferStockProduct->variant_id);

            $this->productStockService->adjustBranchAllStock(productId: $updateTransferStockProduct->product_id, variantId: $deleteUnusedTransferStockProduct->variant_id, branchId: $updateTransferStock->sender_branch_id);

            if ($updateTransferStock->previous_sender_warehouse_id) {

                $this->productStockService->warehouseStock(productId: $deleteUnusedTransferStockProduct->product_id, variantId: $deleteUnusedTransferStockProduct->variant_id, warehouseId: $updateTransferStock->previous_sender_warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock(productId: $deleteUnusedTransferStockProduct->product_id, variantId: $deleteUnusedTransferStockProduct->variant_id, branchId: $updateTransferStock->previous_sender_branch_id);
            }
        }

        $this->transferStockService->unsetOptionKeyValueOfTransferStockObject(transferStock: $updateTransferStock);

        $this->transferStockService->updateTransferStockReceiveStatus(transferStock: $updateTransferStock);
    }

    public function deleteMethodContainer(int $id): ?array
    {
        $deleteTransferStock = $this->transferStockService->deleteTransferStock(id: $id);

        if (isset($deleteTransferStock['pass']) && $deleteTransferStock['pass'] == false) {

            return ['pass' => false, 'msg' => $deleteTransferStock['msg']];
        }

        foreach ($deleteTransferStock->transferStockProducts as $transferStockProduct) {

            if ($deleteTransferStock->sender_warehouse_id) {

                $this->productStockService->adjustWarehouseStock(productId: $transferStockProduct->product_id, variantId: $transferStockProduct->variant_id, warehouseId: $deleteTransferStock->sender_warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock(productId: $transferStockProduct->product_id, variantId: $transferStockProduct->variant_id, branchId: $deleteTransferStock->sender_branch_id);
            }
        }

        return null;
    }
}
