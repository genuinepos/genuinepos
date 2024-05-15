<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Enums\BillingPanelUserType;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\SAAS\Interfaces\UserServiceInterface;
use Modules\SAAS\Interfaces\UserSubscriptionTransactionServiceInterface;

class UserSubscriptionTransactionController extends Controller
{
    public function __construct(
        private UserServiceInterface $userServiceInterface,
        private UserSubscriptionTransactionServiceInterface $userSubscriptionTransactionServiceInterface,
    ) {
    }

    public function index(Request $request, $userId = null)
    {
        if ($request->ajax()) {

            return $this->userSubscriptionTransactionServiceInterface->subscriptionTransactionsTable(request: $request, userId: $userId);
        }

        $users = $this->userServiceInterface->users(with: ['tenant'])->where('user_type', BillingPanelUserType::Subscriber->value)->get(['id', 'name', 'tenant_id']);

        return view('saas::tenants.subscription_transactions.index', compact('users'));
    }

    public function pdfDetails($id)
    {
        $transaction = $this->userSubscriptionTransactionServiceInterface->singleUserSubscriptionTransaction(id: $id, with: ['subscription', 'subscription.user', 'plan'])->first();

        $pdf = Pdf::loadView('saas::tenants.subscription_transactions.pdf.transaction_details', compact('transaction'))->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->stream('invoice.pdf');
    }
}
