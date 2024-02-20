<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateCommandRedirects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate redirect command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Eitehr run php artisan tenants:migrate Or, php artisan module:migrate SAAS');
    }
}
