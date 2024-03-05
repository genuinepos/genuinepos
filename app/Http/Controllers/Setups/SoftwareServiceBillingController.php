<?php

namespace App\Http\Controllers\Setups;

use App\Enums\SubscriptionTransactionType;
use App\Http\Controllers\Controller;
use App\Models\Setups\Branch;
use App\Models\Subscriptions\Subscription;
use App\Models\Subscriptions\SubscriptionTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SAAS\Entities\Plan;
use Barryvdh\DomPDF\Facade\Pdf;

class SoftwareServiceBillingController extends Controller
{
    public function index()
    {
        $shops = Branch::all();
        $subscriptionHistory = SubscriptionTransaction::latest()->get();

        return view('setups.billing.index', compact('shops', 'subscriptionHistory'));
    }

    function upgradablePlansForTrial()
    {
    }

    public function upgradePlan()
    {
        DB::statement('use ' . env('DB_DATABASE'));
        $plans = Plan::active()->where('is_trial_plan', 0)->get();
        DB::reconnect();

        if (config('generalSettings')['subscription']->is_trial_plan == 1) {

            return view('setups.billing.upgrade_plan_from_trial.plans', compact('plans'));
        } else {

            return view('setups.billing.upgrade_plan', compact('plans'));
        }
    }

    public function cartFoUpgradePlan($id)
    {
        DB::statement('use ' . env('DB_DATABASE'));
        $plan = Plan::findOrFail($id);
        DB::reconnect();

        $currentSubscription = Subscription::with('plan')->first();
        if (config('generalSettings')['subscription']->is_trial_plan == 1) {

            return view('setups.billing.upgrade_plan_from_trial.cart', compact('plan', 'currentSubscription'));
        }else {

            return view('setups.billing.cart_for_upgrade_plan', compact('plan', 'currentSubscription'));
        }
    }

    public function processUpgradePlan(Request $request, $id)
    {
        DB::statement('use ' . env('DB_DATABASE'));
        $plan = Plan::findOrFail($id);
        DB::reconnect();

        DB::beginTransaction();

        try {
            $subscription = Subscription::first();

            $subscription->trial_start_date = null;
            $subscription->plan_id = $id;
            $subscription->initial_subtotal = $plan->price_per_year;
            $subscription->initial_plan_start_date = now();
            $subscription->initial_plan_expire_date = null;
            $subscription->save();

            $transaction = new SubscriptionTransaction();
            $transaction->subscription_id = $subscription->id;
            $transaction->plan_id = $plan->id;
            $transaction->transaction_type = SubscriptionTransactionType::UpgradePlan->value;
            $transaction->payment_method_provider_name = $request->payment_method_name;
            $transaction->payment_method_name = $request->payment_method_name;
            $transaction->subtotal = $plan->price_per_year;
            $transaction->total_payable_amount = $plan->price_per_year;
            $transaction->paid = $plan->price_per_year;
            $transaction->payment_status = 1;
            $transaction->payment_date = now();

            $transaction->save();

            $user = auth()->user();

            DB::commit();

            dispatch(new \Modules\SAAS\Jobs\SendSubscriptionUpgradeMailQueueJob(to: $user->email, user: $user));

            return response()->json(['success' => true, 'message' => 'Subscription upgrade successfully']);
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function cartFoAddBranch()
    {

        return view('setups.billing.cart_for_add_branch');
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
}
