<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\PosSaleEditRequest;
use App\Http\Requests\Sales\PosSaleIndexRequest;
use App\Http\Requests\Sales\PosSaleStoreRequest;
use App\Http\Requests\Sales\PosSaleCreateRequest;
use App\Http\Requests\Sales\PosSaleDeleteRequest;
use App\Http\Requests\Sales\PosSaleUpdateRequest;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Interfaces\Sales\PosSaleControllerMethodContainersInterface;

class PosSaleController extends Controller
{
    public function index(PosSaleIndexRequest $request, PosSaleControllerMethodContainersInterface $posSaleControllerMethodContainersInterface)
    {
        $indexMethodContainer = $posSaleControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('sales.pos.index', compact('branches', 'customerAccounts'));
    }

    public function create(PosSaleCreateRequest $request, CodeGenerationServiceInterface $codeGenerator, PosSaleControllerMethodContainersInterface $posSaleControllerMethodContainersInterface, $jobCardId = 'no_id', $saleScreenType = null)
    {
        return $posSaleControllerMethodContainersInterface->createMethodContainer(codeGenerator: $codeGenerator, jobCardId: $jobCardId, saleScreenType: $saleScreenType);
    }

    public function store(PosSaleStoreRequest $request, CodeGenerationServiceInterface $codeGenerator, PosSaleControllerMethodContainersInterface $posSaleControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $posSaleControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $posSaleControllerMethodContainersInterface->printTemplateBySaleStatusForStore(request: $request, sale: $sale, customerCopySaleProducts: $customerCopySaleProducts);
    }

    public function edit(PosSaleEditRequest $request, PosSaleControllerMethodContainersInterface $posSaleControllerMethodContainersInterface, $id, $saleScreenType = null)
    {
        return $posSaleControllerMethodContainersInterface->editMethodContainer(id: $id, saleScreenType: $saleScreenType);
    }

    public function update($id, PosSaleUpdateRequest $request, CodeGenerationServiceInterface $codeGenerator, PosSaleControllerMethodContainersInterface $posSaleControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $posSaleControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request, codeGenerator: $codeGenerator);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            extract($updateMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $posSaleControllerMethodContainersInterface->printTemplateBySaleStatusForUpdate(request: $request, sale: $sale, customerCopySaleProducts: $customerCopySaleProducts);
    }

    public function delete($id, PosSaleDeleteRequest $request, PosSaleControllerMethodContainersInterface $posSaleControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $posSaleControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("POS Sale deleted Successfully."));
    }
}
