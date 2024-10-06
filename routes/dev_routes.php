<?php

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Enums\ContactType;
use App\Models\Sales\Sale;
use App\Enums\PurchaseStatus;
use App\Models\Setups\Currency;
use App\Models\Contacts\Contact;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Models\Sales\CashRegister;

Route::get('my-test', function () {

    // $dir = __DIR__ . '/../resources/views';
    // $translationKeys = [];

    // function checkDir($dir, &$translationKeys)
    // {
    //     if (is_dir($dir)) {
    //         $files = scandir($dir);

    //         foreach ($files as $file) {
    //             if ($file != "." && $file != "..") {
    //                 $path = "$dir/$file";

    //                 if (is_dir($path)) {
    //                     checkDir($path, $translationKeys);
    //                 } else {
    //                     // Only process .blade.php files
    //                     if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
    //                         $content = file_get_contents($path);

    //                         // Use regular expression to match all {{ __('...') }} patterns
    //                         preg_match_all('/__\([\'"](.+?)[\'"]\)/', $content, $matches);

    //                         foreach ($matches[1] as $key) {
    //                             if (!in_array($key, $translationKeys)) {
    //                                 // Set the translation key
    //                                 $translationKeys[] = $key;
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // }

    // // Start the directory scan
    // checkDir($dir, $translationKeys);
    // return $translationKeys;

    // $translatedArray = [];

    // // return count($translationKeys) . '-' . count($translatedArray);

    // $c = array_combine($translationKeys, $translatedArray);

    // return $c;

    return config('app.app_domain');
});

Route::get('password', function () {

    return Hash::make(12345);
});

Route::get('t-id', function () {});
