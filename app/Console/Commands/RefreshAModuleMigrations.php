<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RefreshAModuleMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fresh-module {module} {--s|seed=no} {--c|connection=mysql}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('module');
        $connection = $this->option('connection');
        $withSeed = $this->option('seed') === 'yes';

        $migrationDir = \base_path('Modules'.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR.'Database'.DIRECTORY_SEPARATOR.'Migrations');
        $entityDir = \base_path('Modules'.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR.'Entities');

        $migrationArray = $entityArray = [];

        foreach (new \DirectoryIterator($migrationDir) as $fileinfo) {
            if ($fileinfo->isFile()) {
                $migrationArray[] = $fileinfo->getBasename('.php');
            }
        }
        foreach (new \DirectoryIterator($entityDir) as $fileinfo) {
            if ($fileinfo->isFile()) {
                $entityName = $fileinfo->getBasename('.php');
                $class = "\Modules\\$name\Entities\\$entityName";
                $table = (new $class())->getTable();
                $entityArray[] = $table;
            }
        }

        Schema::connection($connection)->disableForeignKeyConstraints();
        $result = DB::connection('mysql')->table('migrations')->whereIn('migration', $migrationArray)->delete();

        foreach ($entityArray as $table) {
            Schema::connection($connection)->dropIfExists($table);
        }
        Artisan::call('module:migrate '.$name);
        if ($withSeed) {
            Artisan::call('module:seed '.$name);
        }
        Schema::connection($connection)->enableForeignKeyConstraints();
    }
}
