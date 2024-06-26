<?php

namespace App\Http\Controllers\Sales;

use App\Enums\SaleStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\CodeGenerationService;
use App\Http\Requests\Sales\AddSaleEditRequest;
use App\Http\Requests\Sales\AddSaleIndexRequest;
use App\Http\Requests\Sales\AddSaleStoreRequest;
use App\Http\Requests\Sales\AddSaleCreateRequest;
use App\Http\Requests\Sales\AddSaleDeleteRequest;
use App\Http\Requests\Sales\AddSaleUpdateRequest;
use App\Interfaces\Sales\AddSaleControllerMethodContainersInterface;

class AddSalesController extends Controller
{
    public function index(AddSaleIndexRequest $request, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        $indexMethodContainer = $addSaleControllerMethodContainersInterface->indexMethodContainer(request: $request);

        // if ($request->ajax()) {

        //     return $indexMethodContainer;;
        // }

        extract($indexMethodContainer);

        return view('sales.add_sale.index', compact('branches', 'customerAccounts'));
    }

    public function show($id, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        $showMethodContainer = $addSaleControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('sales.add_sale.ajax_views.show', compact('sale'));
    }

    public function create(AddSaleCreateRequest $request, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        $createMethodContainer = $addSaleControllerMethodContainersInterface->createMethodContainer();

        extract($createMethodContainer);

        return view('sales.add_sale.create', compact('customerAccounts', 'methods', 'accounts', 'saleAccounts', 'taxAccounts', 'priceGroups', 'priceGroupProducts', 'warehouses', 'branchName'));
    }

    public function store(AddSaleStoreRequest $request, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface, CodeGenerationService $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $addSaleControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return $addSaleControllerMethodContainersInterface->printTemplateBySaleStatus(request: $request, sale: $sale, customerCopySaleProducts: $customerCopySaleProducts);
        } else {

            return response()->json(['saleFinalMsg' => __('Sale created successfully')]);
        }
    }

    public function edit($id, AddSaleEditRequest $request, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        $editMethodContainer = $addSaleControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('sales.add_sale.edit', compact('sale', 'customerAccounts', 'methods', 'accounts', 'saleAccounts', 'taxAccounts', 'priceGroups', 'priceGroupProducts', 'warehouses', 'branchName'));
    }

    public function update($id, AddSaleUpdateRequest $request, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface, CodeGenerationService $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $addSaleControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request, codeGenerator: $codeGenerator);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Sale updated Successfully.'));
    }

    public function delete($id, AddSaleDeleteRequest $request, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $addSaleControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        $voucherName = SaleStatus::tryFrom($deleteMethodContainer->status)->name;
        $__voucherName = $voucherName == 'Final' ? __('Sale') : $voucherName;

        return response()->json(__("${__voucherName} deleted Successfully."));
    }

    public function searchByInvoiceId($keyWord, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        $searchByInvoiceIdMethodContainer = $addSaleControllerMethodContainersInterface->searchByInvoiceIdMethodContainer(keyWord: $keyWord);
        if (isset($searchByInvoiceIdMethodContainer['noResult'])) {

            return ['noResult' => 'no result'];
        } else {

            return $searchByInvoiceIdMethodContainer;
        }
    }
}
