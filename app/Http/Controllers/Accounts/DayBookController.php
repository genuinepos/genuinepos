<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Services\Accounts\DayBookVoucherService;

class DayBookController extends Controller
{
    public function __construct(private DayBookVoucherService $dayBookVoucherService)
    {
    }

    public function vouchersForReceiptOrPayment($accountId = null, $type = null)
    {
        $trans = $this->dayBookVoucherService->vouchersForPaymentReceipt(accountId: $accountId, type: $type);

        return $vouchers = $this->dayBookVoucherService->filteredVoucher(vouchers: $trans);
    }
}
