<?php

namespace App\Http\Controllers\report;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SupplierReportController extends Controller
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
            $suppliers = '';
            $query = DB::table('suppliers')->where('status', 1);

            if ($request->supplier_id) {
                $query->where('suppliers.id', $request->supplier_id);
            }

            $suppliers = $query->select(
                'suppliers.name',
                'suppliers.opening_balance',
                'suppliers.total_paid',
                'suppliers.total_purchase',
                'suppliers.total_purchase_due',
                'suppliers.total_purchase_return_due'
            )
            ->get();

            return DataTables::of($suppliers)
                ->editColumn('opening_balance', function ($row) use ($generalSettings) {
                    return '<b><span class="opening_balance" data-value="'.$row->opening_balance.'">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->opening_balance . '</span></b>';
                })
                ->editColumn('total_paid', function ($row) use ($generalSettings) {
                    return '<b><span class="total_paid" data-value="'.$row->total_paid.'">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_paid . '</span></b>';
                })
                ->editColumn('total_purchase_due', function ($row) use ($generalSettings) {
                    return '<b><span class="total_purchase_due" data-value="'.$row->total_purchase_due.'">' . json_decode($generalSettings->business, true)['currency'] .$row->total_purchase_due. '</span></b>';
                })
                ->editColumn('total_purchase', function ($row) use ($generalSettings) {
                    return '<b><span class="total_purchase" data-value="'.$row->total_purchase.'">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_purchase . '</span></b>';
                })
                ->editColumn('total_purchase_return_due', function ($row) use ($generalSettings) {
                    return '<b><span class="total_purchase_return_due" data-value="'.$row->total_purchase_return_due.'">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_purchase_return_due . '</span></b>';
                })
                ->rawColumns(['opening_balance', 'total_paid', 'total_purchase', 'total_purchase_due', 'total_due', 'total_purchase_return_due'])
                ->make(true);
        }

        $suppliers = DB::table('suppliers')->select('id','name', 'phone')->get();
        return view('reports.supplier_report.index', compact('suppliers'));
    }
}
