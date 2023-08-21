<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\PaymentMethodService;

class PaymentMethodController extends Controller
{

    public function __construct(private PaymentMethodService $paymentMethodService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->paymentMethodService->paymentMethodListTable();
        }

        return view('setups.payment_methods.index');
    }

    public function create()
    {
        return view('setups.payment_methods.ajax_view.create');
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|unique:payment_methods,name',
            ],
        );

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
        $method = $this->paymentMethodService->singlePaymentMethod(id: $id);

        return view('setups.payment_methods.ajax_view.edit', compact('method'));
    }

    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|unique:payment_methods,name,' . $id,
            ]
        );

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
