<?php

namespace App\Http\Controllers\Users\Reports;

use App\Enums\BooleanType;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Users\Reports\UserActivityLogReportService;
use App\Http\Requests\Users\Reports\UserActivityLogReportIndexRequest;

class UserActivityLogReportController extends Controller
{
    public function __construct(
        private UserActivityLogReportService $userActivityLogReportService,
        private BranchService $branchService,
        private UserService $userService
    ) {}

    public function index(UserActivityLogReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->userActivityLogReportService->userActivityLogReportTable(request: $request);
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
        } else if (!auth()->user()->can('user_activities_log_only_own_log')) {

            $users = $this->userService->users()->where('branch_id', auth()->user()->branch_id)->select('id', 'prefix', 'name', 'last_name')->get();
        }

        return view('users.reports.user_activity_log.index', compact('branches', 'users'));
    }
}
