<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
        $x = Calculator::add(100)->multiply(3)->getResult();
        dd($x);
        return Command::SUCCESS;
    }
}
