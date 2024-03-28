<?php

namespace App\Http\Controllers\Sales;

use App\Enums\SaleStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\CodeGenerationService;
use App\Http\Requests\Sales\AddSaleStoreRequest;
use App\Http\Requests\Sales\AddSaleUpdateRequest;
use App\Interfaces\Sales\AddSaleControllerMethodContainersInterface;

class AddSalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface, $customerAccountId = null, $saleScreen = null)
    {
        abort_if(!auth()->user()->can('view_add_sale'), 403);

        $indexMethodContainer = $addSaleControllerMethodContainersInterface->indexMethodContainer(customerAccountId: $customerAccountId, saleScreen: $saleScreen, request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;;
        }

        extract($indexMethodContainer);

        return view('sales.add_sale.index', compact('branches', 'customerAccounts'));
    }

    public function show($id, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        $showMethodContainer = $addSaleControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('sales.add_sale.ajax_views.show', compact('sale'));
    }

    public function create(AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('create_add_sale'), 403);

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

    public function edit($id, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('edit_add_sale'), 403);

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

    public function delete($id, AddSaleControllerMethodContainersInterface $addSaleControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('delete_add_sale'), 403);

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

    public function searchByInvoiceId($keyWord)
    {
        $sales = DB::table('sales')
            ->where('sales.invoice_id', 'like', "%{$keyWord}%")
            ->where('sales.branch_id', auth()->user()->branch_id)
            ->where('sales.status', SaleStatus::Final->value)
            ->select('sales.id as sale_id', 'sales.invoice_id', 'sales.customer_account_id')->limit(35)->get();

        if (count($sales) > 0) {

            return view('search_results_view.sale_invoice_search_result_list', compact('sales'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }
}
