<?php

namespace App\Models\ShortMenus;

use App\Models\BaseModel;
use App\Models\ShortMenus\ShortMenu;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShortMenuUser extends BaseModel
{
    protected $hidden = ['is_delete_in_update', 'created_at', 'updated_at'];

    public function shortMenu()
    {
        return $this->belongsTo(ShortMenu::class, 'short_menu_id');
    }
}
