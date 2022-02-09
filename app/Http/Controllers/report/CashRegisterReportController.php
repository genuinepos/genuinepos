<?php

namespace App\Http\Controllers\report;

use App\Models\CashRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CashRegisterReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of cash register report
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $settings = DB::table('general_settings')->select(['id', 'business'])->first();

            $cashRegisters = '';

            $query = DB::table('cash_registers')
                ->leftJoin('branches', 'cash_registers.branch_id', 'branches.id')
                ->leftJoin('admin_and_users', 'cash_registers.branch_id', 'branches.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('cash_registers.branch_id', NULL);
                } else {

                    $query->where('cash_registers.branch_id', $request->branch_id);
                }
            }

            if ($request->user_id) {

                $query->where('cash_registers.admin_id', $request->user_id);
            }

            if ($request->status) {

                if ($request->status == 1) {

                    $query->where('cash_registers.status', 1);
                } elseif ($request->status == 2) {

                    $query->where('cash_registers.status', 0);
                }
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('cash_registers.create_at', $date_range); // Final
            }

            $query->select(
                'cash_registers.*',
                'branches.name as b_name',
                'branches.branch_code as b_code',
                'admin_and_users.prefix as u_prefix',
                'admin_and_users.name as u_first_name',
                'admin_and_users.last_name as u_last_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $cashRegisters = $query->orderBy('cash_registers.created_at', 'desc');
            } else {

                $cashRegisters = $query->orderBy('cash_registers.created_at', 'desc')
                    ->where('cash_registers.branch_id', auth()->user()->branch_id);
            }

            return DataTables::of($cashRegisters)
                ->editColumn('open_time', function ($row) {

                    return Carbon::parse($row->created_at)->toFormattedDateString();
                })
                ->editColumn('closed_time', function ($row) {

                    if ($row->closed_at) {

                        return Carbon::createFromFormat('Y-m-d H:i:s', $cash_register->closed_at)->format('jS M, Y h:i A');
                    }
                })
                ->editColumn('branch',  function ($row) use ($settings) {

                    if ($row->b_name) {

                        return $row->b_name . '/' . $row->b_code . '(<b>BL</b>)';
                    } else {

                        return json_decode($settings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('user',  function ($row) {

                    return $row->u_prefix . ' ' . $row->u_first_name . ' ' . $row->u_last_name;
                })
                ->editColumn('status',  function ($row) {

                    return $row->status == 1 ? '<span class="badge bg-success">Open</span>' : '<span class="badge bg-danger">Closed</span>';
                })
                ->editColumn('closed_amount',  function ($row) {

                    return '<span class="closed_amount" data-value="' . $row->closed_amount . '">' . $this->converter->format_in_bdt($row->closed_amount) . '</span>';
                })
                ->rawColumns(['open_time', 'closed_time', 'branch', 'user', 'status', 'closed_amount'])
                ->make(true);
        }

        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.cash_register_report.index', compact('branches'));
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
            $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
            $query->whereBetween('created_at', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
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
