<?php

namespace App\Http\Controllers\TransferStocks;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Enums\IsDeleteInUpdate;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Enums\ProductLedgerVoucherType;
use App\Services\CodeGenerationService;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\WarehouseService;
use App\Services\Products\ProductStockService;
use App\Services\Products\ProductLedgerService;
use App\Services\TransferStocks\TransferStockService;
use App\Services\TransferStocks\TransferStockProductService;

class TransferStockController extends Controller
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
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('transfer_stock_index') || config('generalSettings')['subscription']->features['transfer_stocks'] == BooleanType::False->value, 403);

        abort_if(
            config('generalSettings')['subscription']->has_business == BooleanType::False->value &&
                config('generalSettings')['subscription']->current_shop_count == 1 &&
                config('generalSettings')['subscription']->features['warehouse_count'] == 0,
            403
        );

        if ($request->ajax()) {

            return $this->transferStockService->transferStockTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('transfer_stocks.index', compact('branches'));
    }

    public function show($id)
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

        return view('transfer_stocks.ajax_view.show', compact('transferStock'));
    }

    public function print($id, Request $request)
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

        $printPageSize = $request->print_page_size;
        return view('transfer_stocks.print_templates.print_transfer_stock', compact('transferStock', 'printPageSize'));
    }

    public function create()
    {
        abort_if(!auth()->user()->can('transfer_stock_create') || config('generalSettings')['subscription']->features['transfer_stocks'] == BooleanType::False->value, 403);

        abort_if(
            config('generalSettings')['subscription']->has_business == BooleanType::False->value &&
                config('generalSettings')['subscription']->current_shop_count == 1 &&
                config('generalSettings')['subscription']->features['warehouse_count'] == 0,
            403
        );

        $branchName = $this->branchService->branchName();
        
        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $warehouses = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        return view('transfer_stocks.create', compact('branches', 'warehouses', 'branchName'));
    }

    public function store(Request $request, CodeGenerationService $codeGenerator)
    {
        abort_if(!auth()->user()->can('transfer_stock_create') || config('generalSettings')['subscription']->features['transfer_stocks'] == BooleanType::False->value, 403);

        abort_if(
            config('generalSettings')['subscription']->has_business == BooleanType::False->value &&
                config('generalSettings')['subscription']->current_shop_count == 1 &&
                config('generalSettings')['subscription']->features['warehouse_count'] == 0,
            403
        );

        $this->transferStockService->transferStockValidation(request: $request);

        try {
            DB::beginTransaction();

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

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            $printPageSize = $request->print_page_size;
            return view('transfer_stocks.print_templates.print_transfer_stock', compact('transferStock', 'printPageSize'));
        } else {

            return response()->json(['successMsg' => __('Successfully transfer stock is created.')]);
        }
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('transfer_stock_edit') || config('generalSettings')['subscription']->features['transfer_stocks'] == BooleanType::False->value, 403);

        abort_if(
            config('generalSettings')['subscription']->has_business == BooleanType::True->value &&
                config('generalSettings')['subscription']->current_shop_count == 1 &&
                config('generalSettings')['subscription']->features['warehouse_count'] == 0,
            403
        );

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
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $selectedBranchWarehouses = $this->warehouseService->warehouses()->where('branch_id', $transferStock->receiver_branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        return view('transfer_stocks.edit', compact('transferStock', 'branches', 'warehouses', 'selectedBranchWarehouses'));
    }

    public function update($id, Request $request)
    {
        abort_if(!auth()->user()->can('transfer_stock_edit') || config('generalSettings')['subscription']->features['transfer_stocks'] == BooleanType::False->value, 403);

        abort_if(
            config('generalSettings')['subscription']->has_business == BooleanType::False->value &&
                config('generalSettings')['subscription']->current_shop_count == 1 &&
                config('generalSettings')['subscription']->features['warehouse_count'] == 0,
            403
        );

        $this->transferStockService->transferStockValidation(request: $request);

        try {
            DB::beginTransaction();

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

        return response()->json(__('Transferred Stock is updated successfully.'));
    }

    public function delete($id)
    {
        abort_if(!auth()->user()->can('transfer_stock_delete') || config('generalSettings')['subscription']->features['transfer_stocks'] == BooleanType::False->value, 403);

        abort_if(
            config('generalSettings')['subscription']->has_business == BooleanType::False->value &&
                config('generalSettings')['subscription']->current_shop_count == 1 &&
                config('generalSettings')['subscription']->features['warehouse_count'] == 0,
            403
        );

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

        return response()->json(__('Transferred Stock is deleted successfully.'));
    }
}
