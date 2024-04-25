<?php

namespace App\Models\ShortMenus;

use App\Models\BaseModel;
use App\Enums\ShortMenuScreenType;
use App\Models\ShortMenus\ShortMenuUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShortMenu extends BaseModel
{
    use HasFactory;

    public function userMenuForDashboard()
    {
        return $this->hasOne(ShortMenuUser::class, 'short_menu_id')
            ->where('user_id', auth()->user()->id)
            ->where('screen_type', ShortMenuScreenType::DashboardScreen->value);
    }

    public function userMenuForPos()
    {
        return $this->hasOne(ShortMenuUser::class, 'short_menu_id')
            ->where('user_id', auth()->user()->id)
            ->where('screen_type', ShortMenuScreenType::PosScreen->value);
    }
}
