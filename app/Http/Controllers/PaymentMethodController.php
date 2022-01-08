<?php

namespace App\Http\Controllers;

use App\Utils\Util;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodController extends Controller
{
    protected $util;
    public function __construct(Util $util) {
        $this->util = $util;
        $this->middleware('auth:admin_and_user');
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $methods = DB::table('payment_methods')
                ->leftJoin('accounts', 'payment_methods.account_id', 'accounts.id')
                ->select('payment_methods.*', 'accounts.name as ac_name', 'accounts.account_type')
                ->orderBy('id', 'desc')
                ->get();
            return DataTables::of($methods)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('settings.payment.method.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="' . route('settings.payment.method.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('account', fn ($row) => $row->ac_name.' (<b>'.$this->util->accountType($row->account_type).'</b>)')
                ->rawColumns(['action', 'account'])
                ->make(true);
        }
        $accounts = DB::table('accounts')->orderBy('account_type', 'asc')->get(['id', 'name', 'account_number', 'account_type']);
        return view('settings.payment_settings.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:payment_methods,name',
            'account_id' => 'required',
        ],[
            'account_id.required' => 'Please select a default account',
        ]);

        PaymentMethod::insert([
            'name' => $request->name,
            'account_id' => $request->account_id,
        ]);

        return response()->json('Payment method created successfully.');
    }

    public function edit($id)
    {
        $method = DB::table('payment_methods')->where('id', $id)->first();
        $accounts = DB::table('accounts')->get(['id', 'name', 'account_number']);
        return view('settings.payment_settings.ajax_view.edit_payment_method', compact('method', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:payment_methods,name,' . $id,
            'account_id' => 'required',
        ],[
            'account_id.required' => 'Please select a default account',
        ]);

        $updatePayment = PaymentMethod::where('id', $id)->first();
        $updatePayment->update([
            'name' => $request->name,
            'account_id' => $request->account_id,
        ]);

        return response()->json('Payment Method update successfully.');
    }

    public function delete(Request $request, $id)
    {
        $deleteCardType = PaymentMethod::where('id', $id)->first();
        if (!is_null($deleteCardType)) {
            $deleteCardType->delete();
        }

        return response()->json('Payment Method deleted successfully.');
    }
}
