<?php

namespace App\Http\Controllers\Report;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
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
                ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
                ->leftJoin('users', 'user_activity_logs.user_id', 'users.id');

            $query->select(
                'user_activity_logs.id',
                'user_activity_logs.branch_id',
                'user_activity_logs.date',
                'user_activity_logs.report_date',
                'user_activity_logs.action',
                'user_activity_logs.subject_type',
                'user_activity_logs.descriptions',
                'branches.name as branch_name',
                'branches.area_name as branch_area_name',
                'branches.branch_code',
                'parentBranch.name as parent_branch_name',
                'users.prefix as u_prefix',
                'users.name as u_name',
                'users.last_name as u_last_name',
            );

            if (
                auth()->user()->can('has_access_to_all_area') &&
                config('generalSettings')['subscription']->has_business == BooleanType::True->value &&
                auth()->user()->is_belonging_an_area == BooleanType::False->value &&
                !auth()->user()->can('user_activities_log_only_own_log')
            ) {

                $query;
            }else if(!auth()->user()->can('user_activities_log_only_own_log')) {

                $query->where('user_activity_logs.branch_id', auth()->user()->branch_id);
            }else if(auth()->user()->can('user_activities_log_only_own_log')) {

                $query->where('user_activity_logs.user_id', auth()->user()->id);
            }

            $this->filteredQuery($request, $query);

            $logs = $query->orderBy('user_activity_logs.report_date', 'desc');

            $generalSettings = config('generalSettings');

            return DataTables::of($logs)
                ->editColumn('date', function ($row) use ($generalSettings) {

                    $dateFormat = $generalSettings['business_or_shop__date_format'];

                    return date($dateFormat . ' h:i:s a', strtotime($row->report_date));
                })
                ->editColumn('branch', function ($row) use ($generalSettings) {
                    if ($row->branch_id) {

                        if ($row->parent_branch_name) {

                            return $row->parent_branch_name . '(' . $row->branch_area_name . ')';
                        } else {

                            return $row->branch_name . '(' . $row->branch_area_name . ')';
                        }
                    } else {

                        return $generalSettings['business_or_shop__business_name'];
                    }
                })
                ->editColumn('action_by', fn ($row) => $row->u_prefix . ' ' . $row->u_name . ' ' . $row->u_last_name)

                ->editColumn('action', function ($row) use ($actions) {

                    if (UserActivityLogActionType::tryFrom($row->action)->name == 'Deleted') {

                        return '<strong class="text-danger">' . __('Deleted') . '</strong>';
                    } elseif (UserActivityLogActionType::tryFrom($row->action)->name == 'Added') {

                        return '<strong class="text-success">' . __('Added') . '</strong>';
                    } elseif (UserActivityLogActionType::tryFrom($row->action)->name == 'Updated') {

                        return '<strong class="text_color_updated">' . __('Updated') . '</strong>';
                    } elseif (UserActivityLogActionType::tryFrom($row->action)->name == 'User Login') {

                        return '<strong class="text-success">' . __('User Login') . '</strong>';
                    } elseif (UserActivityLogActionType::tryFrom($row->action)->name == 'User Logout') {

                        return '<strong class="text-danger">' . __('User Logout') . '</strong>';
                    }
                })

                ->editColumn('subject_type', function ($row) {

                    return UserActivityLogSubjectType::tryFrom($row->subject_type)->name;
                })

                ->editColumn('descriptions', function ($row) {

                    return $row->descriptions;
                })

                ->rawColumns(['date', 'branch', 'action_by', 'action', 'subject_type', 'descriptions'])
                ->make(true);
        }

        $branches = null;
        $users = null;
        if (
            auth()->user()->can('has_access_to_all_area') &&
            config('generalSettings')['subscription']->has_business == BooleanType::True->value &&
            auth()->user()->is_belonging_an_area == BooleanType::False->value &&
            !auth()->user()->can('user_activities_log_only_own_log')
        ) {

            $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();
            $users = $this->userService->users()->select('id', 'prefix', 'name', 'last_name')->get();
        }else if(!auth()->user()->can('user_activities_log_only_own_log')) {

            $users = $this->userService->users()->where('branch_id', auth()->user()->branch_id)->select('id', 'prefix', 'name', 'last_name')->get();
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
