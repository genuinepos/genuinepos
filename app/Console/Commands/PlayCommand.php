<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use RecursiveDirectoryIterator;
use SpeedDigit\Localization\Facades\Calculator;

class PlayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'play';

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
        $ri = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(app_path('Models')), \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($ri as $item) {
           echo $item->getRealPath() . '====' . $item->getFilename() . \PHP_EOL;
        }
        return Command::SUCCESS;
    }
}
