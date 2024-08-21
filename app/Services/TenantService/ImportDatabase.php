<?php

declare(strict_types=1);

namespace App\Services\TenantService;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Stancl\Tenancy\Events\CreatingDatabase;
use Stancl\Tenancy\Database\DatabaseManager;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class ImportDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var TenantWithDatabase|Model */
    protected $tenant;

    public function __construct(TenantWithDatabase $tenant)
    {
        $this->tenant = $tenant;
    }

    public function handle()
    {
        $params = [
            'file' => database_path('db/tenant.sql'),
            'dbname' => 'pos_' . $this->tenant->id,
        ];

        Artisan::call('import:sql', $params);
    }
}
