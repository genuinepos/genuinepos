<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
        Schema::table('products', function (Blueprint $table) {
            // $table->renameColumn('sub_category_id', 'parent_category_id');
            $table->renameIndex('products_sub_category_id_foreign', 'products_parent11_category_id_foreign');
            // $table->foreign('sub_category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }
}
