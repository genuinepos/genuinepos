<?php

namespace App\Http\Controllers\Setups;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Illuminate\Support\Facades\Session;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Users\UserActivityLogService;
use App\Http\Requests\ChangeBusinessOrBranchLocation\RedirectLocationRequest;

class ChangeBusinessOrBranchLocationController extends Controller
{
    public function __construct(private BranchService $branchService, private UserService $userService, private UserActivityLogService $userActivityLogService)
    {
        $this->middleware('subscriptionRestrictions');
    }

    public function index()
    {
        if (
            !Session::get('chooseBusinessOrShop') &&
            auth()->user()->can('has_access_to_all_area') &&
            auth()->user()->is_belonging_an_area == BooleanType::False->value &&
            (
                config('generalSettings')['subscription']->has_business == BooleanType::True->value ||
                config('generalSettings')['subscription']->current_shop_count > 1
            )
        ) {

            $branches = $this->branchService->branches(with: ['parentBranch'])
                ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')
                ->get();

            return view('setups.choose_shop_or_business.index', compact('branches'));
        }else {

            return redirect()->route('dashboard.index');
        }
    }

    public function redirectLocation(RedirectLocationRequest $request)
    {
        $this->userService->changeBranch(request: $request);

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::UserLogin->value, subjectType: UserActivityLogSubjectType::UserLogin->value, dataObj: auth()->user());
        Session::put('chooseBusinessOrShop', 'yes');
        return redirect()->back();
    }
}
