<?php

namespace App\Services\ShortMenus;

use App\Models\ShortMenus\ShortMenu;

class ShortMenuService
{
    public function shortMenus(array $with): ?object
    {
        $query = ShortMenu::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
