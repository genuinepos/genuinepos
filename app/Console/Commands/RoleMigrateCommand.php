<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class RoleMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rp:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update role and permission to latest version';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::debug('Initial');
        if (app()->runningInConsole()) {
            Log::debug('Start');
            Artisan::call('db:seed --class=RolePermissionSeeder');
            Log::debug('END');
        }
    }
}
