<?php

namespace App\Http\Controllers\Setups;

use App\Http\Controllers\Controller;
use App\Services\Setups\PaymentMethodService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentMethodController extends Controller
{
    public function __construct(private PaymentMethodService $paymentMethodService)
    {
        $this->middleware('expireDate');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('payment_methods_index'), 403);

        if ($request->ajax()) {

            return $this->paymentMethodService->paymentMethodListTable();
        }

        return view('setups.payment_methods.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->can('payment_methods_add'), 403);

        return view('setups.payment_methods.ajax_view.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('payment_methods_add'), 403);

        $this->paymentMethodService->paymentMethodStoreValidation(request: $request);

        try {
            DB::beginTransaction();

            $this->paymentMethodService->addPaymentMethod($request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Payment method created successfully.'));
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('payment_methods_edit'), 403);
        $method = $this->paymentMethodService->singlePaymentMethod(id: $id);

        return view('setups.payment_methods.ajax_view.edit', compact('method'));
    }

    public function update(Request $request, $id)
    {
        abort_if(!auth()->user()->can('payment_methods_edit'), 403);

        $this->paymentMethodService->paymentMethodUpdateValidation(request: $request, id: $id);

        try {
            DB::beginTransaction();

            $this->paymentMethodService->updatePaymentMethod($request, $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Payment Method update successfully.'));
    }

    public function delete(Request $request, $id)
    {
        abort_if(!auth()->user()->can('payment_methods_delete'), 403);

        try {
            DB::beginTransaction();

            $deletePaymentMethod = $this->paymentMethodService->deletePaymentMethod($id);

            if ($deletePaymentMethod['success'] == false) {

                return response()->json(['errorMsg' => $deletePaymentMethod['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json($deletePaymentMethod['msg']);
    }
}
