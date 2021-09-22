<?php

namespace App\Http\Controllers\report;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CustomerReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of supplier report
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $customers = '';
            $query = DB::table('customers')->where('status', 1);
            if ($request->customer_id) {
                $query->where('customers.id', $request->customer_id);
            }

            $customers = $query->select(
                'customers.name',
                'customers.contact_id',
                'customers.phone',
                'customers.address',
                'customers.opening_balance',
                'customers.total_paid',
                'customers.total_sale',
                'customers.total_sale_due',
                'customers.total_sale_return_due'
            );

            return DataTables::of($customers)
                ->editColumn('name', function ($row) use ($generalSettings) {
                    return $row->name.' (ID: '.$row->contact_id.')';
                })
                ->editColumn('opening_balance', function ($row) use ($generalSettings) {
                    return '<b><span class="opening_balance" data-value="' . $row->opening_balance . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->opening_balance . '</span></b>';
                })
                ->editColumn('total_paid', function ($row) use ($generalSettings) {
                    return '<b><span class="total_paid" data-value="' . $row->total_paid . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_paid . '</span></b>';
                })
                ->editColumn('total_sale_due', function ($row) use ($generalSettings) {
                    return '<b><span class="total_purchase_due" data-value="' . $row->total_sale_due . '">' . json_decode($generalSettings->business, true)['currency'] . $row->total_sale_due . '</span></b>';
                })
                ->editColumn('total_sale', function ($row) use ($generalSettings) {
                    return '<b><span class="total_sale" data-value="' . $row->total_sale . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_sale . '</span></b>';
                })
                ->editColumn('total_sale_return_due', function ($row) use ($generalSettings) {
                    return '<b><span class="total_sale_return_due" data-value="' . $row->total_sale_return_due . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_sale_return_due . '</span></b>';
                })
                ->rawColumns(['name', 'opening_balance', 'total_paid', 'total_sale', 'total_sale_due', 'total_due', 'total_sale_return_due'])
                ->make(true);
        }

        $customers = DB::table('customers')->select('id', 'name', 'phone')->get();
        return view('reports.customer_report.index', compact('customers'));
    }

    public function print(Request $request)
    {
        $customerReports = '';
        $query = DB::table('customers')->where('status', 1);
        if ($request->customer_id) {
            $query->where('customers.id', $request->customer_id);
        }

        $customerReports = $query->select(
            'customers.name',
            'customers.contact_id',
            'customers.phone',
            'customers.address',
            'customers.opening_balance',
            'customers.total_paid',
            'customers.total_sale',
            'customers.total_sale_due',
            'customers.total_sale_return_due'
        )->get();
        return view('reports.customer_report.ajax_view.print', compact('customerReports'));
    }
}
