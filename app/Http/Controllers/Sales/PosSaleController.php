<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Interfaces\Sales\PosSaleControllerMethodContainersInterface;

class PosSaleController extends Controller
{
    public function index(Request $request, PosSaleControllerMethodContainersInterface $posSaleControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('pos_all'), 403);

        $indexMethodContainer = $posSaleControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;;
        }

        extract($indexMethodContainer);

        return view('sales.pos.index', compact('branches', 'customerAccounts'));
    }

    public function create(PosSaleControllerMethodContainersInterface $posSaleControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('pos_add'), 403);

        return $posSaleControllerMethodContainersInterface->createMethodContainer();
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerator, PosSaleControllerMethodContainersInterface $posSaleControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('pos_add'), 403);

        try {
            DB::beginTransaction();

            $storeMethodContainer =  $posSaleControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

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

    public function edit($id, PosSaleControllerMethodContainersInterface $posSaleControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('pos_edit'), 403);

        return $posSaleControllerMethodContainersInterface->editMethodContainer(id: $id);
    }

    public function update($id, Request $request, CodeGenerationServiceInterface $codeGenerator, PosSaleControllerMethodContainersInterface $posSaleControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('pos_edit'), 403);

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
}
