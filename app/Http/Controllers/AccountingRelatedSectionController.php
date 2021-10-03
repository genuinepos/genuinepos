<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CashFlow;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingRelatedSectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // balance sheet view
    public function balanceSheet()
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        return view('accounting.related_sections.balance_sheet');
    }

    // Get balance sheet amounts **requested by ajax**
    public function balanceSheetAmounts()
    {
        $totalSupplierDue = 0;
        $totalSupplierReturnDue = 0;
        $totalCustomerReturnDue = 0;
        $totalCustomerDue = 0;
        $accountBalance = 0;
        $closingStock = 0;

        $suppliers = DB::table('suppliers')->select(['id', 'total_purchase_due', 'total_purchase_return_due'])->get();
        foreach ($suppliers as $supplier) {
            $totalSupplierDue += $supplier->total_purchase_due;
            $totalSupplierReturnDue += $supplier->total_purchase_return_due;
        }

        $customers = DB::table('customers')->select(['id', 'total_sale_due', 'total_sale_return_due'])->get();
        foreach ($customers as $customer) {
            $totalCustomerDue += $customer->total_sale_due;
            $totalCustomerReturnDue += $customer->total_sale_return_due;
        }

        $accounts = DB::table('accounts')->select(['id', 'name', 'balance'])->get();
        foreach ($accounts as $account) {
            $accountBalance += $account->balance;
        }

        $totalPhysicalAsset = DB::table('assets')->select(
            DB::raw('sum(total_value) as t_value'),
        )->groupBy('assets.id')->get();

        $products = Product::with('product_variants')->select(['id', 'product_cost_with_tax'])->get();

        foreach ($products as $product) {
            if ($product->product_variants) {
                foreach ($product->product_variants as $variant) {
                    $closingStock += $variant->variant_cost_with_tax;
                }
            } else {
                $closingStock += $product->product_cost_with_tax;
            }
        }

        $totalLiLiability = $totalSupplierDue + $totalCustomerReturnDue;
        $totalAsset = $totalSupplierReturnDue + 
        $totalCustomerDue + 
        $accountBalance + 
        $closingStock + 
        $totalPhysicalAsset->sum('t_value');

        return response()->json([
            'totalSupplierDue' => $totalSupplierDue,
            'totalSupplierReturnDue' => $totalSupplierReturnDue,
            'totalCustomerReturnDue' => $totalCustomerReturnDue,
            'totalCustomerDue' => $totalCustomerDue,
            'closingStock' => $closingStock,
            'totalPhysicalAsset' => $totalPhysicalAsset->sum('t_value'),
            'totalLiLiability' => $totalLiLiability,
            'totalAsset' => $totalAsset,
            'accounts' => $accounts,
        ]);
    }

    // Trial balance view
    public function trialBalance()
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        return view('accounting.related_sections.trial_balance');
    }

    // Get balance sheet amounts **requested by ajax**
    public function trialBalanceAmounts()
    {
        $totalSupplierDue = 0;
        $totalSupplierReturnDue = 0;
        $totalCustomerReturnDue = 0;
        $totalCustomerDue = 0;
        $accountBalance = 0;

        $suppliers = DB::table('suppliers')->select(['id', 'total_purchase_due', 'total_purchase_return_due'])->get();
        foreach ($suppliers as $supplier) {
            $totalSupplierDue += $supplier->total_purchase_due;
            $totalSupplierReturnDue += $supplier->total_purchase_return_due;
        }

        $customers = DB::table('customers')->select(['id', 'total_sale_due', 'total_sale_return_due'])->get();
        foreach ($customers as $customer) {
            $totalCustomerDue += $customer->total_sale_due;
            $totalCustomerReturnDue += $customer->total_sale_return_due;
        }

        $accounts = DB::table('accounts')->select(['id', 'name', 'balance'])->get();
        foreach ($accounts as $account) {
            $accountBalance += $account->balance;
        }

        $totalPhysicalAsset = DB::table('assets')->select(
            DB::raw('sum(total_value) as t_value'),
        )->groupBy('assets.id')->get();


        $totalDebit = $totalSupplierDue + $totalCustomerReturnDue;
        $totalCredit = $totalSupplierReturnDue + $totalCustomerDue + $accountBalance + $totalPhysicalAsset->sum('t_value');

        return response()->json([
            'totalSupplierDue' => $totalSupplierDue,
            'totalSupplierReturnDue' => $totalSupplierReturnDue,
            'totalCustomerReturnDue' => $totalCustomerReturnDue,
            'totalCustomerDue' => $totalCustomerDue,
            'totalPhysicalAsset' => $totalPhysicalAsset->sum('t_value'),
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'accounts' => $accounts
        ]);
    }

    // Cash flow view
    public function cashFow()
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        return view('accounting.related_sections.cash_flow');
    }

    // All cash flows **requested by ajax**
    public function allCashflows()
    {
        $CashFlows = CashFlow::with(
            [
                'account', 'sender_account',
                'receiver_account',
                'sale_payment',
                'sale_payment.sale',
                'sale_payment.customer',
                'purchase_payment',
                'purchase_payment.purchase',
                'purchase_payment.supplier',
                'expanse_payment',
                'expanse_payment.expense',
                'money_receipt',
                'money_receipt.customer',
                'payroll',
                'payroll_payment',
                'loan',
                'loan.company',
            ]
        )->orderBy('report_date', 'desc')->get();
        return view('accounting.related_sections.ajax_view.cash_flows_list', compact('CashFlows'));
    }

    public function filterCashflows(Request $request)
    {
        $filterCashFlows = '';
        $query = CashFlow::with(
            [
                'account',
                'sender_account',
                'receiver_account',
                'sale_payment',
                'sale_payment.sale',
                'sale_payment.customer',
                'purchase_payment',
                'purchase_payment.purchase',
                'purchase_payment.supplier',
                'expanse_payment',
                'expanse_payment.expense',
                'money_receipt',
                'money_receipt.customer',
                'payroll',
                'payroll_payment',
                'loan',
                'loan.company',
            ]
        );

        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $query->whereBetween('report_date', $date_range); // Final
        }

        if ($request->transaction_type) {
            $query->where('cash_type', $request->transaction_type);
        }
        
        $filterCashFlows = $query->orderBy('report_date', 'desc')->get();
        return view('accounting.related_sections.ajax_view.filtered_cash_flow', compact('filterCashFlows'));
    }

    public function printCashflow(Request $request)
    {
        $filterCashFlows = '';
        $fromDate = '';
        $toDate = '';
        $query = CashFlow::with(
            [
                'account',
                'sender_account',
                'receiver_account',
                'sale_payment',
                'sale_payment.sale',
                'sale_payment.customer',
                'purchase_payment',
                'purchase_payment.purchase',
                'purchase_payment.supplier',
                'expanse_payment',
                'expanse_payment.expense',
                'money_receipt',
                'money_receipt.customer',
                'payroll',
                'payroll_payment',
                'loan',
                'loan.company',
            ]
        );

        if ($request->from_date) {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
            $query->whereBetween('report_date', $date_range); // Final
        }

        if ($request->transaction_type) {
            $query->where('cash_type', $request->transaction_type);
        }
        
        $filterCashFlows = $query->orderBy('id', 'desc')->get();
        return view('accounting.related_sections.ajax_view.print_cash_flow', compact('filterCashFlows', 'fromDate', 'toDate'));
    }
}