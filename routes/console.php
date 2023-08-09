<?php

use App\Models\Accounts\AccountGroup;
use App\Services\Setups\BranchService;
use Illuminate\Support\Facades\Artisan;

Artisan::command('dev:init', function () {
    Artisan::call('db:seed --class=TenancyDatabaseSeeder');
});


Artisan::command('dev:ag', function () {
    AccountGroup::where('branch_id', 1)->delete();
    // (new BranchService)->addBranchAccountGroups(1);
});
