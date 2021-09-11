<?php

namespace App\Http\Controllers\report;

use App\Models\Expanse;
//use App\Charts\CommonChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ExpanseReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of expense report
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $expenses = '';
            $query = DB::table('expanses')
                ->leftJoin('branches', 'expanses.branch_id', 'branches.id')
                ->leftJoin('admin_and_users', 'expanses.admin_id', 'admin_and_users.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('expanses.branch_id', NULL);
                } else {
                    $query->where('expanses.branch_id', $request->branch_id);
                }
            }

            if ($request->admin_id) {
                $query->where('expanses.admin_id', $request->admin_id);
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1]));
                $query->whereBetween('expanses.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $expenses = $query->select(
                    'expanses.*',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'admin_and_users.prefix as cr_prefix',
                    'admin_and_users.name as cr_name',
                    'admin_and_users.last_name as cr_last_name',
                )->orderBy('id', 'desc');
            } else {
                $expenses = $query->select(
                    'expanses.*',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'admin_and_users.prefix as cr_prefix',
                    'admin_and_users.name as cr_name',
                    'admin_and_users.last_name as cr_last_name',
                )->where('expanses.branch_id', auth()->user()->branch_id)
                    ->orderBy('id', 'desc');
            }

            return DataTables::of($expenses)
                ->editColumn('date', function ($row) use ($generalSettings) {
                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'].'(<b>HO</b>)';
                    }
                })
                ->editColumn('user_name',  function ($row) {
                    return $row->cr_prefix . ' ' . $row->cr_name . ' ' . $row->cr_last_name;
                })
                ->editColumn('payment_status',  function ($row) {
                    $html = "";
                    $payable = $row->net_total_amount;
                    if ($row->due <= 0) {
                        $html .= '<span class="badge bg-success">Paid</span>';
                    } elseif ($row->due > 0 && $row->due < $payable) {
                        $html .= '<span class="badge bg-primary text-white">Partial</span>';
                    } elseif ($payable == $row->due) {
                        $html .= '<span class="badge bg-danger text-white">Due</span>';
                    }
                    return $html;
                })
                ->editColumn('tax_percent',  function ($row) {
                    $tax_amount = $row->total_amount /100 * $row->tax_percent;
                    return '<b><span class="tax_amount" data-value="'.$tax_amount.'">'.$tax_amount.'('.$row->tax_percent.'%)</span></b>';
                })
                ->editColumn('net_total', function ($row) use ($generalSettings) {
                    return '<span class="net_total" data-value="'.$row->net_total_amount.'"><b>'.json_decode($generalSettings->business, true)['currency'] . $row->net_total_amount .'</b></span>';
                })
                ->editColumn('paid', function ($row) use ($generalSettings) {
                    return '<span class="paid" data-value="'.$row->paid.'"><b>'.json_decode($generalSettings->business, true)['currency'] . $row->paid.'</b></span>';
                })
                ->editColumn('due', function ($row) use ($generalSettings) {
                    $html = "";
                    $html .= '<span class="due" data-value="'.$row->due.'" class="text-danger"><strong>' .
                            json_decode($generalSettings->business, true)['currency'] . $row->due .
                            '</strong></span>';
                    return $html;
                })
                ->setRowClass('text-start')
                ->rawColumns(['action', 'date', 'from', 'user_name', 'payment_status', 'tax_percent', 'paid', 'due', 'net_total'])
                ->make(true);
        }
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.expense_report.index', compact('branches'));
    }

    public function print(Request $request)
    {
        $expenses = '';
        $branch_id = $request->branch_id;
        $fromDate = '';
        $toDate = '';
        $query = DB::table('expanses')
            ->leftJoin('branches', 'expanses.branch_id', 'branches.id')
            ->leftJoin('admin_and_users', 'expanses.admin_id', 'admin_and_users.id');

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $query->where('expanses.branch_id', NULL);
            } else {
                $query->where('expanses.branch_id', $request->branch_id);
            }
        }

        if ($request->admin_id) {
            $query->where('expanses.admin_id', $request->admin_id);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $fromDate = date('Y-m-d', strtotime($date_range[0]));
            $toDate = date('Y-m-d', strtotime($date_range[1]));
            $query->whereBetween('expanses.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $expenses = $query->select(
                'expanses.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'admin_and_users.prefix as cr_prefix',
                'admin_and_users.name as cr_name',
                'admin_and_users.last_name as cr_last_name',
            )->orderBy('id', 'desc')
                ->get();
        } else {
            $expenses = $query->select(
                'expanses.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'admin_and_users.prefix as cr_prefix',
                'admin_and_users.name as cr_name',
                'admin_and_users.last_name as cr_last_name',
            )->where('expanses.branch_id', auth()->user()->branch_id)
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('reports.expense_report.ajax_view.print', compact('expenses', 'fromDate', 'toDate', 'branch_id'));
    }

    public function getFilteredExpenseReport(Request $request)
    {
        if ($request->ex_category_id && $request->branch_id && $request->date_range) {
            $this->getExpenseReport();
        }

        $expenses = Expanse::orderBy('id', 'DESC');
        if ($request->branch_id) {
            $expenses->where('branch_id', $request->branch_id);
        }
    }
}