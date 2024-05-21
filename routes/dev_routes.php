<?php


use Carbon\Carbon;
use App\Enums\RoleType;
use App\Enums\BooleanType;
use App\Models\Setups\Branch;
use App\Models\GeneralSetting;
use App\Models\Accounts\Account;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Facades\Tenancy;
use App\Enums\AccountingVoucherType;
use App\Models\ShortMenus\ShortMenu;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Models\Accounts\AccountLedger;
use Illuminate\Support\Facades\Schema;
use App\Enums\AccountLedgerVoucherType;
use Illuminate\Support\Facades\Session;
use App\Models\TransferStocks\TransferStock;
use App\Models\Accounts\AccountingVoucherDescription;

Route::get('my-test', function () {
    return;
});

Route::get('t-id', function () {
});
