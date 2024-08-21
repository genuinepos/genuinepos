<?php

namespace App\Http\Controllers\Services;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Services\ServiceInvoiceIndexRequest;
use App\Http\Requests\Services\ServiceInvoiceDeleteRequest;
use App\Interfaces\Services\ServiceInvoiceControllerMethodContainersInterface;

class ServiceInvoiceController extends Controller
{
    public function index(ServiceInvoiceIndexRequest $request, ServiceInvoiceControllerMethodContainersInterface $serviceInvoiceControllerMethodContainersInterface)
    {
        $indexMethodContainer = $serviceInvoiceControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('services.invoices.index', compact('branches', 'customerAccounts'));
    }

    public function delete($id, ServiceInvoiceDeleteRequest $request, ServiceInvoiceControllerMethodContainersInterface $serviceInvoiceControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $serviceInvoiceControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Invoice deleted successfully.'));
    }
}
