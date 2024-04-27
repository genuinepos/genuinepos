<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Communication\Email\SendEmail;

use Carbon\Carbon;

class DeleteEmailList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-email-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenDaysAgo = Carbon::now()->subDays(10);

        SendEmail::where('created_at', '<', $tenDaysAgo)->delete();

        $this->info('Old records deleted successfully.');
    }
}
