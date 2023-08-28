<?php

namespace App\Services\Setups;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodService
{
    public function paymentMethodListTable()
    {
        $methods = DB::table('payment_methods')->get();

        return DataTables::of($methods)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                if ($row->is_fixed == 0) {

                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="'.route('payment.methods.edit', [$row->id]).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="'.route('payment.methods.delete', [$row->id]).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                    $html .= '</div>';

                    return $html;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function addPaymentMethod(object $request): object
    {
        return PaymentMethod::create(['name' => $request->name]);
    }

    public function updatePaymentMethod($request, $id): void
    {
        $updatePayment = PaymentMethod::where('id', $id)->first();

        $updatePayment->update([
            'name' => $request->name,
        ]);
    }

    public function deletePaymentMethod(int $id): array
    {
        $deletePaymentMethod = PaymentMethod::where('id', $id)->first();

        if (! is_null($deletePaymentMethod)) {

            if ($deletePaymentMethod->is_fixed == 1) {

                return ['success' => false, 'msg' => __('This Payment Method Can not be deleted')];
            }

            $deletePaymentMethod->delete();
        }

        return ['success' => true, 'msg' => __('Payment Method deleted successfully.')];
    }

    public function singlePaymentMethod(int $id, array $with = null): ?PaymentMethod
    {
        $query = PaymentMethod::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
