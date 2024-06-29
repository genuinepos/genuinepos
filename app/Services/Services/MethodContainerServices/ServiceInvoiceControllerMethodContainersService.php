<?php

namespace App\Services\Services\MethodContainerServices;

use App\Services\Sales\SaleService;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Products\StockChainService;
use App\Services\Products\ProductStockService;
use App\Services\Users\UserActivityLogService;
use App\Services\Services\ServiceInvoiceService;
use App\Interfaces\Services\ServiceInvoiceControllerMethodContainersInterface;

class ServiceInvoiceControllerMethodContainersService implements ServiceInvoiceControllerMethodContainersInterface
{
    public function __construct(
        private ServiceInvoiceService $serviceInvoiceService,
        private SaleService $saleService,
        private AccountService $accountService,
        private BranchService $branchService,
        private ProductStockService $productStockService,
        private StockChainService $stockChainService,
        private UserActivityLogService $userActivityLogService,
    ) {
    }

    public function indexMethodContainer(object $request): array|object
    {
        $data = [];
        if ($request->ajax()) {

            return $this->serviceInvoiceService->serviceInvoicesListTable(request: $request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $data['customerAccounts']  = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $data['ownBranchIdOrParentBranchId'] = $ownBranchIdOrParentBranchId;

        return $data;
    }

    public function deleteMethodContainer(int $id): array|object
    {
        $deleteSale = $this->saleService->deleteSale($id);

        if (isset($deleteSale['pass']) && $deleteSale['pass'] == false) {

            return ['pass' => false, 'msg' => $deleteSale['msg']];

            return response()->json(['errorMsg' => $deleteSale['msg']]);
        }

        foreach ($deleteSale->saleProducts as $saleProduct) {

            $this->productStockService->adjustMainProductAndVariantStock($saleProduct->product_id, $saleProduct->variant_id);

            $this->productStockService->adjustBranchAllStock(productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, branchId: $saleProduct->branch_id);

            if ($saleProduct->warehouse_id) {

                $this->productStockService->adjustWarehouseStock($saleProduct->product_id, $saleProduct->variant_id, $saleProduct->warehouse_id);
            } else {

                $this->productStockService->adjustBranchStock($saleProduct->product_id, $saleProduct->variant_id, $saleProduct->branch_id);
            }

            foreach ($saleProduct->stockChains as $stockChain) {

                if ($stockChain->purchaseProduct) {

                    $this->stockChainService->adjustPurchaseProductOutLeftQty($stockChain->purchaseProduct);
                }
            }
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::Sales->value, dataObj: $deleteSale);

        return $deleteSale;
    }
}
