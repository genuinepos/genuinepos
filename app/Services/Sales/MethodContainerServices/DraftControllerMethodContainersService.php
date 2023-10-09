<?php

namespace App\Services\Sales\MethodContainerServices;

use App\Enums\SaleStatus;
use App\Enums\SaleScreenType;
use App\Enums\DayBookVoucherType;
use App\Enums\AccountingVoucherType;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Interfaces\Sales\DraftControllerMethodContainersInterface;

class DraftControllerMethodContainersService implements DraftControllerMethodContainersInterface
{
    public function showMethodContainer(
        int $id,
        object $quotationService,
        object $saleProductService,
    ): array {

        $data = [];
        $draft = $quotationService->singleDraft(id: $id, with: [
            'customer:id,name,phone,address',
            'createdBy:id,prefix,name,last_name',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.branch:id,name,branch_code,area_name,parent_branch_id',
            'saleProducts.branch.parentBranch:id,name,branch_code,area_name',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.unit.baseUnit:id,base_unit_id,code_name',
        ]);

        $data['customerCopySaleProducts'] = $saleProductService->customerCopySaleProducts(saleId: $draft->id);
        $data['draft'] = $draft;

        return $data;
    }

    function editMethodContainer(
        int $id,
        object $draftService,
        object $accountService,
        object $accountFilterService,
        object $priceGroupService,
        object $managePriceGroupService,
    ): array {

        $draft = $draftService->singleDraft(id: $id, with: [
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.unit:id,name,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.unit.baseUnit:id,name,code_name,base_unit_id',
        ]);

        $generalSettings = config('generalSettings');
        $ownBranchIdOrParentBranchId = $draft?->branch?->parent_branch_id ? $draft?->branch?->parent_branch_id : $draft->branch_id;

        $accounts = $accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $draft->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

        $data['accounts'] = $accountFilterService->filterCashBankAccounts($accounts);

        $data['saleAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', $draft->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['taxAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', $draft->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['customerAccounts'] = $accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $data['priceGroups'] = $priceGroupService->priceGroups()->get(['id', 'name']);
        $data['priceGroupProducts'] = $managePriceGroupService->priceGroupProducts();
        $data['draft'] = $draft;

        return $data;
    }

    public function updateMethodContainer(
        int $id,
        object $request,
        object $draftService,
        object $draftProductService,
    ): ?array {

        $draft = $draftService->singleDraft(id: $id, with: ['saleProducts']);

        $updateDraft = $draftService->updateDraft(request: $request, updateDraft: $draft);

        $updateDraftProducts = $draftProductService->updateDraftProducts(request: $request, draft: $updateDraft);

        $draft = $draftService->singleDraft(id: $id, with: ['saleProducts']);

        $deletedUnusedDraftProducts = $draft->saleProducts()->where('is_delete_in_update', 1)->get();

        if (count($deletedUnusedDraftProducts) > 0) {

            foreach ($deletedUnusedDraftProducts as $deletedUnusedDraftProduct) {

                $deletedUnusedDraftProduct->delete();
            }
        }

        return null;
    }
}
