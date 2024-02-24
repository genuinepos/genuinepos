<?php

namespace App\Http\Controllers\Setups;

use App\Http\Controllers\Controller;
use App\Models\Setups\Branch;
use App\Models\Subscriptions\Subscription;
use App\Models\Subscriptions\SubscriptionTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SAAS\Entities\Plan;

class SoftwareServiceBillingController extends Controller
{
    public function index()
    {
        $currentSubscription = Subscription::with('plan')->find(1);
        DB::reconnect();

        $shops = Branch::all();

        $subscriptionHistory = SubscriptionTransaction::latest()->get();

        return view('setups.billing.index', compact('currentSubscription', 'shops', 'subscriptionHistory'));
    }

    public function upgradePlan() {
        DB::statement('use ' . env('DB_DATABASE'));
        $plans = Plan::all();

        DB::reconnect();

        return view('setups.billing.upgrade_plan', compact('plans'));
    }

    public function cartFoUpgradePlan($id)
    {
        DB::statement('use ' . env('DB_DATABASE'));
        $plan = Plan::findOrFail($id);

        DB::reconnect();

        $currentSubscription = Subscription::with('plan')->find(1);

        return view('setups.billing.cart_for_upgrade_plan', compact('plan', 'currentSubscription'));
    }

    public function processUpgradePlan(Request $request, $id)
    {
        DB::statement('use ' . env('DB_DATABASE'));
        $plan = Plan::findOrFail($id);
        DB::reconnect();

        $subscription = Subscription::first();

        $subscription->trial_start_date = null;
        $subscription->initial_plan_start_date = $request->initial_plan_start_date;
        $subscription->plan_id = $id;
        $subscription->initial_subtotal = $plan->price_per_year;
        $subscription->initial_plan_start_date = now();
        $subscription->initial_plan_expire_date = $request->initial_plan_expire_date;

        if($subscription->save()) {
            $transaction = new SubscriptionTransaction();
            $transaction->subscription_id = $subscription->id;
            $transaction->plan_id = $plan->id;
            $transaction->transaction_type = 1;
            $transaction->payment_method_provider_name = $request->payment_method_name;
            $transaction->payment_method_name = $request->payment_method_name;
            $transaction->subtotal = $plan->price_per_year;
            $transaction->total_payable_amount = $plan->price_per_year;
            $transaction->paid = $plan->price_per_year;
            $transaction->payment_status = 1;
            $transaction->payment_date = now();

            $transaction->save();
        }

        return response()->json(['success' => true, 'message' => 'Subscription upgrade successfully']);
    }

    public function cartFoAddBranch() {

        return view('setups.billing.cart_for_add_branch');
    }

    public function cartForRenewBranch() {
        return view('setups.billing.cart_for_branch_renew');
    }

    public function dueRepayment() {
        return view('setups.billing.due_repayment');
    }
}
