<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('accounting.related_sections.balance_sheet', compact('branches'));
    }

    // Get balance sheet amounts **requested by ajax**
    public function balanceSheetAmounts(Request $request)
    {
        $totalCashInHand = '';
        $TotalBankBalance = '';
        $TotalCapital = '';
        $TotalInvestment = '';
        $fixedAssets = '';
        $loanCompanies = '';
        $singleProductStockValueValue = '';
        $variantProductStockValueValue = '';

        $suppliers = DB::table('suppliers')
            ->select(
                DB::raw('SUM(total_purchase_due) as total_due'),
                DB::raw('SUM(total_purchase_return_due) as total_return_due'),
            )->get();

        $customers = DB::table('customers')
            ->select(
                DB::raw('SUM(total_sale_due) as total_due'),
                DB::raw('SUM(total_sale_return_due) as total_return_due'),
            )->get();

        $totalCashInHandQuery = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->select(DB::raw('SUM(accounts.balance) as total_cash'))
            ->where('accounts.account_type', 1)
            ->groupBy('accounts.account_type');

        $TotalBankBalanceQuery = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->select(DB::raw('SUM(accounts.balance) as total_bank_balance'))
            ->where('accounts.account_type', 2)
            ->groupBy('accounts.account_type');

        $TotalCapitalQuery = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->select(DB::raw('SUM(accounts.balance) as total_capital'))
            ->where('accounts.account_type', 26)
            ->groupBy('accounts.account_type');

        $TotalInvestmentQuery = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->select(DB::raw('SUM(accounts.balance) as total_investment'))
            ->where('accounts.account_type', 16)->groupBy('accounts.account_type');

        $fixedAssetQuery = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->select(
                'accounts.name',
                'accounts.balance'
            )->where('accounts.account_type', 15)->orderBy('accounts.id', 'desc');

        $loanCompanyQuery = DB::table('loan_companies')
            ->select(
                DB::raw('SUM(pay_loan_due) as total_la_receivable'),
                DB::raw('SUM(get_loan_due) as total_ll_payable'),
            );

        $singleProductStockValueQuery = DB::table('product_branches')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->where('products.is_variant', 0)
            ->select(DB::raw('SUM(products.product_cost_with_tax * product_branches.product_quantity) as total'));

        $variantProductStockValueQuery = DB::table('product_branch_variants')
            ->leftJoin('product_branches', 'product_branch_variants.product_branch_id', 'product_branches.id')
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
            ->select(DB::raw('SUM(product_variants.variant_cost_with_tax * product_branch_variants.variant_quantity) as total'));

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $totalCashInHandQuery->where('account_branches.branch_id', NULL);

                $TotalBankBalanceQuery->where('account_branches.branch_id', NULL);

                $TotalCapitalQuery->where('account_branches.branch_id', NULL);

                $TotalInvestmentQuery->where('account_branches.branch_id', NULL);

                $fixedAssetQuery->where('account_branches.branch_id', NULL)->get();

                $loanCompanyQuery->where('loan_companies.branch_id', NULL)->get();

                $singleProductStockValueQuery->where('product_branches.branch_id', NULL);

                $variantProductStockValueQuery->where('product_branches.branch_id', NULL);

            } else {

                $totalCashInHandQuery->where('account_branches.branch_id', $request->branch_id);

                $TotalBankBalanceQuery->where('account_branches.branch_id', $request->branch_id);

                $TotalCapitalQuery->where('account_branches.branch_id', $request->branch_id);

                $TotalInvestmentQuery->where('account_branches.branch_id', $request->branch_id);

                $fixedAssetQuery->where('account_branches.branch_id', $request->branch_id);

                $loanCompanyQuery->where('loan_companies.branch_id', $request->branch_id);

                $singleProductStockValueQuery->where('product_branches.branch_id', $request->branch_id);

                $variantProductStockValueQuery->where('product_branches.branch_id', $request->branch_id);
            }
        }

        $totalCashInHand = $totalCashInHandQuery->get();

        $TotalBankBalance = $TotalBankBalanceQuery->get();

        $TotalCapital = $TotalCapitalQuery->get();

        $TotalInvestment = $TotalInvestmentQuery->get();

        $fixedAssets = $fixedAssetQuery->get();

        $loanCompanies = $loanCompanyQuery->get();

        $singleProductStockValue = $singleProductStockValueQuery->get();

        $variantProductStockValue = $variantProductStockValueQuery->get();

        $currentStockValue = $singleProductStockValue->sum('total') + $variantProductStockValue->sum('total');

        return view(
            'accounting.related_sections.ajax_view.balance_sheet_ajax_view',
            compact(
                'suppliers',
                'customers',
                'totalCashInHand',
                'TotalBankBalance',
                'TotalInvestment',
                'fixedAssets',
                'TotalCapital',
                'loanCompanies',
                'currentStockValue',
            )
        );
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
                'loan_payment',
                'loan_payment.branch',
                'loan_payment.company',
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
                'loan_payment',
                'loan_payment.branch',
                'loan_payment.company',
                'loan.company',
            ]
        );

        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
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
                'loan_payment',
                'loan_payment.branch',
                'loan_payment.company',
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
