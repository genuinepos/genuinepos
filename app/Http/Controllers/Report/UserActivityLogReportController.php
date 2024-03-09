<?php

namespace App\Http\Controllers\Report;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Yajra\DataTables\Facades\DataTables;

class UserActivityLogReportController extends Controller
{
    public function __construct(
        private BranchService $branchService,
        private UserService $userService,
        private UserActivityLogUtil $userActivityLogUtil
    ) {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $actions = $this->userActivityLogUtil->actions();
            $subject_types = $this->userActivityLogUtil->subjectTypes();
            $logs = '';
            $query = DB::table('user_activity_logs')
                ->leftJoin('branches', 'user_activity_logs.branch_id', 'branches.id')
                ->leftJoin('users', 'user_activity_logs.user_id', 'users.id');

            $query->select(
                'user_activity_logs.id',
                'user_activity_logs.date',
                'user_activity_logs.report_date',
                'user_activity_logs.action',
                'user_activity_logs.subject_type',
                'user_activity_logs.descriptions',
                'branches.name as branch_name',
                'branches.branch_code',
                'users.prefix as u_prefix',
                'users.name as u_name',
                'users.last_name as u_last_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $logs = $this->filteredQuery($request, $query)
                    ->orderBy('user_activity_logs.report_date', 'desc');
            } else {

                $logs = $this->filteredQuery($request, $query)
                    ->where('user_activity_logs.branch_id', auth()->user()->branch_id)
                    ->orderBy('user_activity_logs.report_date', 'desc');
            }
            $generalSettings = config('generalSettings');

            return DataTables::of($logs)
                ->editColumn('date', function ($row) use ($generalSettings) {
                    $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                    return date($__date_format . ' h:i:s a', strtotime($row->report_date));
                })
                ->editColumn('branch', function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                    } else {
                        return $generalSettings['business_or_shop__business_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('action_by', fn ($row) => $row->u_prefix . ' ' . $row->u_name . ' ' . $row->u_last_name)

                ->editColumn('action', function ($row) use ($actions) {

                    if ($actions[$row->action] == 'Deleted') {

                        return '<strong class="text-danger">' . $actions[$row->action] . '</strong>';
                    } elseif ($actions[$row->action] == 'Added') {

                        return '<strong class="text-success">' . $actions[$row->action] . '</strong>';
                    } elseif ($actions[$row->action] == 'Updated') {

                        return '<strong class="text_color_updated">' . $actions[$row->action] . '</strong>';
                    } elseif ($actions[$row->action] == 'User Login') {

                        return '<strong class="text-success">' . $actions[$row->action] . '</strong>';
                    } elseif ($actions[$row->action] == 'User Logout') {

                        return '<strong class="text-danger">' . $actions[$row->action] . '</strong>';
                    }

                    return $actions[$row->action];
                })

                ->editColumn('subject_type', function ($row) use ($subject_types) {

                    return $subject_types[$row->subject_type];
                })

                ->editColumn('descriptions', function ($row) {

                    return $row->descriptions;
                })

                ->rawColumns(['date', 'branch', 'action_by', 'action', 'subject_type', 'descriptions'])
                ->make(true);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $users = null;
        if (auth()->user()->can('has_access_to_all_area') && config('generalSettings')['subscription']->current_shop_count > 0) {

            $users = $this->userService->users()->select('id', 'prefix', 'name', 'last_name')->get();
        }

        return view('reports.user_activity_log.index', compact('branches', 'users'));
    }

    private function filteredQuery($request, $query)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('user_activity_logs.branch_id', null);
            } else {

                $query->where('user_activity_logs.branch_id', $request->branch_id);
            }
        }

        if ($request->user_id) {

            $query->where('user_activity_logs.user_id', $request->user_id);
        }

        if ($request->action) {

            $query->where('user_activity_logs.action', $request->action);
        }

        if ($request->subject_type) {

            $query->where('user_activity_logs.subject_type', $request->subject_type);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('user_activity_logs.report_date', $date_range); // Final
        }

        return $query;
    }
}
