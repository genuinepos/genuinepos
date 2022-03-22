<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Utils\NetProfitLossAccount;

class FinancialAmountsUtil
{
    protected $netProfitLossAccount;
 
    public function __construct(
        NetProfitLossAccount $netProfitLossAccount,
    ) {

        $this->netProfitLossAccount = $netProfitLossAccount;
    }
    
    public function allFinancialAmounts($request = NULL): array
    {
        $cashAndBankBalance = $this->cashAndBankBalance($request);
        // $salesSaleReturnAmount = $this->salesSaleReturnAmount($request);
        // $purchaseAndPurchaseReturnAmount = $this->purchaseAndPurchaseReturnAmount($request);
        // $expensesAmounts = $this->expensesAmounts($request);
 
        $netProfitLossAccountAmounts = $this->netProfitLossAccount->netLossProfit($request);

        $anotherAmounts = [];

        $anotherAmounts['fixed_asset_balance'] = $this->fixedAssetBalance($request);
        $anotherAmounts['cash_in_hand'] = $cashAndBankBalance['cash_in_hand_balance'];
        $anotherAmounts['bank_account'] = $cashAndBankBalance['bank_account_balance'];

        return array_merge($netProfitLossAccountAmounts, $anotherAmounts);
    }

    private function cashAndBankBalance($request)
    {
        $cashInHandAmounts = '';
        $cashInHandAmountsQ = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $cashInHandAmountsQ->where('account_branches.branch_id', NULL);
            } else {

                $cashInHandAmountsQ->where('account_branches.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $cashInHandAmountsQ->whereBetween('account_ledgers.date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $cashInHandAmounts = $cashInHandAmountsQ->select(
                'accounts.account_type',
                DB::raw('SUM(account_ledgers.debit) as total_debit'),
                DB::raw('SUM(account_ledgers.credit) as total_credit')
            )->groupBy('accounts.account_type')->get();
        } else {

            $cashInHandAmounts = $cashInHandAmountsQ
                ->where('account_branches.branch_id', auth()->user()->branch_id)
                ->select(
                    'accounts.account_type',
                    DB::raw('SUM(account_ledgers.debit) as total_debit'),
                    DB::raw('SUM(account_ledgers.credit) as total_credit')
                )->groupBy('accounts.account_type')->get();
        }

        $balance = ['cash_in_hand_balance' => 0, 'bank_account_balance' => 0];

        foreach ($cashInHandAmounts as $cashInHandAmount) {

            if ($cashInHandAmount->account_type == 1) {

                $balance['cash_in_hand_balance'] = $cashInHandAmount->total_debit - $cashInHandAmount->total_credit;
            } else {

                $balance['bank_account_balance'] = $cashInHandAmount->total_debit - $cashInHandAmount->total_credit;
            }
        }

        return $balance;
    }

    public function fixedAssetBalance($request)
    {
        $fixedAssetAmounts = '';
        $fixedAssetAmountsQ = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('accounts.account_type', 15)
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $fixedAssetAmountsQ->where('account_branches.branch_id', NULL);
            } else {

                $fixedAssetAmountsQ->where('account_branches.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $fixedAssetAmountsQ->whereBetween('account_ledgers.date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $fixedAssetAmounts = $fixedAssetAmountsQ->groupBy('accounts.account_type');
        } else {

            $fixedAssetAmounts = $fixedAssetAmountsQ
                ->where('account_branches.branch_id', auth()->user()->branch_id)
                ->groupBy('accounts.account_type');
        }

        $fixedAssetDebitCredit = $fixedAssetAmounts->select(
            DB::raw('SUM(account_ledgers.debit) as total_debit'),
            DB::raw('SUM(account_ledgers.credit) as total_credit')
        )->get();

        return $fixedAssetDebitCredit->sum('total_debit') - $fixedAssetDebitCredit->sum('total_credit');
    }

    // public function salesSaleReturnAmount($request)
    // {
    //     $sales = '';
    //     $saleReturns = '';
    //     $salesQ = DB::table('sales');
    //     $saleReturnsQ = DB::table('sale_returns');

    //     if (isset($request->branch_id) && $request->branch_id) {

    //         if ($request->branch_id == 'NULL') {

    //             $salesQ->where('sales.branch_id', NULL);
    //             $saleReturnsQ->where('sale_returns.branch_id', NULL);
    //         } else {

    //             $salesQ->where('sales.branch_id', $request->branch_id);
    //             $saleReturnsQ->where('sale_returns.branch_id', $request->branch_id);
    //         }
    //     }

    //     if (isset($request->from_date) && $request->from_date) {

    //         $from_date = date('Y-m-d', strtotime($request->from_date));
    //         $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
    //         $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
    //         $salesQ->whereBetween('sales.report_date', $date_range);
    //         $saleReturnsQ->whereBetween('sale_returns.report_date', $date_range);
    //     }

    //     if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

    //         $salesQ->whereIn('status', [1, 3]);
    //         $saleReturnsQ->select(DB::raw('SUM(total_return_amount) as total_return'));
    //     } else {

    //         $salesQ->where('sales.branch_id', auth()->user()->branch_id)
    //             ->whereIn('status', [1, 3]);

    //         $saleReturnsQ->where('sale_returns.branch_id', auth()->user()->branch_id)
    //             ->select(DB::raw('SUM(total_return_amount) as total_return'));
    //     }

    //     $sales = $salesQ->select(
    //         DB::raw('SUM(sales.total_payable_amount) as total_sale'),
    //         DB::raw('SUM(sales.paid) as total_paid'),
    //         DB::raw('SUM(sales.due) as total_due')
    //     )->get();

    //     $saleReturns = $saleReturnsQ->get();

    //     $amounts = [
    //         'total_sale' => $sales->sum('total_sale'),
    //         'total_paid' => $sales->sum('total_paid'),
    //         'total_due' => $sales->sum('total_due'),
    //         'total_return' => $saleReturns->sum('total_return'),
    //     ];
    // }

    // public function purchaseAndPurchaseReturnAmount($request)
    // {
    //     $purchases = '';
    //     $purchaseReturns = '';
    //     $purchasesQ = DB::table('purchases');
    //     $purchaseReturnsQ = DB::table('purchase_returns');

    //     if (isset($request->branch_id) && $request->branch_id) {

    //         if ($request->branch_id == 'NULL') {

    //             $purchasesQ->where('purchases.branch_id', NULL);
    //             $purchaseReturnsQ->where('purchase_returns.branch_id', NULL);
    //         } else {

    //             $purchasesQ->where('purchases.branch_id', $request->branch_id);
    //             $purchaseReturnsQ->where('purchase_returns.branch_id', $request->branch_id);
    //         }
    //     }

    //     if (isset($request->from_date) && $request->from_date) {

    //         $from_date = date('Y-m-d', strtotime($request->from_date));
    //         $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
    //         $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
    //         $purchasesQ->whereBetween('purchases.report_date', $date_range);
    //         $purchaseReturnsQ->whereBetween('purchase_returns.report_date', $date_range);
    //     }

    //     if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

    //         $purchasesQ->whereIn('purchase_status', [1, 3]);
    //         $purchaseReturnsQ->select(DB::raw('SUM(total_return_amount) as total_return'));
    //     } else {

    //         $salesQ->where('purchases.branch_id', auth()->user()->branch_id)
    //             ->whereIn('status', [1, 3]);

    //         $saleReturnsQ->where('purchases.branch_id', auth()->user()->branch_id)
    //             ->select(DB::raw('SUM(total_return_amount) as total_return'));
    //     }

    //     $sales = $salesQ->select(
    //         DB::raw('SUM(purchases.total_purchase_amount) as total_purchase'),
    //         DB::raw('SUM(purchases.paid) as total_paid'),
    //         DB::raw('SUM(purchases.due) as total_due')
    //     )->get();

    //     $saleReturns = $saleReturnsQ->get();

    //     $amounts = [
    //         'total_purchase' => $sales->sum('total_purchase'),
    //         'total_paid' => $sales->sum('total_paid'),
    //         'total_due' => $sales->sum('total_due'),
    //         'total_return' => $saleReturns->sum('total_return'),
    //     ];
    // }

    // public function expensesAmounts($request)
    // {
    //     $expenses = '';
    //     $expensesQ = DB::table('expanses')
    //         ->leftJoin('accounts', 'expanses.expense_account_id', 'accounts.id')
    //         ->whereIn('accounts.account_type', [7, 8]);

    //     if (isset($request->branch_id) && $request->branch_id) {

    //         if ($request->branch_id == 'NULL') {

    //             $expensesQ->where('expanses.branch_id', NULL);
    //         } else {

    //             $expensesQ->where('expanses.branch_id', $request->branch_id);
    //         }
    //     }

    //     if (isset($request->from_date) && $request->from_date) {

    //         $from_date = date('Y-m-d', strtotime($request->from_date));
    //         $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
    //         $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
    //         $expensesQ->whereBetween('expanses.report_date', $date_range);
    //     }

    //     if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

    //         $expenses = $expensesQ->select(
    //             'accounts.account_type',
    //             DB::raw('SUM(expanses.net_total_amount) as total_expense'),
    //         )->groupBy('accounts.account_type')->get();
    //     } else {

    //         $expenses = $expensesQ->where('expanses.branch_id', auth()->user()->branch_id)
    //         ->select(
    //             'accounts.account_type',
    //             DB::raw('SUM(expanses.net_total_amount) as total_expense'),
    //         )->groupBy('accounts.account_type')->get();
    //     }

    //     $amountArray = [];

    //     foreach ($expenses as $expense) {

    //         if ($$expense->account_type == 7) {

    //             $amountArray['direct_expense'] = $expense->total_expense;
    //         } else {

    //             $amountArray['indirect_expense'] = $cashInHandAmount->total_expense;
    //         }
    //     }

    //     return $amountArray;
    // }
}
