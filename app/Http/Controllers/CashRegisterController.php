<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CashRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CashRegisterTransaction;

class CashRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Create cash register
    public function create()
    {   
        $warehouses = '';
        $accounts = '';
        $cashCounters = DB::table('cash_counters')
        ->where('branch_id', auth()->user()->branch_id)
        ->get(['id', 'counter_name', 'short_name']);

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $warehouses = DB::table('warehouses')->get(['id', 'warehouse_name', 'warehouse_code']);
            $accounts = DB::table('accounts')->get(['id', 'name', 'account_number', 'balance']);
        }
        $openedCashRegister = CashRegister::with('branch', 'admin', 'admin.role')
        ->where('admin_id', auth()->user()->id)->where('status', 1)
        ->first();

        if (!$openedCashRegister) {
            return view('sales.cash_register.create', compact('warehouses', 'accounts', 'cashCounters'));
        } else {
            return redirect()->route('sales.pos.create');
        }
    }

    // Store cash register
    public function store(Request $request)
    {
        //return $request->all();
        $addCashRegister = new CashRegister();
        $this->validate($request, [
            'cash_in_hand' => 'required',
        ]);

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $this->validate($request, [
                'warehouse_id' => 'required',
            ]);
        }
        //$addCashRegister->amount = $request->cash_in_hand;
        $addCashRegister->admin_id = auth()->user()->id;
        $addCashRegister->cash_counter_id = $request->counter_id;
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $addCashRegister->warehouse_id = $request->warehouse_id;
            $addCashRegister->account_id = $request->account_id;
        }else {
            $addCashRegister->branch_id = auth()->user()->branch_id;
        }
        $addCashRegister->save();

        $addCashRegisterTransaction = new CashRegisterTransaction();
        $addCashRegisterTransaction->cash_register_id = $addCashRegister->id;
        $addCashRegisterTransaction->cash_type = 2;
        $addCashRegisterTransaction->transaction_type = 1;
        $addCashRegisterTransaction->amount = $request->cash_in_hand;
        $addCashRegisterTransaction->save();
        return redirect()->route('sales.pos.create');
    }

    // Close cash register
    public function cashRegisterDetails()
    {
        if (auth()->user()->permission->register['register_view'] == '0') {
            return 'Access Forbidden';
        }

        $activeCashRegister = CashRegister::with([
            'branch',
            'admin',
            'admin.role',
            'cash_register_transactions',
            'cash_register_transactions.sale',
            'cash_register_transactions.sale.sale_products',
            'cash_register_transactions.sale.sale_payments',
            'cash_counter'
        ])->where('admin_id', auth()->user()->id)->where('status', 1)->first();
        return view('sales.cash_register.ajax_view.cash_register_details', compact('activeCashRegister'));
    }

    // get closing cash register details 
    public function closeCashRegisterModalView()
    {
        $activeCashRegister = CashRegister::with([
            'cash_register_transactions',
            'cash_register_transactions.sale',
            'cash_register_transactions.sale.sale_products',
            'cash_register_transactions.sale.sale_payments'
        ])->where('admin_id', auth()->user()->id)->where('status', 1)->first();
        return view('sales.cash_register.ajax_view.close_cash_register_view', compact('activeCashRegister'));
    }

    // Close cash register
    public function close(Request $request)
    {
        $this->validate($request, [
            'total_cash' => 'required',
            'total_card_slip' => 'required',
            'total_cheque' => 'required',
        ]);

        $closeCashRegister = CashRegister::where('admin_id', auth()->user()->id)->where('status', 1)->first();
        $closeCashRegister->closed_amount = $request->total_cash;
        $closeCashRegister->total_card_slips = $request->total_card_slip;
        $closeCashRegister->total_cheques = $request->total_cheque;
        $closeCashRegister->closing_note = $request->closing_note;
        $closeCashRegister->closed_at = Carbon::now()->format('Y-m-d H:i:00');
        $closeCashRegister->status = 0;
        $closeCashRegister->save();
        return redirect()->back();
    }
}