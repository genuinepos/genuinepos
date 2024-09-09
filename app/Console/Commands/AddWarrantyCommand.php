<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Products\Warranty;
use Illuminate\Support\Facades\DB;
use App\Services\CodeGenerationService;

class AddWarrantyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:warranty';

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
        $codeGenerator = new CodeGenerationService;
        $warranties = array(
            array('id' => '1', 'name' => 'NO', 'duration' => '0', 'duration_type' => 'Days', 'description' => 'NO', 'type' => '1', 'created_at' => '2023-07-16 09:49:59', 'updated_at' => '2023-07-16 09:49:59')
        );

        foreach ($warranties as $warranty) {

            $exists = DB::table('warranties')->where('id', $warranty['id'])->first();
            if (!isset($exists)) {

                $code = $codeGenerator->warrantyCode();

                Warranty::insert([
                    'id' => $warranty['id'],
                    'code' => $code,
                    'name' => $warranty['name'],
                    'duration' => $warranty['duration'],
                    'duration_type' => $warranty['duration_type'],
                    'description' => $warranty['description'],
                    'type' => $warranty['type'],
                ]);
            }
        }
    }
}
