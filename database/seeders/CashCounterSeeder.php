<?php

namespace Database\Seeders;

use App\Models\CashCounter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CashCounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        if (CashCounter::count() == 0) {
            CashCounter::truncate();
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE cash_counters AUTO_INCREMENT=1');
        }
        CashCounter::insert([
            'branch_id' => auth()->user()?->branch_id ?? null,
            'counter_name' => 'Cash Counter 1',
            'short_name' => 'CN1',
        ]);
    }
}
