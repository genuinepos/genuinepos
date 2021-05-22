<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use App\Models\CashRegister;
use Illuminate\Http\Request;

class CashRegisterReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of cash register report
    public function index()
    {
        return view('reports.cash_register_report.index');
    }

    // Get cash register reports
    public function getCashRegisterReport(Request $request)
    {
        $cash_registers = '';
        $query = CashRegister::with(
            [
                'branch',
                'admin', 
                'cash_register_transactions', 
                'cash_register_transactions.sale',
                'cash_register_transactions.sale.sale_payments'
            ]
        );

        if ($request->user_id) {
            $query->where('admin_id', $request->user_id);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            //$form_date = date('Y-m-d', strtotime($date_range[0] . ' -1 days'));
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
            $query->whereBetween('created_at', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        } else {
            $today = date('Y-m-d');
            $star_date = date('Y-m-d', strtotime($today));
            $end_date = date('Y-m-d', strtotime($today . ' +1 days'));
            $query->whereBetween('created_at', [$star_date . ' 00:00:00', $end_date . ' 00:00:00']);
        }

        if ($request->status) {
            if ($request->status == 1) {
                $query->where('status', 1);
            }elseif ($request->status == 2) {
                $query->where('status', 0);
            }
        }

        $cash_registers = $query->get(); 
        return view('reports.cash_register_report.ajax_view.cash_register_list', compact('cash_registers'));
    }

    public function detailsCashRegister($cashRegisterId)
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
            'cash_register_transactions.sale.sale_payments'
        ])->where('id', $cashRegisterId)->first();
        return view('sales.cash_register.ajax_view.cash_register_details', compact('activeCashRegister'));
    }
}
