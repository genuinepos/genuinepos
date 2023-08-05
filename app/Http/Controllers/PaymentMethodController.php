<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodController extends Controller
{
    protected $util;

    public function __construct(Util $util)
    {
        $this->util = $util;

    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $methods = DB::table('payment_methods')
                ->select('payment_methods.*')
                ->get();

            return DataTables::of($methods)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    if ($row->is_fixed == 0) {

                        $html = '<div class="dropdown table-dropdown">';
                        $html .= '<a href="'.route('settings.payment.method.edit', [$row->id]).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                        $html .= '<a href="'.route('settings.payment.method.delete', [$row->id]).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                        $html .= '</div>';

                        return $html;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('settings.payment_method.index');
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|unique:payment_methods,name',
            ],
        );

        PaymentMethod::insert([
            'name' => $request->name,
        ]);

        return response()->json('Payment method created successfully.');
    }

    public function edit($id)
    {
        $method = DB::table('payment_methods')->where('id', $id)->first();

        return view('settings.payment_method.ajax_view.edit_payment_method', compact('method'));
    }

    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|unique:payment_methods,name,'.$id,
            ],

        );

        $updatePayment = PaymentMethod::where('id', $id)->first();

        $updatePayment->update([
            'name' => $request->name,
        ]);

        return response()->json('Payment Method update successfully.');
    }

    public function delete(Request $request, $id)
    {
        $deletePaymentMethod = PaymentMethod::where('id', $id)->first();

        if (! is_null($deletePaymentMethod)) {

            if ($deletePaymentMethod->is_fixed == 1) {

                return response()->json('Can not delete, This payment method is fixed');
            }

            $deletePaymentMethod->delete();
        }

        return response()->json('Payment Method deleted successfully.');
    }
}
