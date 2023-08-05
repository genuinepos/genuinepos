<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ModuleModelShow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mm:show {model} {module?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Module model show';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->argument('model');
        $module = $this->argument('module') ?? 'HRM';
        // Artisan::call("model:show \Modules\{$module}\Entities\{$model}");
        Artisan::call('model:show User');
    }
}
