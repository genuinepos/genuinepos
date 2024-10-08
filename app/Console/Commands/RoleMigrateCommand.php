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
        if (app()->runningInConsole()) {

            // Artisan::call('db:seed --class=RolePermissionSeeder');
            Artisan::call('db:seed', [
                '--class' => 'RolePermissionSeeder',
                '--force' => true // Optional: if seeding needs to be forced
            ]);
        }
    }
}
