<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExpenseReportCategoryWiseController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->user()->can('category_wise_expense')) {
            abort(403, 'Access Forbidden.');
        }
        if ($request->ajax()) {

            $generalSettings = config('generalSettings');
            $expenses = '';
            $query = DB::table('expense_descriptions')
                ->leftJoin('expanses', 'expense_descriptions.expense_id', 'expanses.id')
                ->leftJoin('expanse_categories', 'expense_descriptions.expense_category_id', 'expanse_categories.id')
                ->leftJoin('branches', 'expanses.branch_id', 'branches.id')
                ->leftJoin('users', 'expanses.admin_id', 'users.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('expanses.branch_id', null);
                } else {

                    $query->where('expanses.branch_id', $request->branch_id);
                }
            }

            if ($request->admin_id) {

                $query->where('expanses.admin_id', $request->admin_id);
            }

            if ($request->category_id) {

                $query->where('expense_descriptions.expense_category_id', $request->category_id);
            }

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('expanses.report_date', $date_range); // Final
            }

            $query->select(
                'expense_descriptions.amount',
                'expanses.invoice_id',
                'expanses.date',
                'expanse_categories.name',
                'expanse_categories.code',
                'branches.name as branch_name',
                'branches.branch_code',
                'users.prefix as cr_prefix',
                'users.name as cr_name',
                'users.last_name as cr_last_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            } else {

                $expenses = $query->where('expanses.branch_id', auth()->user()->branch_id);
            }

            $expenses = $query->orderBy('expanses.report_date', 'desc');

            return DataTables::of($expenses)
                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date($generalSettings['business__date_format'], strtotime($row->date));
                })->editColumn('from', function ($row) use ($generalSettings) {

                    if ($row->branch_name) {

                        return $row->branch_name.'/'.$row->branch_code.'(<b>B.L.</b>)';
                    } else {

                        return $generalSettings['business__shop_name'].'(<b>HO</b>)';
                    }
                })->editColumn('category_name', function ($row) {

                    return $row->name.' ('.$row->code.')';
                })->editColumn('user_name', function ($row) {

                    if ($row->cr_name) {

                        return $row->cr_prefix.' '.$row->cr_name.' '.$row->cr_last_name;
                    } else {

                        return '---';
                    }
                })->editColumn('amount', fn ($row) => '<span class="amount" data-value="'.$row->amount.'">'.\App\Utils\Converter::format_in_bdt($row->amount).'</span>')
                ->rawColumns(['date', 'from', 'category_name', 'user_name', 'amount'])
                ->make(true);
        }

        $expenseCategories = DB::table('expanse_categories')->select('id', 'name', 'code')->get();
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();

        return view('reports.expense_category_wise_report.index', compact('branches', 'expenseCategories'));
    }

    public function print(Request $request)
    {
        $expenses = '';
        $branch_id = $request->branch_id;
        $fromDate = '';
        $toDate = '';

        $expenses = '';
        $query = DB::table('expense_descriptions')
            ->leftJoin('expanses', 'expense_descriptions.expense_id', 'expanses.id')
            ->leftJoin('expanse_categories', 'expense_descriptions.expense_category_id', 'expanse_categories.id')
            ->leftJoin('branches', 'expanses.branch_id', 'branches.id')
            ->leftJoin('users', 'expanses.admin_id', 'users.id');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('expanses.branch_id', null);
            } else {

                $query->where('expanses.branch_id', $request->branch_id);
            }
        }

        if ($request->admin_id) {

            $query->where('expanses.admin_id', $request->admin_id);
        }

        if ($request->category_id) {

            $query->where('expense_descriptions.expense_category_id', $request->category_id);
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('expanses.report_date', $date_range); // Final
        }

        $query->select(
            'expense_descriptions.expense_category_id as category_id',
            'expense_descriptions.amount',
            'expanses.invoice_id',
            'expanses.date',
            'expanses.report_date',
            'expanse_categories.name as category_name',
            'expanse_categories.code as category_code',
            'branches.name as branch_name',
            'branches.branch_code',
            'users.prefix as cr_prefix',
            'users.name as cr_name',
            'users.last_name as cr_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

        } else {

            $expenses = $query->where('expanses.branch_id', auth()->user()->branch_id);
        }

        $expenses = $query->orderBy('expense_descriptions.expense_category_id', 'asc')->orderBy('expanses.report_date', 'desc')->get();

        $count = count($expenses);
        $veryLastCategoryId = $count > 0 ? $expenses->last()->category_id : '';
        $lastRow = $count - 1;

        return view(
            'reports.expense_category_wise_report.ajax_view.print',
            compact('expenses', 'fromDate', 'toDate', 'branch_id', 'count', 'veryLastCategoryId', 'lastRow')
        );
    }
}
