<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\ContraStoreRequest;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Accounts\ContraDeleteRequest;
use App\Http\Requests\Accounts\ContraUpdateRequest;
use App\Interfaces\Accounts\ContraControllerMethodContainersInterface;

class ContraController extends Controller
{
    public function index(Request $request, ContraControllerMethodContainersInterface $contraControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('contras_index'), 403);

        $indexMethodContainer = $contraControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;;
        }

        extract($indexMethodContainer);

        return view('accounting.accounting_vouchers.contras.index', compact('branches'));
    }

    public function show(ContraControllerMethodContainersInterface $contraControllerMethodContainersInterface, $id)
    {
        $showMethodContainer = $contraControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('accounting.accounting_vouchers.contras.ajax_view.show', compact('contra'));
    }

    public function print($id, Request $request, ContraControllerMethodContainersInterface $contraControllerMethodContainersInterface)
    {
        $printMethodContainer = $contraControllerMethodContainersInterface->printMethodContainer(id: $id, request: $request);

        extract($printMethodContainer);

        return view('accounting.accounting_vouchers.print_templates.print_contra', compact('contra', 'printPageSize'));
    }

    public function create(ContraControllerMethodContainersInterface $contraControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('contras_create'), 403);

        $createMethodContainer = $contraControllerMethodContainersInterface->createMethodContainer();

        extract($createMethodContainer);

        return view('accounting.accounting_vouchers.contras.ajax_view.create', compact('accounts', 'methods'));
    }

    public function store(ContraStoreRequest $request, ContraControllerMethodContainersInterface $contraControllerMethodContainersInterface, CodeGenerationServiceInterface $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $contraControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('accounting.accounting_vouchers.print_templates.print_contra', compact('contra', 'printPageSize'));
        } else {

            return response()->json(['successMsg' => __('Contra added successfully.')]);
        }
    }

    public function edit($id, ContraControllerMethodContainersInterface $contraControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('contras_edit'), 403);

        $editMethodContainer = $contraControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('accounting.accounting_vouchers.contras.ajax_view.edit', compact('accounts', 'methods', 'contra'));
    }

    public function update($id, ContraUpdateRequest $request, ContraControllerMethodContainersInterface $contraControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $contraControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Contra updated successfully.'));
    }

    public function delete($id, ContraDeleteRequest $request, ContraControllerMethodContainersInterface $contraControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $contraControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Contra deleted successfully.'));
    }
}
