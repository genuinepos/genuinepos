<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PDOException;

class DbWipeAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:restart';

    /**
     * The console Command to wipse all database added to .env file..
     *
     * @var string
     */
    protected $description = 'Command to wipse all database added to .env file.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            Artisan::call('db:wipe');
            DB::statement('drop database if exists '.config('database.connections.hrm.database'));
            DB::statement('drop database if exists '.config('database.connections.crm.database'));
            DB::statement('drop database if exists '.config('database.connections.website.database'));

            DB::statement('create database if not exists '.config('database.connections.hrm.database'));
            DB::statement('create database if not exists '.config('database.connections.crm.database'));
            DB::statement('create database if not exists '.config('database.connections.website.database'));

            echo 'Done!'.PHP_EOL;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
