<?php

namespace Modules\SAAS\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Modules\SAAS\Entities\Tenant;

class BackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'saas:tenants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup all tenants DB & Project files (Except vendor and node_modules directories).';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('backup:run');
        Tenant::all()->runForEach(function () {
            Artisan::call('backup:run --only-db');
        });
    }
}
