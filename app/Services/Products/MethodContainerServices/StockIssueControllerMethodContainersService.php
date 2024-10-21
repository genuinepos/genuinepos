<?php

namespace App\Services\Products\MethodContainerServices;

use App\Enums\BooleanType;
use App\Enums\DayBookVoucherType;
use App\Services\Users\UserService;
use App\Services\Branches\BranchService;
use App\Enums\ProductLedgerVoucherType;
use App\Services\Hrm\DepartmentService;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\WarehouseService;
use App\Services\Products\StockChainService;
use App\Services\Products\StockIssueService;
use App\Services\Products\ProductStockService;
use App\Services\Users\UserActivityLogService;
use App\Services\Products\ProductLedgerService;
use App\Services\Products\StockIssueProductService;
use App\Interfaces\Products\StockIssueControllerMethodContainersInterface;

class StockIssueControllerMethodContainersService implements StockIssueControllerMethodContainersInterface
{
    public function __construct(
        private StockIssueService $stockIssueService,
        private StockIssueProductService $stockIssueProductService,
        private UserService $userService,
        private DepartmentService $departmentService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
        private ProductStockService $productStockService,
        private StockChainService $stockChainService,
        private ProductLedgerService $productLedgerService,
        private DayBookService $dayBookService,
        private UserActivityLogService $userActivityLogService,
    ) {}

    public function indexMethodContainer(object $request): array|object
    {
        $data = [];
        if ($request->ajax()) {

            return $this->stockIssueService->stockIssuesTable(request: $request);
        }

        $data['departments'] = $this->departmentService->departments()->select('id', 'name')->get();
        $data['users'] = $this->userService->users()
            ->where('branch_id', auth()->user()->branch_id)
            ->select('id', 'prefix', 'name', 'last_name')->get();

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return $data;
    }

    public function showMethodContainer(int $id): array
    {
        $data = [];
        $data['stockIssue'] = $this->stockIssueService->singleStockIssue(
            id: $id,
            with: [
                'branch',
                'department',
                'reportedBy',
                'createdBy',
                'stockIssuedProducts.product',
                'stockIssuedProducts.variant',
                'stockIssuedProducts.unit',
                'stockIssuedProducts.stockWarehouse',
            ]
        );

        return $data;
    }

    public function printMethodContainer(int $id, object $request): array
    {
        $data = [];
        $data['stockIssue'] = $this->stockIssueService->singleStockIssue(
            id: $id,
            with: [
                'branch',
                'department',
                'reportedBy',
                'createdBy',
                'stockIssuedProducts.product',
                'stockIssuedProducts.variant',
                'stockIssuedProducts.unit',
                'stockIssuedProducts.stockWarehouse',
            ]
        );

        $data['printPageSize'] = $request->print_page_size;

        return $data;
    }

    public function createMethodContainer(): array
    {
        $data = [];

        $data['departments'] = $this->departmentService->departments()->select('id', 'name')->get();
        $data['users'] = $this->userService->users()
            ->where('branch_id', auth()->user()->branch_id)
            ->select('id', 'prefix', 'name', 'last_name')->get();

        $data['warehouses'] = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['branchName'] = $this->branchService->branchName();

        return $data;
    }

    public function storeMethodContainer(object $request, object $codeGenerator): array
    {
        $data = [];

        $restrictions = $this->stockIssueService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');

        $voucherPrefix = $generalSettings['prefix__stock_issue_voucher_prefix'] ? $generalSettings['prefix__stock_issue_voucher_prefix'] : 'STI';

        $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'];

        $addStockIssue = $this->stockIssueService->addStockIssue(request: $request, codeGenerator: $codeGenerator, voucherPrefix: $voucherPrefix);

        $variantId = isset($request->variant_ids[0]) && $request->variant_ids[0] != 'noid' ? $request->variant_ids[0] : null;
        $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::StockIssue->value, date: $addStockIssue->date, accountId: null, productId: isset($request->product_ids[0]) ? $request->product_ids[0] : null, variantId: $variantId, transId: $addStockIssue->id, amount: $addStockIssue->net_total_amount, amountType: 'credit');

        foreach ($request->product_ids as $index => $productId) {

            $addStockIssueProduct = $this->stockIssueProductService->addStockIssueProduct(request: $request, stockIssue: $addStockIssue, index: $index);

            $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::StockIssue->value, date: $request->date, productId: $productId, transId: $addStockIssueProduct->id, rate: $addStockIssueProduct->unit_cost_inc_tax, quantityType: 'out', quantity: $addStockIssueProduct->quantity, subtotal: $addStockIssueProduct->subtotal, variantId: $addStockIssueProduct->variant_id, warehouseId: (isset($addStockIssueProduct->warehouse_id) ? $addStockIssueProduct->warehouse_id : null));
        }

        $stockIssue = $this->stockIssueService->singleStockIssue(
            id: $addStockIssue->id,
            with: [
                'branch',
                'department',
                'reportedBy',
                'createdBy',
                'stockIssuedProducts.product',
                'stockIssuedProducts.variant',
                'stockIssuedProducts.unit',
                'stockIssuedProducts.stockWarehouse',
            ]
        );

        foreach ($request->product_ids as $index => $productId) {

            $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

            $this->productStockService->adjustBranchAllStock(productId: $productId, variantId: $variantId, branchId: $stockIssue->branch_id);

            if (isset($request->warehouse_ids[$index])) {

                $this->productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_ids[$index]);
            } else {

                $this->productStockService->adjustBranchStock(productId: $productId, variantId: $variantId, branchId: $stockIssue->branch_id);
            }
        }

        $this->stockChainService->addStockChain(stockIssue: $stockIssue, stockAccountingMethod: $stockAccountingMethod);

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::StockIssue->value, dataObj: $stockIssue);

        $printPageSize = $request->print_page_size;
        return ['stockIssue' => $stockIssue, 'printPageSize' => $printPageSize];
    }

    public function editMethodContainer(int $id): array
    {
        $data = [];

        $data['departments'] = $this->departmentService->departments()->select('id', 'name')->get();
        $data['users'] = $this->userService->users()->where('branch_id', auth()->user()->branch_id)
            ->select('id', 'prefix', 'name', 'last_name')->get();

        $data['warehouses'] = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['stockIssue'] = $this->stockIssueService->singleStockIssue(
            id: $id,
            with: [
                'branch',
                'stockIssuedProducts.product',
                'stockIssuedProducts.product.unit',
                'stockIssuedProducts.variant',
                'stockIssuedProducts.unit',
                'stockIssuedProducts.stockWarehouse',
            ]
        );

        abort_if(!$data['stockIssue'], 404);

        $data['branchName'] = $this->branchService->branchName();
        return $data;
    }

    public function updateMethodContainer(int $id, object $request): ?array
    {
        $data = [];

        $restrictions = $this->stockIssueService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');

        $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'];

        $updateStockIssue = $this->stockIssueService->updateStockIssue(request: $request, id: $id);

        $variantId = isset($request->variant_ids[0]) && $request->variant_ids[0] != 'noid' ? $request->variant_ids[0] : null;
        $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::StockIssue->value, date: $updateStockIssue->date, accountId: null, productId: isset($request->product_ids[0]) ? $request->product_ids[0] : null, variantId: $variantId, transId: $updateStockIssue->id, amount: $updateStockIssue->net_total_amount, amountType: 'credit');

        foreach ($request->product_ids as $index => $productId) {

            $updateStockIssueProduct = $this->stockIssueProductService->updateStockIssueProduct(request: $request, stockIssue: $updateStockIssue, index: $index);

            $this->productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::StockIssue->value, date: $request->date, productId: $productId, transId: $updateStockIssueProduct->id, rate: $updateStockIssueProduct->unit_cost_inc_tax, quantityType: 'out', quantity: $updateStockIssueProduct->quantity, subtotal: $updateStockIssueProduct->subtotal, variantId: $updateStockIssueProduct->variant_id, warehouseId: $updateStockIssueProduct->warehouse_id, currentWarehouseId: $updateStockIssueProduct->current_warehouse_id, branchId: $updateStockIssue->branch_id);
        }

        $deletedUnusedStockIssuedProducts = $this->stockIssueProductService->stockIssueProducts(with: ['stockChains', 'stockChains.purchaseProduct'])->where('stock_issue_id', $updateStockIssue->id)->where('is_delete_in_update', BooleanType::True->value)->get();

        if (count($deletedUnusedStockIssuedProducts) > 0) {

            foreach ($deletedUnusedStockIssuedProducts as $deletedUnusedStockIssuedProduct) {

                $deletedUnusedStockIssuedProduct->delete();

                // Adjust deleted product stock
                $this->productStockService->adjustMainProductAndVariantStock($deletedUnusedStockIssuedProduct->product_id, $deletedUnusedStockIssuedProduct->variant_id);

                $this->productStockService->adjustBranchAllStock(productId: $deletedUnusedStockIssuedProduct->product_id, variantId: $deletedUnusedStockIssuedProduct->variant_id, branchId: $updateStockIssue->branch_id);

                if (isset($deletedUnusedStockIssuedProduct->warehouse_id)) {

                    $this->productStockService->adjustWarehouseStock(productId: $deletedUnusedStockIssuedProduct->product_id, variantId: $deletedUnusedStockIssuedProduct->variant_id, warehouseId: $deletedUnusedStockIssuedProduct->warehouse_id);
                } else {

                    $this->productStockService->adjustBranchStock(productId: $deletedUnusedStockIssuedProduct->product_id, variantId: $deletedUnusedStockIssuedProduct->variant_id, branchId: $updateStockIssue->branch_id);
                }

                foreach ($deletedUnusedStockIssuedProduct->stockChains as $stockChain) {

                    $this->stockChainService->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                }
            }
        }

        $stockIssue = $this->stockIssueService->singleStockIssue(id: $updateStockIssue->id, with: [
            'stockIssuedProducts',
            'stockIssuedProducts.product',
            'stockIssuedProducts.stockChains',
            'stockIssuedProducts.stockChains.purchaseProduct',
        ]);

        $stockIssuedProducts = $stockIssue->stockIssuedProducts;
        foreach ($stockIssuedProducts as $issuedProduct) {

            $this->productStockService->adjustMainProductAndVariantStock($issuedProduct->product_id, $issuedProduct->variant_id);

            $this->productStockService->adjustBranchAllStock(productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, branchId: $stockIssue->branch_id);

            if ($issuedProduct->warehouse_id) {

                $this->productStockService->adjustWarehouseStock(productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, warehouseId: $issuedProduct->warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock(productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, branchId: $stockIssue->branch_id);
            }
        }

        $this->stockChainService->updateStockChain(stockIssue: $stockIssue, stockAccountingMethod: $stockAccountingMethod);

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::StockIssue->value, dataObj: $stockIssue);

        return null;
    }

    public function deleteMethodContainer(int $id): void
    {
        $deleteStockIssue = $this->stockIssueService->deleteStockIssue(id: $id);

        foreach ($deleteStockIssue->stockIssuedProducts as $issuedProduct) {

            $this->productStockService->adjustMainProductAndVariantStock($issuedProduct->product_id, $issuedProduct->variant_id);

            $this->productStockService->adjustBranchAllStock(productId: $issuedProduct->product_id, variantId: $issuedProduct->variant_id, branchId: $issuedProduct->branch_id);

            if ($issuedProduct->warehouse_id) {

                $this->productStockService->adjustWarehouseStock($issuedProduct->product_id, $issuedProduct->variant_id, $issuedProduct->warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock($issuedProduct->product_id, $issuedProduct->variant_id, $issuedProduct->branch_id);
            }

            foreach ($issuedProduct->stockChains as $stockChain) {

                if ($stockChain->purchaseProduct) {

                    $this->stockChainService->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                }
            }
        }
    }
}
