<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Accounts\DayBookVoucherService;

class DayBookController extends Controller
{
    public function __construct(
        private DayBookVoucherService $dayBookVoucherService,
    ) {
    }

    function vouchersForReceiptOrPayment($accountId = null, $type = null)
    {
        $trans = $this->dayBookVoucherService->vouchersForPaymentReceipt(accountId: $accountId, type: $type);
        return $vouchers = $this->dayBookVoucherService->filteredVoucherForReceipt(vouchers: $trans);
    }
}
