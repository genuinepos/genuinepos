<?php

namespace Modules\SAAS\Scope;

trait IsActive
{
    public function scopeIsActive($query)
    {
        return $query->where('status', 1);
    }
}
