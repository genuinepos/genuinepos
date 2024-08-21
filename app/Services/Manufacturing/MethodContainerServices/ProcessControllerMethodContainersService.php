<?php

namespace App\Services\Manufacturing\MethodContainerServices;

use App\Services\Accounts\AccountService;
use App\Services\Products\ProductService;
use App\Services\Manufacturing\ProcessService;
use App\Services\Manufacturing\ProcessIngredientService;
use App\Interfaces\Manufacturing\ProcessControllerMethodContainersInterface;

class ProcessControllerMethodContainersService implements ProcessControllerMethodContainersInterface
{
    public function __construct(
        private ProcessService $processService,
        private ProcessIngredientService $processIngredientService,
        private AccountService $accountService,
        private ProductService $productService,
    ) {
    }

    public function indexMethodContainer(object $request): ?object
    {
        if ($request->ajax()) {

            return $this->processService->processTable($request);
        }

        return null;
    }

    public function showMethodContainer(int $id): array
    {
        $data = [];
        $data['process'] = $this->processService->process(with: [
            'branch',
            'branch.parentBranch',
            'product',
            'variant',
            'unit',
            'ingredients',
            'ingredients.product',
            'ingredients.unit',
            'ingredients.variant',
        ])->where('id', $id)->first();

        return $data;
    }

    public function printMethodContainer(int $id, object $request): array
    {
        $data = [];
        $data['process'] = $this->processService->process(with: [
            'branch',
            'branch.parentBranch',
            'product',
            'variant',
            'unit',
            'ingredients',
            'ingredients.product',
            'ingredients.unit',
            'ingredients.variant',
        ])->where('id', $id)->first();

        $data['printPageSize'] = $request->print_page_size;

        return $data;
    }

    public function selectProductModalMethodContainer(): array
    {
        $data = [];
        $data['products'] = $this->productService->branchProducts(branchId: auth()->user()->branch_id, withVariant: true);
        return $data;
    }

    public function createMethodContainer(object $request): mixed
    {
        $data = [];
        $productAndVariantId = explode('-', $request->product_id);
        $productId = $productAndVariantId[0];
        $variantId = $productAndVariantId[1] != 'noid' ? $productAndVariantId[1] : null;

        $checkSameItemProcess = $this->processService->process()->where('product_id', $productId)->where('variant_id', $variantId)->first();

        if ($checkSameItemProcess) {

            return redirect()->route('manufacturing.process.edit', $checkSameItemProcess->id);
        }

        $data['product'] = $this->processService->getProcessableProductForCreate(request: $request);

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        return $data;
    }

    public function storeMethodContainer(object $request): void
    {
        $addProcess = $this->processService->addProcess(request: $request);

        if (isset($request->product_ids)) {

            $this->processIngredientService->addProcessIngredients(request: $request, processId: $addProcess->id);
        }
    }

    public function editMethodContainer(int $id): array
    {
        $data = [];
        $data['process'] = $this->processService->process(with: [
            'product',
            'variant',
            'unit',
            'ingredients',
            'ingredients.product',
            'ingredients.variant',
            'ingredients.unit',
            'ingredients.unit.baseUnit:id,name,code_name,base_unit_id',
        ])->where('id', $id)->first();

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        return $data;
    }

    public function updateMethodContainer(int $id, object $request): void
    {
        $updateProcess = $this->processService->updateProcess(request: $request, id: $id);
        $this->processIngredientService->updateProcessIngredients(request: $request, process: $updateProcess);
    }

    public function deleteMethodContainer(int $id): void
    {
        $this->processService->deleteProcess(id: $id);
    }
}
