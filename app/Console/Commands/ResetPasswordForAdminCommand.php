<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetPasswordForAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset admin command to password';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!config('app.debug')) {
            $this->error('This command is not available on production environment');
            exit(1);
        }

        $newPassword = 'password';

        $firstAdmin = \App\Models\User::role('superadmin')->first();
        if (isset($firstAdmin)) {
            $firstAdmin->password = bcrypt($newPassword);
            $firstAdmin->save();
            echo "Username: {$firstAdmin->username}\nPassword: {$newPassword}\n";
            $this->info('Reset succeed!');
        }

        return Command::SUCCESS;
    }
}
