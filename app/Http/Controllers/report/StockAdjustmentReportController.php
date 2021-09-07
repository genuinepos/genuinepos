<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class StockAdjustmentReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of Stock report
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('stock_adjustments');
            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('branch_id', NULL);
                } else {
                    $query->where('branch_id', $request->branch_id);
                }
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1]));
                $query->whereBetween('report_date_ts', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            }

            return $query->select(
                DB::raw('sum(net_total_amount) as t_amount'),
                DB::raw('sum(recovered_amount) as t_recovered_amount'),
                DB::raw("SUM(IF(type = '1', net_total_amount, 0)) as total_normal"),
                DB::raw("SUM(IF(type = '2', net_total_amount, 0)) as total_abnormal"),
            )->get();
        }

        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.adjustment_report.index', compact('branches'));
    }

    // All Stock Adjustment **requested by ajax**
    public function allAdjustments(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $adjustments = '';
            $query = DB::table('stock_adjustments')->leftJoin('branches', 'stock_adjustments.branch_id', 'branches.id')
                ->leftJoin('warehouses', 'stock_adjustments.warehouse_id', 'warehouses.id')
                ->leftJoin('admin_and_users', 'stock_adjustments.admin_id', 'admin_and_users.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('stock_adjustments.branch_id', NULL);
                } else {
                    $query->where('stock_adjustments.branch_id', $request->branch_id);
                }
            }

            if ($request->type) {
                $query->where('stock_adjustments.type', $request->type);
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1]));
                $query->whereBetween('stock_adjustments.report_date_ts', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $adjustments = $query->select(
                    'stock_adjustments.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'admin_and_users.prefix',
                    'admin_and_users.name',
                    'admin_and_users.last_name',
                )->orderBy('id', 'desc')->get();
            } else {
                $adjustments = $query->select(
                    'stock_adjustments.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'admin_and_users.prefix',
                    'admin_and_users.name as cr_name',
                    'admin_and_users.last_name',
                )->where('stock_adjustments.branch_id', auth()->user()->branch_id)
                    ->get();
            }

            return DataTables::of($adjustments)
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })->editColumn('from',  function ($row) use ($generalSettings) {
                    if (!$row->branch_name && !$row->warehouse_name) {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    } else {
                        if ($row->branch_name) {
                            return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                        } else {
                            return $row->warehouse_name . '/' . $row->warehouse_code . '(<b>WH</b>)';
                        }
                    }
                })->editColumn('type',  function ($row) {
                    return $row->type == 1 ? '<span class="badge bg-primary">Normal</span>' : '<span class="badge bg-danger">Abnormal</span>';
                })->editColumn('net_total', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->net_total_amount . '</b>';
                })->editColumn('recovered_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->recovered_amount . '</b>';
                })->editColumn('created_by', function ($row) {
                    return $row->prefix . ' ' . $row->name . ' ' . $row->last_name;
                })->rawColumns(['date', 'invoice_id', 'from', 'type', 'net_total', 'recovered_amount', 'created_by'])
                ->make(true);
        }
    }

    public function print(Request $request)
    {
        $branch_id = $request->branch_id;
        $fromDate = '';
        $toDate = '';
        $adjustments = '';
        $query = DB::table('stock_adjustments')
            ->leftJoin('branches', 'stock_adjustments.branch_id', 'branches.id')
            ->leftJoin('warehouses', 'stock_adjustments.warehouse_id', 'warehouses.id')
            ->leftJoin('admin_and_users', 'stock_adjustments.admin_id', 'admin_and_users.id');

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $query->where('stock_adjustments.branch_id', NULL);
            } else {
                $query->where('stock_adjustments.branch_id', $request->branch_id);
            }
        }

        if ($request->type) {
            $query->where('stock_adjustments.type', $request->type);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $fromDate = date('Y-m-d', strtotime($date_range[0]));
            $toDate = date('Y-m-d', strtotime($date_range[1]));
            $query->whereBetween('stock_adjustments.report_date_ts', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $adjustments = $query->select(
                'stock_adjustments.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'admin_and_users.prefix',
                'admin_and_users.name',
                'admin_and_users.last_name',
            )->orderBy('id', 'desc')->get();
        } else {
            $adjustments = $query->select(
                'stock_adjustments.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'admin_and_users.prefix',
                'admin_and_users.name as cr_name',
                'admin_and_users.last_name',
            )->where('stock_adjustments.branch_id', auth()->user()->branch_id)->get();
        }

        return view('reports.adjustment_report.ajax_view.print', compact('adjustments', 'branch_id', 'fromDate', 'toDate'));
    }
}
