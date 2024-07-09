<?php

namespace App\Http\Controllers\ShortMenus;

use Illuminate\Http\Request;
use App\Enums\ShortMenuScreenType;
use App\Http\Controllers\Controller;
use App\Models\ShortMenus\ShortMenuUser;
use App\Services\ShortMenus\ShortMenuService;
use App\Services\ShortMenus\ShortMenuUserService;

class ShortMenuController extends Controller
{
    public function __construct(
        private ShortMenuService $shortMenuService,
        private ShortMenuUserService $shortMenuUserService,
    ) {
    }

    public function showModalForm($screenType)
    {
        $userMenu = $screenType == ShortMenuScreenType::DashboardScreen->value ? 'userMenuForDashboard' : 'userMenuForPos';
         $shortMenus = $this->shortMenuService->shortMenus(with: [$userMenu])->get();
        return view('short_menus.short_menus_add_modal_form', compact('shortMenus', 'screenType'));
    }

    public function show($screenType)
    {
        $shortMenuUsers = $this->shortMenuUserService->shortMenuUsers(with: ['shortMenu'])
            ->where('screen_type', $screenType)->where('user_id', auth()->user()->id)->get();

        if ($screenType == ShortMenuScreenType::DashboardScreen->value) {

            return view('short_menus.short_menus_dashboard_card', compact('shortMenuUsers'));
        }else {

            return view('short_menus.pos_shortcut_menus', compact('shortMenuUsers'));
        }
    }

    public function store($screenType, Request $request)
    {
        $this->shortMenuUserService->addOrUpdateShortMenuUser(request: $request, screenType: $screenType);
        return response()->json(__('Updated successfully.'));
    }
}
