<?php

namespace App\Http\Controllers\Billing;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
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

        return view('billing.index', compact('branches', 'transactions'));
    }

    public function invoiceView($id)
    {
        $transaction = $this->subscriptionTransactionService->singleSubscriptionTransaction(id: $id, with: ['subscription', 'subscription.user', 'plan']);
        DB::reconnect();
        return view('billing.invoices.invoice_view', compact('transaction'));
    }

    public function invoicePdf($id)
    {
        $transaction = $this->subscriptionTransactionService->singleSubscriptionTransaction(id: $id, with: ['subscription', 'subscription.user', 'plan']);
        DB::reconnect();
        $pdf = Pdf::loadView('billing.pdf.transaction_details', compact('transaction'))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->stream('invoice.pdf');
    }
}
