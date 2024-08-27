<?php

use App\Models\User;
use App\Models\Branches\Branch;
use App\Models\GeneralSetting;
use App\Models\Products\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\AccountGroupSeeder;
use Illuminate\Database\Schema\Blueprint;

require_once base_path('deployment/db-migrations/001.php');

Artisan::command('dev:m', function () {

    $migration = DB::table('migrations')
        ->where('migration', '2023_01_02_113358_create_purchase_sale_product_chains_table')
        ->delete();

    $exists = DB::table('migrations')->where('migration', '2024_03_23_163909_create_stock_chains_table')->first();

    if (!$exists) {

        DB::table('migrations')->insert([
            'migration' => '2024_03_23_163909_create_stock_chains_table',
            'batch' => 6
        ]);
    }
});

Artisan::command('sync:gs', function () {

    $generalSettings = GeneralSetting::where('key', 'prefix__stock_issue_voucher_prefix')->where('branch_id', null)->first();

    if (!isset($generalSettings)) {

        $add = new GeneralSetting();
        $add->key = 'prefix__stock_issue_voucher_prefix';
        $add->value = 'STI';
        $add->save();
    }

    $branches = Branch::with('parentBranch', 'childBranches')->get();
    foreach ($branches as $key => $branch) {

        $generalSettings = GeneralSetting::where('key', 'prefix__stock_issue_voucher_prefix')
            ->where('branch_id', $branch->id)->first();

        if (!isset($generalSettings)) {

            $numberOfChildBranch = $branch?->parentBranch && count($branch?->parentBranch?->childBranches) > 0 ? count($branch->parentBranch->childBranches) : '';

            $branchName = $branch?->parentBranch ? $branch?->parentBranch->name : $branch->name;

            $exp = explode(' ', $branchName);

            $branchPrefix = '';
            foreach ($exp as $ex) {
                $str = str_split($ex);
                $branchPrefix .= $str[0];
            }

            $add = new GeneralSetting();
            $add->key = 'prefix__stock_issue_voucher_prefix';
            $add->value = $branchPrefix . $numberOfChildBranch . 'STI';
            $add->branch_id = $branch->id;
            $add->save();
        }
    }

    $deleteUnusedSettings = GeneralSetting::whereIn('key', [
        'addons__hrm',
        'addons__manage_task',
        'addons__service',
        'addons__manufacturing',
        'addons__e_commerce',
        'addons__branch_limit',
        'addons__cash_counter_limit',
    ])->get();

    foreach ($deleteUnusedSettings as $deleteUnusedSetting) {
        $deleteUnusedSetting->delete();
    }
});

Artisan::command('sync:table', function () {

    $products = Product::all();
    foreach ($products as $product) {
        $product->thumbnail_photo = null;
        $product->save();
    }

    $branches = Branch::all();
    foreach ($branches as $branch) {

        $branch->logo = null;
        $branch->save();
    }

    $users = User::all();
    foreach ($users as $user) {

        $user->photo = null;
        $user->save();
    }
});

// Artisan::command('dev:init', function () {
//     Artisan::call('db:seed --class=TenancyDatabaseSeeder');
// });

// Artisan::command('dev:seed', function () {
//     (new AccountGroupSeeder)->run();
// });

// Artisan::command('t:1', function () {
//     $s = GeneralSetting::where('parent_branch_id', auth()->user()?->branch_id)
//         ->orWhere('branch_id', auth()->user()?->branch_id)
//         ->orWhereNull('branch_id')
//         ->distinct('key')
//         ->pluck('value', 'key')
//         ->toArray();
//     dd($s);
// });

// Artisan::command('t:2', function () {

//     $generalSettings = GeneralSetting::where(function ($query) {
//         $query->whereNotNull('branch_id')
//             ->whereNotNull('parent_branch_id')
//             ->orderBy('branch_id')
//             ->orderBy('parent_branch_id');
//     })
//         ->orWhere(function ($query) {
//             $query->whereNotNull('branch_id')
//                 ->whereNull('parent_branch_id')
//                 ->orderBy('branch_id');
//         })
//         ->orWhere(function ($query) {
//             $query->whereNull('branch_id')
//                 ->whereNotNull('parent_branch_id')
//                 ->orderBy('parent_branch_id');
//         })
//         ->orWhere(function ($query) {
//             $query->whereNull('branch_id')
//                 ->whereNull('parent_branch_id')
//                 ->orderBy(DB::raw('RAND()')); // Random order for global settings
//         })
//         ->distinct('key')
//         ->pluck('value', 'key');
//     // ->get();

//     dd($generalSettings);
// });
