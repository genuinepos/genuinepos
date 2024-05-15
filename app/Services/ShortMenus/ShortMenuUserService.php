<?php

namespace App\Services\ShortMenus;

use App\Enums\BooleanType;
use App\Models\ShortMenus\ShortMenuUser;

class ShortMenuUserService
{
    public function shortMenuUsers(?array $with = null): ?object
    {
        $query = ShortMenuUser::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function addOrUpdateShortMenuUser(object $request, int $screenType): void
    {
        $userShortMenus = $this->shortMenuUsers()->where('user_id', auth()->user()->id)->where('screen_type', $screenType)->get();

        foreach ($userShortMenus as $userShortMenu) {

            $userShortMenu->is_delete_in_update = BooleanType::True->value;
            $userShortMenu->save();
        }

        if (isset($request->menu_ids)) {

            foreach ($request->menu_ids as $menuId) {

                $addOrUpdateShortMenuUser = null;

                $shortMenuUser = $this->shortMenuUsers()->where('short_menu_id', $menuId)
                    ->where('user_id', auth()->user()->id)
                    ->where('screen_type', $screenType)
                    ->first();

                if ($shortMenuUser) {

                    $addOrUpdateShortMenuUser = $shortMenuUser;
                } else {

                    $addOrUpdateShortMenuUser = new ShortMenuUser();
                }

                $addOrUpdateShortMenuUser->short_menu_id = $menuId;
                $addOrUpdateShortMenuUser->user_id = auth()->user()->id;
                $addOrUpdateShortMenuUser->screen_type = $screenType;
                $addOrUpdateShortMenuUser->is_delete_in_update = BooleanType::False->value;
                $addOrUpdateShortMenuUser->save();
            }
        }

        $unusedShortMenus = $this->shortMenuUsers()
            ->where('user_id', auth()->user()->id)
            ->where('screen_type', $screenType)
            ->where('is_delete_in_update', BooleanType::True->value)->get();

        foreach ($unusedShortMenus as $unusedShortMenu) {

            $unusedShortMenu->delete();
        }
    }
}
