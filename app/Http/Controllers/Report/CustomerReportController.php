<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CustomerReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = config('generalSettings');
            $branchWiseCustomerAmountUtil =  new \App\Utils\BranchWiseCustomerAmountUtil();

            $customers = DB::table('customers')
                ->select(
                    'customers.id',
                    'customers.contact_id',
                    'customers.name',
                    'customers.phone',
                )->orderBy('customers.name');

            return DataTables::of($customers)
                ->editColumn('name', function ($row) {

                    return $row->name . '(<b>' . $row->phone . '</b>)';
                })
                ->editColumn('opening_balance', function ($row) use ($request, $branchWiseCustomerAmountUtil) {

                    $openingBalance = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['opening_balance'];
                    return '<span class="opening_balance" data-value="' . $openingBalance . '">' . \App\Utils\Converter::format_in_bdt($openingBalance) . '</span>';
                })

                ->editColumn('total_sale', function ($row) use ($request, $branchWiseCustomerAmountUtil) {

                    $totalSale = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_sale'];
                    return '<span class="total_sale" data-value="' . $totalSale . '">' . \App\Utils\Converter::format_in_bdt($totalSale) . '</span>';
                })

                ->editColumn('total_paid', function ($row) use ($request, $branchWiseCustomerAmountUtil) {

                    $totalPaid = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_paid'];
                    return '<span class="total_paid" data-value="' . $totalPaid . '">' . \App\Utils\Converter::format_in_bdt($totalPaid) . '</span>';
                })

                ->editColumn('total_sale_due', function ($row) use ($request, $branchWiseCustomerAmountUtil) {

                    $totalSaleDue = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_sale_due'];
                    return '<span class="total_sale_due" data-value="' . $totalSaleDue . '">' . \App\Utils\Converter::format_in_bdt($totalSaleDue) . '</span>';
                })

                ->editColumn('total_return', function ($row) use ($request, $branchWiseCustomerAmountUtil) {

                    $totalReturn = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_return'];
                    return '<span class="total_return" data-value="' . $totalReturn . '">' . \App\Utils\Converter::format_in_bdt($totalReturn) . '</span>';
                })

                ->editColumn('total_sale_return_due', function ($row) use ($request, $branchWiseCustomerAmountUtil) {

                    $totalSaleReturnDue = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($row->id, $request->branch_id)['total_sale_return_due'];
                    return '<span class="total_sale_return_due" data-value="' . $totalSaleReturnDue . '">' . \App\Utils\Converter::format_in_bdt($totalSaleReturnDue) . '</span>';
                })

                ->rawColumns(['name', 'opening_balance', 'total_sale', 'total_paid', 'total_sale_due', 'total_return', 'total_sale_return_due'])
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

        $branchWiseCustomerAmountUtil =  new \App\Utils\BranchWiseCustomerAmountUtil();

        $customerReports = DB::table('customers')->select(
            'customers.id',
            'customers.contact_id',
            'customers.name',
            'customers.phone',
        )->orderBy('customers.name')->get();

        return view('reports.customer_report.ajax_view.print', compact('customerReports', 'branchWiseCustomerAmountUtil'));
    }
}
