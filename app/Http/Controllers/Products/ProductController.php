<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductEditRequest;
use App\Http\Requests\Products\ProductIndexRequest;
use App\Http\Requests\Products\ProductStoreRequest;
use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Products\ProductDeleteRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Interfaces\Products\ProductControllerMethodContainersInterface;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request, ProductControllerMethodContainersInterface $productControllerMethodContainersInterface, $isForCreatePage = 0)
    {
        $indexMethodContainer = $productControllerMethodContainersInterface->indexMethodContainer(request: $request, isForCreatePage: $isForCreatePage);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('product.products.index', compact('categories', 'brands', 'units', 'taxAccounts', 'branches'));
    }

    public function show($id, ProductControllerMethodContainersInterface $productControllerMethodContainersInterface)
    {
        $showMethodContainer = $productControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('product.products.ajax_view.show', compact('product', 'ownAndOtherBranchAndWarehouseStocks', 'priceGroups'));
    }

    public function create(ProductCreateRequest $request, ProductControllerMethodContainersInterface $productControllerMethodContainersInterface, $id = null)
    {
        $createMethodContainer = $productControllerMethodContainersInterface->createMethodContainer(request: $request, id: $id);

        if ($request->ajax()) {

            return $createMethodContainer;
        }

        extract($createMethodContainer);

        return view('product.products.create', compact('units', 'categories', 'brands', 'warranties', 'taxAccounts', 'branches', 'bulkVariants', 'lastProductSerialCode', 'product'));
    }

    public function store(ProductStoreRequest $request, ProductControllerMethodContainersInterface $productControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $productControllerMethodContainersInterface->storeMethodContainer(request: $request);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return $storeMethodContainer;
    }

    public function edit($id, ProductEditRequest $request, ProductControllerMethodContainersInterface $productControllerMethodContainersInterface)
    {
        $editMethodContainer = $productControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('product.products.edit', compact('units', 'categories', 'subCategories', 'brands', 'warranties', 'taxAccounts', 'branches', 'bulkVariants', 'lastProductSerialCode', 'product'));
    }

    public function update($id, ProductUpdateRequest $request, ProductControllerMethodContainersInterface $productControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $productControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Product updated successfully.'));
    }

    public function formPart($type, ProductControllerMethodContainersInterface $productControllerMethodContainersInterface)
    {
        $formPartMethodContainer = $productControllerMethodContainersInterface->formPartMethodContainer(type: $type);

        extract($formPartMethodContainer);

        return view('product.products.ajax_view.form_part', compact('type', 'taxAccounts', 'bulkVariants', 'units', 'defaultUnitId', 'defaultUnitName'));
    }

    public function changeStatus($id, ProductControllerMethodContainersInterface $productControllerMethodContainersInterface)
    {
        $changeStatusMethodContainer = $productControllerMethodContainersInterface->changeStatusMethodContainer(id: $id);
        return response()->json($changeStatusMethodContainer['msg']);
    }

    public function delete($id, ProductDeleteRequest $request, ProductControllerMethodContainersInterface $productControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $productControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Product deleted successfully.'));
    }

    public function getLastProductId(ProductControllerMethodContainersInterface $productControllerMethodContainersInterface)
    {
        return $productControllerMethodContainersInterface->getLastProductIdMethodContainer();
    }
}
