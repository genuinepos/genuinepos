<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Sales\SaleProductService;
use App\Services\Accounts\AccountFilterService;
use App\Http\Requests\Sales\SoldProductIndexRequest;

class SoldProductController extends Controller
{
    public function __construct(
        private SaleProductService $saleProductService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
    ) {}

    public function index(SoldProductIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->saleProductService->soldProductListTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.add_sale.sold_products.index', compact('branches', 'customerAccounts'));
    }

    public function soldProductsForSalesReturn($saleId)
    {
        $saleProducts = $this->saleProductService->saleProducts(with: [
            'sale:id',
            'product:id,name,product_code,unit_id',
            'product.unit:id,name,code_name',
            'product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'variant:id,variant_name,variant_code',
            'unit:id,name,base_unit_id,base_unit_multiplier',
            'unit.baseUnit:id,name,base_unit_id',
        ])->where('sale_id', $saleId)->get();

        $itemUnitsArray = [];
        foreach ($saleProducts as $saleProduct) {

            if (isset($saleProduct->product_id)) {

                $itemUnitsArray[$saleProduct->product_id][] = [
                    'unit_id' => $saleProduct->product->unit->id,
                    'unit_name' => $saleProduct->product->unit->name,
                    'unit_code_name' => $saleProduct->product->unit->code_name,
                    'base_unit_multiplier' => 1,
                    'multiplier_details' => '',
                    'is_base_unit' => 1,
                ];
            }

            if (count($saleProduct?->product?->unit?->childUnits) > 0) {

                foreach ($saleProduct?->product?->unit?->childUnits as $unit) {

                    $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $saleProduct?->product?->unit?->name . ')';

                    array_push($itemUnitsArray[$saleProduct->product_id], [
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->name,
                        'unit_code_name' => $unit->code_name,
                        'base_unit_multiplier' => $unit->base_unit_multiplier,
                        'multiplier_details' => $multiplierDetails,
                        'is_base_unit' => 0,
                    ]);
                }
            }
        }

        $view = view('sales.add_sale.sold_products.ajax_view.sold_products_for_sales_return', ['saleProducts' => $saleProducts])->render();

        return [
            'view' => $view,
            'units' => json_encode($itemUnitsArray),
        ];
    }

    public function saleProductsForSalesOrderToInvoice($salesOrderId)
    {
        $saleProducts = $this->saleProductService->saleProducts(with: [
            'sale:id',
            'product',
            'product.unit:id,name,code_name',
            'product.brand:id,name',
            'product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'variant:id,variant_name,variant_code',
            'unit:id,name,base_unit_id,base_unit_multiplier',
            'unit.baseUnit:id,name,base_unit_id',
        ])->where('sale_id', $salesOrderId)->get();

        $itemUnitsArray = [];
        foreach ($saleProducts as $saleProduct) {

            if (isset($saleProduct->product_id)) {

                $itemUnitsArray[$saleProduct->product_id][] = [
                    'unit_id' => $saleProduct->product->unit->id,
                    'unit_name' => $saleProduct->product->unit->name,
                    'unit_code_name' => $saleProduct->product->unit->code_name,
                    'base_unit_multiplier' => 1,
                    'multiplier_details' => '',
                    'is_base_unit' => 1,
                ];
            }

            if (count($saleProduct?->product?->unit?->childUnits) > 0) {

                foreach ($saleProduct?->product?->unit?->childUnits as $unit) {

                    $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $saleProduct?->product?->unit?->name . ')';

                    array_push($itemUnitsArray[$saleProduct->product_id], [
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->name,
                        'unit_code_name' => $unit->code_name,
                        'base_unit_multiplier' => $unit->base_unit_multiplier,
                        'multiplier_details' => $multiplierDetails,
                        'is_base_unit' => 0,
                    ]);
                }
            }
        }

        $view = view('sales.add_sale.sold_products.ajax_view.sales_ordered_products_for_sale_order_to_invoice', ['saleProducts' => $saleProducts])->render();

        return [
            'view' => $view,
            'units' => json_encode($itemUnitsArray),
        ];
    }
}
