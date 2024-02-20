<?php

namespace App\Http\Controllers\Setups;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SoftwareServiceBillingController extends Controller
{
    public function index() {
        return view('setups.billing.index');
    }

    public function upgradePlan() {
        return view('setups.billing.upgrade_plan');
    }

    public function cartFoUpgradePlan() {
        return view('setups.billing.cart_for_upgrade_plan');
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
