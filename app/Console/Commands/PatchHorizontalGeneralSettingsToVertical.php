<?php

namespace App\Console\Commands;

use Database\Seeders\HorizontalToVerticalConversionSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PatchHorizontalGeneralSettingsToVertical extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:001';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Horizontal to vertical conversion';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Artisan::call('db:seed --class="HorizontalToVerticalConversionSeeder"');
        Artisan::call('db:seed --class="GeneralSettingsSeeder"');

        return Command::SUCCESS;
    }
}
