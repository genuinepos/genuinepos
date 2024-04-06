<?php

namespace App\Http\Controllers\Setups;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Enums\BooleanType;
use App\Models\Sales\Sale;
use Illuminate\Http\Request;
use App\Models\Setups\Branch;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\SAAS\Entities\Plan;
use App\Models\Purchases\Purchase;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Facades\Tenancy;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use App\Enums\SubscriptionTransactionType;
use App\Models\Subscriptions\Subscription;
use App\Models\Subscriptions\ShopExpireDateHistory;
use App\Models\Subscriptions\SubscriptionTransaction;
use App\Services\Subscriptions\SubscriptionTransactionService;

class SoftwareServiceBillingController extends Controller
{
    public function __construct(
        private BranchService $branchService,
        private SubscriptionTransactionService $subscriptionTransactionService,
    ) {
    }

    public function index()
    {
        $branches = $this->branchService->branches(with: ['parentBranch', 'shopExpireDateHistory'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();
        $transactions = $this->subscriptionTransactionService->subscriptionTransactions()->latest()->get();

        return view('setups.billing.index', compact('branches', 'transactions'));
    }

    public function cartForRenewBranch()
    {
        return view('setups.billing.cart_for_branch_renew');
    }

    public function dueRepayment()
    {
        return view('setups.billing.due_repayment');
    }

    public function invoiceView($id)
    {
        $transaction = $this->invoiceQuery($id);

        return view('setups.invoices.invoice_view', compact('transaction'));
    }

    public function invoiceDownload($id)
    {
        $transaction = $this->invoiceQuery($id);
        // return view('setups.invoices.invoice_download', compact('transaction'));
        $pdf = Pdf::loadView('setups.invoices.invoice_download', compact('transaction'))->setOptions(['defaultFont' => 'sans-serif']);;
        return $pdf->download('invoice.pdf');
    }

    protected function invoiceQuery($id)
    {
        $transaction = SubscriptionTransaction::with('subscription', 'subscription.user', 'plan')->find($id);
        DB::reconnect();

        return $transaction;
    }

    private function getExpireDate(string $period, int $periodCount)
    {
        $today = new \DateTime();
        $lastDate = '';
        if ($period == 'day') {

            $lastDate = $today->modify('+' . $periodCount . ' days');
            $lastDate = $today->modify('+1 days');
        } elseif ($period == 'month') {

            $lastDate = $today->modify('+' . $periodCount . ' months');
            $lastDate = $today->modify('+1 days');
        } elseif ($period == 'year') {

            $lastDate = $today->modify('+' . $periodCount . ' years');
            $lastDate = $today->modify('+1 days');
        }

        // Format the date
        return $lastDate->format('Y-m-d');
    }
}
