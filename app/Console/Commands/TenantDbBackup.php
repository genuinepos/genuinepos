<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TenantDbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:db-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup all tenants db.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get connection details
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $filename = 'db.sql';
        $path = "backups/";

        // Ensure the backup directory exists
        if (!file_exists(database_path($path))) {
            mkdir(database_path('backups'), 0755, true);
        }

        $backupPath = database_path("backups/" . $filename);

        $pdo = new \PDO("mysql:host={$host};dbname=information_schema", $username, $password);
        $stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM SCHEMATA WHERE SCHEMA_NAME = :database");
        $stmt->bindParam(':database', $database);
        $stmt->execute();
        $dbExists = $stmt->fetchColumn();

        if ($dbExists) {

            // Run the mysqldump command
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($database),
                escapeshellarg($backupPath)
            );

            $output = null;
            $resultCode = null;
            exec($command, $output, $resultCode);

            // if ($resultCode !== 0) {

            //     dd("Failed to backup database {$database}. Command: {$command}");
            // }

            if (config('file_disk.name') != 'local') {

                // Upload to S3
                // Storage::disk(config('file_disk.name'))->put($tenant->id . '/' . 'db/' . $filename, fopen($backupPath, 'r+'));
                Storage::disk(config('file_disk.name'))->put('central_db/' . $filename, file_get_contents($backupPath));

                // Clean up local dump file
                unlink($backupPath);
            }
        }

        $tenants = Tenant::select('id')->get();
        foreach ($tenants as $tenant) {

            $database = 'pos_' . $tenant->id;
            // $filename = 'pos_' . tenant('id') . '_' . date('Y_m_d') . '.sql';

            $pdo = new \PDO("mysql:host={$host};dbname=information_schema", $username, $password);
            $stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM SCHEMATA WHERE SCHEMA_NAME = :database");
            $stmt->bindParam(':database', $database);
            $stmt->execute();
            $dbExists = $stmt->fetchColumn();

            if ($dbExists) {

                // Run the mysqldump command
                $command = sprintf(
                    'mysqldump --user=%s --password=%s --host=%s %s > %s',
                    escapeshellarg($username),
                    escapeshellarg($password),
                    escapeshellarg($host),
                    escapeshellarg($database),
                    escapeshellarg($backupPath)
                );

                $output = null;
                $resultCode = null;
                exec($command, $output, $resultCode);

                // if ($resultCode !== 0) {

                //     dd("Failed to backup database {$database}. Command: {$command}");
                // }

                if (config('file_disk.name') != 'local') {

                    // Upload to S3
                    // Storage::disk(config('file_disk.name'))->put($tenant->id . '/' . 'db/' . $filename, fopen($backupPath, 'r+'));
                    Storage::disk(config('file_disk.name'))->put($tenant->id . '/' . 'db/' . $filename, file_get_contents($backupPath));

                    // Clean up local dump file
                    unlink($backupPath);
                }
            }
        }

        // dd("Backup for database {$database} completed successfully as {$filename}!");
    }
}
