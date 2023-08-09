<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FreshOtherModulesExceptMainApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fresh others';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Artisan::call('fresh-module HRM  -c hrm');
        Artisan::call('fresh-module CRM  -c crm');
        Artisan::call('fresh-module Website  -c website');
        Artisan::call('fresh-module Core');
    }
}
