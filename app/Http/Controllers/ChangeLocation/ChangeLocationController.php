<?php

namespace App\Http\Controllers\ChangeLocation;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use Illuminate\Support\Facades\Session;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Users\UserActivityLogService;
use App\Http\Requests\ChangeLocation\ChangeLocationConfirmRequest;
use App\Http\Requests\ChangeBusinessOrBranchLocation\RedirectLocationRequest;

class ChangeLocationController extends Controller
{
    public function __construct(private BranchService $branchService, private UserService $userService, private UserActivityLogService $userActivityLogService) {}

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

            $branches = $this->branchService->switchableBranches();
            return view('change_location.index', compact('branches'));
        } else {

            return redirect()->route('dashboard.index');
        }
    }

    public function confirm(ChangeLocationConfirmRequest $request)
    {
        $this->userService->changeBranch(request: $request);

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::UserLogin->value, subjectType: UserActivityLogSubjectType::UserLogin->value, dataObj: auth()->user());
        Session::put('chooseBusinessOrShop', 'yes');
        return redirect()->back();
    }
}
