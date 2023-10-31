<?php

namespace App\Http\Controllers\TransferStocks;

use Illuminate\Http\Request;
use App\Enums\IsDeleteInUpdate;
use App\Enums\TransferStockType;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Enums\ProductLedgerVoucherType;
use App\Services\CodeGenerationService;
use App\Services\Accounts\DayBookService;
use App\Services\Products\ProductStockService;
use App\Services\Products\ProductLedgerService;
use App\Services\Setups\WarehouseService;
use App\Services\TransferStocks\TransferStockService;
use App\Services\TransferStocks\TransferStockProductService;

class TransferStockBranchToBranchController extends Controller
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

    function index($type = null, Request $request)
    {
        if ($request->ajax()) {

            return $this->transferStockService->transferStockTable(type: $type, request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('transfer_stocks.branch_to_branch.index', compact('branches'));
    }

    function show($id)
    {
        $transferStock = $this->transferStockService->singleTransferStock(
            id: $id,
            with: [
                'branch',
                'branch.parentBranch',
                'senderBranch',
                'senderBranch.parentBranch',
                'receiverWarehouse',
                'transferStockProducts',
                'transferStockProducts.product',
                'transferStockProducts.variant',
                'transferStockProducts.unit',
                'sendBy',
            ]
        );

        return view('transfer_stocks.branch_to_branch.ajax_view.show', compact('transferStock'));
    }

    public function create()
    {
        $branchName = $this->branchService->branchName();

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $warehouses = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        return view('transfer_stocks.branch_to_branch.create', compact('branches', 'warehouses', 'branchName'));
    }

    function store(Request $request, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'receiver_branch_id' => 'required',
        ], [
            'receiver_branch_id.required' => __("Receiver branch is required."),
        ]);

        try {
            DB::beginTransaction();

            $branchCode = auth()?->user()?->branch?->branch_code;
            $voucherPrefix = 'TSSS' . auth()?->user()?->branch?->branch_code;

            $addTransferStock = $this->transferStockService->addTransferStock(request: $request, transferStockType: TransferStockType::BranchToBranch->value, codeGenerator: $codeGenerator, voucherPrefix: $voucherPrefix);

            $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::TransferStock->value, date: $addTransferStock->date, accountId: null, transId: $addTransferStock->id, amount: $addTransferStock->total_stock_value, amountType: 'credit');

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

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('transfer_stocks.save_and_print_template.print_transfer_stock_branch_to_branch', compact('transferStock'));
        } else {

            return response()->json(['successMsg' => __("Successfully Transfer Stock is created.")]);
        }
    }

    function edit($id)
    {
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

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $warehouses = $this->warehouseService->warehouses()->where('branch_id', $transferStock->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $selectedBranchWarehouses = $this->warehouseService->warehouses()->where('branch_id', $transferStock->receiver_branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        return view('transfer_stocks.branch_to_branch.edit', compact('transferStock', 'branches', 'warehouses', 'selectedBranchWarehouses'));
    }

    function update($id, Request $request)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'receiver_branch_id' => 'required',
        ], [
            'receiver_branch_id.required' => __("Receiver branch is required."),
        ]);

        try {
            DB::beginTransaction();

            $updateTransferStock = $this->transferStockService->updateTransferStock(request: $request, transferStockType: TransferStockType::BranchToBranch->value, id: $id);

            $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::TransferStock->value, date: $updateTransferStock->date, accountId: null, transId: $updateTransferStock->id, amount: $updateTransferStock->total_stock_value, amountType: 'credit');

            foreach ($request->product_ids as $index => $product_id) {

                $updateTransferStockProduct = $this->transferStockProductService->updateTransferStockProduct(request: $request, transferStockId: $updateTransferStock->id, index: $index);

                $this->productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::TransferStock->value, date: $updateTransferStock->date, productId: $updateTransferStockProduct->product_id, transId: $updateTransferStockProduct->id, rate: $updateTransferStockProduct->unit_cost_inc_tax, quantityType: 'out', quantity: $updateTransferStockProduct->send_qty, subtotal: $updateTransferStockProduct->subtotal, variantId: $updateTransferStockProduct->variant_id, branchId: $updateTransferStock->sender_branch_id, warehouseId: $updateTransferStock->sender_warehouse_id, currentWarehouseId: $updateTransferStock->previous_sender_warehouse_id);

                $this->productStockService->adjustMainProductAndVariantStock(productId: $updateTransferStockProduct->product_id, variantId: $updateTransferStockProduct->variant_id);

                $this->productStockService->adjustBranchAllStock(productId: $updateTransferStockProduct->product_id, variantId: $updateTransferStockProduct->variant_id, branchId: $updateTransferStock->sender_branch_id);

                $this->productStockService->adjustBranchStock(productId: $updateTransferStockProduct->product_id, variantId: $updateTransferStockProduct->variant_id, branchId: $updateTransferStock->sender_branch_id);
            }

            $deleteUnusedTransferStockProducts = $this->transferStockProductService->transferStockProducts()->where('transfer_stock_id', $updateTransferStock->id)->where('is_delete_in_update', IsDeleteInUpdate::Yes->value)->get();

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

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Transfer Stock is updated successfully."));
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $deleteTransferStock = $this->transferStockService->deleteTransferStock(id: $id);

            if (isset($deleteTransferStock['pass']) && $deleteTransferStock['pass'] == false) {

                return response()->json(['errorMsg' => $deleteTransferStock['msg']]);
            }

            foreach ($deleteTransferStock->transferStockProducts as $transferStockProduct) {

                if ($deleteTransferStock->sender_warehouse_id) {

                    $this->productStockService->adjustWarehouseStock(productId: $transferStockProduct->product_id, variantId: $transferStockProduct->variant_id, warehouseId: $deleteTransferStock->sender_warehouse_id);
                } else {

                    $this->productStockService->adjustBranchStock(productId: $transferStockProduct->product_id, variantId: $transferStockProduct->variant_id, branchId: $deleteTransferStock->sender_branch_id);
                }
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Transfer Stock is deleted successfully."));
    }
}
