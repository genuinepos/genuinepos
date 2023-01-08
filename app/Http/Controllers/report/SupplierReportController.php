<?php

namespace App\Http\Controllers\Report;

use App\Utils\Converter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SupplierReportController extends Controller
{
    protected $converter;
    public function __construct(Converter $converter)
    {
        $this->converter = $converter;

    }

    // Index view of supplier report
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = config('generalSettings');
            $suppliers = '';
            $query = DB::table('suppliers')->where('status', 1);

            if ($request->supplier_id) {

                $query->where('suppliers.id', $request->supplier_id);
            }

            $suppliers = $query->select(
                'suppliers.name',
                'suppliers.contact_id',
                'suppliers.phone',
                'suppliers.address',
                'suppliers.opening_balance',
                'suppliers.total_paid',
                'suppliers.total_purchase',
                'suppliers.total_purchase_due',
                'suppliers.total_purchase_return_due'
            );

            return DataTables::of($suppliers)
                ->editColumn('name', function ($row) {
                    return $row->name.' (ID: '.$row->contact_id.')';
                })
                ->editColumn('opening_balance', fn ($row) => '<span class="opening_balance" data-value="'.$row->opening_balance.'">' . $this->converter->format_in_bdt($row->opening_balance) . '</span>')
                ->editColumn('total_paid', fn ($row) => '<b><span class="total_paid" data-value="'.$row->total_paid.'">' . $this->converter->format_in_bdt($row->total_paid) . '</span>')
                ->editColumn('total_purchase_due', fn ($row) => '<span class="total_purchase_due" data-value="'.$row->total_purchase_due.'">' . $this->converter->format_in_bdt($row->total_purchase_due). '</span>')
                ->editColumn('total_purchase', fn ($row) => '<span class="total_purchase" data-value="'.$row->total_purchase.'">' . $this->converter->format_in_bdt($row->total_purchase) . '</span></b>')
                ->editColumn('total_purchase_return_due', fn ($row) => '<span class="total_purchase_return_due" data-value="'.$row->total_purchase_return_due.'">' . $this->converter->format_in_bdt($row->total_purchase_return_due) . '</span>')
                ->rawColumns(['name', 'opening_balance', 'total_paid', 'total_purchase', 'total_purchase_due', 'total_due', 'total_purchase_return_due'])
                ->make(true);
        }

        $suppliers = DB::table('suppliers')->select('id','name', 'phone')->get();
        return view('reports.supplier_report.index', compact('suppliers'));
    }

    public function print(Request $request)
    {
        $supplierReports = '';
        $supplierId = $request->supplier_id;
        $query = DB::table('suppliers')->where('status', 1);

        if ($request->supplier_id) {
            $query->where('suppliers.id', $request->supplier_id);
        }

        $supplierReports = $query->select(
            'suppliers.name',
            'suppliers.contact_id',
            'suppliers.phone',
            'suppliers.address',
            'suppliers.opening_balance',
            'suppliers.total_paid',
            'suppliers.total_purchase',
            'suppliers.total_purchase_due',
            'suppliers.total_purchase_return_due'
        )->get();

        return view('reports.supplier_report.ajax_view.print', compact('supplierReports', 'supplierId'));
    }
}
