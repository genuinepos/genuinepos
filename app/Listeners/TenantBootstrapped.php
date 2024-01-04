<?php

namespace App\Listeners;

use Exception;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class TenantBootstrapped
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {

    }
}
