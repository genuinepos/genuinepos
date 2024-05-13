<?php

namespace App\Http\Controllers;

use App\Utils\AccountUtil;
use App\Utils\NetProfitLossAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingRelatedSectionController extends Controller
{
    protected $accountUtil;
    protected $netProfitLossAccount;

    public function __construct(AccountUtil $accountUtil, NetProfitLossAccount $netProfitLossAccount)
    {
        $this->accountUtil = $accountUtil;
        $this->netProfitLossAccount = $netProfitLossAccount;

    }

    // balance sheet view
    public function balanceSheet()
    {
        if (! auth()->user()->can('accounting_access')) {

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

                $totalCashInHandQuery->where('account_branches.branch_id', null);

                $TotalBankBalanceQuery->where('account_branches.branch_id', null);

                $TotalCapitalQuery->where('account_branches.branch_id', null);

                $TotalInvestmentQuery->where('account_branches.branch_id', null);

                $fixedAssetQuery->where('account_branches.branch_id', null);

                $loanCompanyQuery->where('loan_companies.branch_id', null);

                $singleProductStockValueQuery->where('product_branches.branch_id', null);

                $variantProductStockValueQuery->where('product_branches.branch_id', null);
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

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $totalCashInHand = $totalCashInHandQuery->get();

            $TotalBankBalance = $TotalBankBalanceQuery->get();

            $TotalCapital = $TotalCapitalQuery->get();

            $TotalInvestment = $TotalInvestmentQuery->get();

            $fixedAssets = $fixedAssetQuery->get();

            $loanCompanies = $loanCompanyQuery->get();

            $singleProductStockValue = $singleProductStockValueQuery->get();

            $variantProductStockValue = $variantProductStockValueQuery->get();
        } else {

            $totalCashInHand = $totalCashInHandQuery->where('account_branches.branch_id', auth()->user()->branch_id)
                ->get();

            $TotalBankBalance = $TotalBankBalanceQuery->where('account_branches.branch_id', auth()->user()->branch_id)->get();

            $TotalCapital = $TotalCapitalQuery->where('account_branches.branch_id', auth()->user()->branch_id)->get();

            $TotalInvestment = $TotalInvestmentQuery->where('account_branches.branch_id', auth()->user()->branch_id)->get();

            $fixedAssets = $fixedAssetQuery->where('account_branches.branch_id', auth()->user()->branch_id)->get();

            $loanCompanies = $loanCompanyQuery->where('loan_companies.branch_id', auth()->user()->branch_id)->get();

            $singleProductStockValue = $singleProductStockValueQuery->where('product_branches.branch_id', auth()->user()->branch_id)->get();

            $variantProductStockValue = $variantProductStockValueQuery->where('product_branches.branch_id', auth()->user()->branch_id)->get();
        }

        // $totalCashInHand = $totalCashInHandQuery->get();

        // $TotalBankBalance = $TotalBankBalanceQuery->get();

        // $TotalCapital = $TotalCapitalQuery->get();

        // $TotalInvestment = $TotalInvestmentQuery->get();

        // $fixedAssets = $fixedAssetQuery->get();

        // $loanCompanies = $loanCompanyQuery->get();

        // $singleProductStockValue = $singleProductStockValueQuery->get();

        // $variantProductStockValue = $variantProductStockValueQuery->get();

        $currentStockValue = $singleProductStockValue->sum('total') + $variantProductStockValue->sum('total');

        $netProfitLossAccount = $this->netProfitLossAccount->netLossProfit($request);

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
                'netProfitLossAccount',
            )
        );
    }

    public function profitLossAccount()
    {
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();

        return view('accounting.related_sections.loss_profit_ac', compact('branches'));
    }

    public function profitLossAccountAmounts(Request $request)
    {
        $netProfitLossAccount = $this->netProfitLossAccount->netLossProfit($request);

        return view(
            'accounting.related_sections.ajax_view.profit_loss_ac_ajax_view',
            compact('netProfitLossAccount')
        );
    }

    public function printProfitLossAccount(Request $request)
    {
        $branch_id = $request->branch_id;

        $fromDate = '';
        $toDate = '';

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = isset($request->to_date) && $request->to_date
                ? date('Y-m-d', strtotime($request->to_date))
                : $from_date;

            $fromDate = $from_date;
            $toDate = $to_date;
        }

        $netProfitLossAccount = $this->netProfitLossAccount->netLossProfit($request);

        return view(
            'accounting.related_sections.ajax_view.print_profit_loss_ac',
            compact('netProfitLossAccount', 'branch_id', 'fromDate', 'toDate')
        );
    }
}
