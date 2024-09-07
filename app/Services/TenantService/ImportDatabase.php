<?php

declare(strict_types=1);

namespace App\Services\TenantService;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class ImportDatabase
{
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
            '--force' => true
        ];

        // Artisan::call('import:sql', $params);

        try {

            Artisan::call('import:sql', $params);
        } catch (\Exception $e) {

            Log::error('Error during SQL import for tenant: ' . $this->tenant->id . '. Error: ' . $e->getMessage());
        }
    }
}
