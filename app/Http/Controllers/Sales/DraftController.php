<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\DraftUpdateRequest;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Interfaces\Sales\DraftControllerMethodContainersInterface;

class DraftController extends Controller
{
    public function index(Request $request, DraftControllerMethodContainersInterface $draftControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('sale_draft'), 403);

        $indexMethodContainer = $draftControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;;
        }

        extract($indexMethodContainer);

        return view('sales.add_sale.drafts.index', compact('branches', 'customerAccounts'));
    }

    public function show($id, DraftControllerMethodContainersInterface $draftControllerMethodContainersInterface)
    {
        $showMethodContainer = $draftControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('sales.add_sale.drafts.ajax_views.show', compact('draft', 'customerCopySaleProducts'));
    }

    public function edit($id, DraftControllerMethodContainersInterface $draftControllerMethodContainersInterface)
    {
        $editMethodContainer = $draftControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('sales.add_sale.drafts.edit', compact('draft', 'customerAccounts', 'accounts', 'methods', 'warehouses', 'saleAccounts', 'taxAccounts', 'priceGroups', 'priceGroupProducts', 'branchName'));
    }

    public function update($id, DraftUpdateRequest $request, DraftControllerMethodContainersInterface $draftControllerMethodContainersInterface, CodeGenerationServiceInterface $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $draftControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request, codeGenerator: $codeGenerator);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Draft updated Successfully.'));
    }
}
