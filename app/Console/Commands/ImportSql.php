<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\File;

class ImportSql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sql {file} {dbname} {--username=} {--password=} {--host=} {--port=} {--force=}';
    // protected $signature = 'import:sql';
    protected $description = 'Import database for tenant creation.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $file = database_path('db/tenant.sql');
        // $dbname = 'tenant_import_test';
        // $username = env('DB_USERNAME');
        // $password = env('DB_PASSWORD');
        // $host = env('DB_HOST');
        // $port = env('DB_PORT');
        // Log::info("DB Import Start");
        $file = $this->argument('file');
        $dbname = $this->argument('dbname');
        $username = $this->option('username') ?? config('database.connections.mysql.username');
        $password = $this->option('password') ?? config('database.connections.mysql.password');
        $host = $this->option('host') ?? config('database.connections.mysql.host');
        $port = $this->option('port') ?? config('database.connections.mysql.port');

        if (!File::exists($file)) {
            $this->error("The file {$file} does not exist.");
            return;
        }

        config([
            'database.connections.custom' => [
                'driver' => 'mysql',
                'host' => $host,
                'port' => $port,
                'database' => $dbname,
                'username' => $username,
                'password' => $password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ]
        ]);

        try {
            DB::purge('custom'); // Purge the custom connection to make sure no old configuration is used.
            DB::reconnect('custom'); // Reconnect with the new configuration.

            // Disable foreign key checks
            DB::connection('custom')->unprepared('SET FOREIGN_KEY_CHECKS=0;');

            $sql = File::get($file);
            DB::connection('custom')->unprepared($sql);

            // Enable foreign key checks
            DB::connection('custom')->unprepared('SET FOREIGN_KEY_CHECKS=1;');

            // $this->info("The file {$file} has been successfully imported into the database {$dbname}.");
            // Log::info("The file {$file} has been successfully imported into the database {$dbname}.");
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
        }
    }
}
