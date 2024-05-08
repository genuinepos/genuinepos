<?php

namespace App\Http\Controllers\Accounts\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\Reports\CashFlow\FixedAssetsCashFlowService;
use App\Services\Accounts\Reports\CashFlow\InvestmentsCashFlowService;
use App\Services\Accounts\Reports\CashFlow\CurrentAssetsCashFlowService;
use App\Services\Accounts\Reports\CashFlow\DirectExpenseCashFlowService;
use App\Services\Accounts\Reports\CashFlow\DirectIncomesCashFlowService;
use App\Services\Accounts\Reports\CashFlow\SalesAccountsCashFlowService;
use App\Services\Accounts\Reports\CashFlow\CapitalAccountCashFlowService;
use App\Services\Accounts\Reports\CashFlow\IndirectIncomesCashFlowService;
use App\Services\Accounts\Reports\CashFlow\LoanLiabilitiesCashFlowService;
use App\Services\Accounts\Reports\CashFlow\SuspenseAccountCashFlowService;
use App\Services\Accounts\Reports\CashFlow\IndirectExpensesCashFlowService;
use App\Services\Accounts\Reports\CashFlow\PurchaseAccountsCashFlowService;
use App\Services\Accounts\Reports\CashFlow\BranchAndDivisionCashFlowService;
use App\Services\Accounts\Reports\CashFlow\CurrentLiabilitiesCashFlowService;

class CashFlowController extends Controller
{
    public function __construct(
        private CapitalAccountCashFlowService $capitalAccountCashFlowService,
        private BranchAndDivisionCashFlowService $branchAndDivisionCashFlowService,
        private SuspenseAccountCashFlowService $suspenseAccountCashFlowService,
        private CurrentLiabilitiesCashFlowService $currentLiabilitiesCashFlowService,
        private LoanLiabilitiesCashFlowService $loanLiabilitiesCashFlowService,
        private CurrentAssetsCashFlowService $currentAssetsCashFlowService,
        private FixedAssetsCashFlowService $fixedAssetsCashFlowService,
        private InvestmentsCashFlowService $investmentsCashFlowService,
        private DirectExpenseCashFlowService $directExpenseCashFlowService,
        private IndirectExpensesCashFlowService $indirectExpensesCashFlowService,
        private PurchaseAccountsCashFlowService $purchaseAccountsCashFlowService,
        private DirectIncomesCashFlowService $directIncomesCashFlowService,
        private IndirectIncomesCashFlowService $indirectIncomesCashFlowService,
        private SalesAccountsCashFlowService $salesAccountsCashFlowService,
        private BranchService $branchService,
    ) {
    }

    public function index()
    {
        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();
        return view('accounting.reports.cash_flow.index', compact('branches'));
    }

    public function cashFlowData(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        if ($request->from_date && !isset($request->to_date)) {

            return response()->json(['errorMsg' => __('To Date is required')]);
        } elseif ($request->to_date && !isset($request->from_date)) {

            return response()->json(['errorMsg' => __('From Date is required')]);
        }

        $formatOfReport = $request->format_of_report;
        $capitalAccountCashFlows = $this->capitalAccountCashFlowService->capitalAccount(request: $request);
        $branchAndDivisionCashFlows = $this->branchAndDivisionCashFlowService->branchAndDivision(request: $request);
        $suspenseAccountCashFlows = $this->suspenseAccountCashFlowService->suspenseAccount(request: $request);
        $currentLiabilitiesCashFlows = $this->currentLiabilitiesCashFlowService->currentLiabilities(request: $request);
        $loanLiabilitiesCashFlows = $this->loanLiabilitiesCashFlowService->loanLiabilities(request: $request);
        $currentAssetsCashFlows = $this->currentAssetsCashFlowService->currentAsset(request: $request);
        $fixedAssetsCashFlows = $this->fixedAssetsCashFlowService->fixedAssets(request: $request);
        $investmentsCashFlows = $this->investmentsCashFlowService->investments(request: $request);
        $directExpenseCashFlows = $this->directExpenseCashFlowService->DirectExpenses(request: $request);
        $indirectExpenseCashFlows = $this->indirectExpensesCashFlowService->indirectExpenses(request: $request);
        $purchaseCashFlows = $this->purchaseAccountsCashFlowService->purchaseAccounts(request: $request);
        $directIncomeCashFlows = $this->directIncomesCashFlowService->directIncomes(request: $request);
        $indirectIncomeCashFlows = $this->indirectIncomesCashFlowService->indirectIncomes(request: $request);
        $salesAccountCashFlows = $this->salesAccountsCashFlowService->salesAccounts($request);

        $totalIn = 0;
        $totalOut = 0;

        $totalIn += $capitalAccountCashFlows->cash_in;
        $totalIn += $branchAndDivisionCashFlows->cash_in;
        $totalIn += $suspenseAccountCashFlows->cash_in;
        $totalIn += $currentLiabilitiesCashFlows->cash_in;
        $totalIn += $loanLiabilitiesCashFlows->cash_in;
        $totalIn += $currentAssetsCashFlows->cash_in;
        $totalIn += $fixedAssetsCashFlows->cash_in;
        $totalIn += $investmentsCashFlows->cash_in;
        $totalIn += $directExpenseCashFlows->cash_in;
        $totalIn += $indirectExpenseCashFlows->cash_in;
        $totalIn += $purchaseCashFlows->cash_in;
        $totalIn += $directIncomeCashFlows->cash_in;
        $totalIn += $indirectIncomeCashFlows->cash_in;
        $totalIn += $salesAccountCashFlows->cash_in;

        $totalOut += $capitalAccountCashFlows->cash_out;
        $totalOut += $branchAndDivisionCashFlows->cash_out;
        $totalOut += $suspenseAccountCashFlows->cash_out;
        $totalOut += $currentLiabilitiesCashFlows->cash_out;
        $totalOut += $loanLiabilitiesCashFlows->cash_out;
        $totalOut += $currentAssetsCashFlows->cash_out;
        $totalOut += $fixedAssetsCashFlows->cash_out;
        $totalOut += $investmentsCashFlows->cash_out;
        $totalOut += $directExpenseCashFlows->cash_out;
        $totalOut += $indirectExpenseCashFlows->cash_out;
        $totalOut += $purchaseCashFlows->cash_out;
        $totalOut += $directIncomeCashFlows->cash_out;
        $totalOut += $indirectIncomeCashFlows->cash_out;
        $totalOut += $salesAccountCashFlows->cash_out;

        $balance = 0;
        $balanceSide = 'in';

        if ($totalIn > $totalOut) {

            $balance = $totalIn - $totalOut;
            $balanceSide = 'in';
        } elseif ($totalOut > $totalIn) {

            $balance = $totalOut - $totalIn;
            $balanceSide = 'out';
        }

        return view('accounting.reports.cash_flow.ajax_view.cash_flow_data',
            compact(
                'capitalAccountCashFlows',
                'branchAndDivisionCashFlows',
                'suspenseAccountCashFlows',
                'currentLiabilitiesCashFlows',
                'loanLiabilitiesCashFlows',
                'currentAssetsCashFlows',
                'fixedAssetsCashFlows',
                'investmentsCashFlows',
                'directExpenseCashFlows',
                'indirectExpenseCashFlows',
                'purchaseCashFlows',
                'directIncomeCashFlows',
                'indirectIncomeCashFlows',
                'salesAccountCashFlows',
                'totalIn',
                'totalOut',
                'balance',
                'balanceSide',
                'formatOfReport',
                'fromDate',
                'toDate',
            )
        );
    }

    public function cashFlowPrint(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        if ($request->from_date && !isset($request->to_date)) {

            return response()->json(['errorMsg' => __('To Date is required')]);
        } elseif ($request->to_date && !isset($request->from_date)) {

            return response()->json(['errorMsg' => __('From Date is required')]);
        }

        $ownOrParentBranch = '';
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch;
            } else {

                $branchName = auth()->user()?->branch;
            }
        }

        $filteredBranchName = $request->branch_name;
        $filteredChildBranchName = $request->child_branch_name;

        $formatOfReport = $request->format_of_report;
        $capitalAccountCashFlows = $this->capitalAccountCashFlowService->capitalAccount(request: $request);
        $branchAndDivisionCashFlows = $this->branchAndDivisionCashFlowService->branchAndDivision(request: $request);
        $suspenseAccountCashFlows = $this->suspenseAccountCashFlowService->suspenseAccount(request: $request);
        $currentLiabilitiesCashFlows = $this->currentLiabilitiesCashFlowService->currentLiabilities(request: $request);
        $loanLiabilitiesCashFlows = $this->loanLiabilitiesCashFlowService->loanLiabilities(request: $request);
        $currentAssetsCashFlows = $this->currentAssetsCashFlowService->currentAsset(request: $request);
        $fixedAssetsCashFlows = $this->fixedAssetsCashFlowService->fixedAssets(request: $request);
        $investmentsCashFlows = $this->investmentsCashFlowService->investments(request: $request);
        $directExpenseCashFlows = $this->directExpenseCashFlowService->DirectExpenses(request: $request);
        $indirectExpenseCashFlows = $this->indirectExpensesCashFlowService->indirectExpenses(request: $request);
        $purchaseCashFlows = $this->purchaseAccountsCashFlowService->purchaseAccounts(request: $request);
        $directIncomeCashFlows = $this->directIncomesCashFlowService->directIncomes(request: $request);
        $indirectIncomeCashFlows = $this->indirectIncomesCashFlowService->indirectIncomes(request: $request);
        $salesAccountCashFlows = $this->salesAccountsCashFlowService->salesAccounts($request);

        $totalIn = 0;
        $totalOut = 0;

        $totalIn += $capitalAccountCashFlows->cash_in;
        $totalIn += $branchAndDivisionCashFlows->cash_in;
        $totalIn += $suspenseAccountCashFlows->cash_in;
        $totalIn += $currentLiabilitiesCashFlows->cash_in;
        $totalIn += $loanLiabilitiesCashFlows->cash_in;
        $totalIn += $currentAssetsCashFlows->cash_in;
        $totalIn += $fixedAssetsCashFlows->cash_in;
        $totalIn += $investmentsCashFlows->cash_in;
        $totalIn += $directExpenseCashFlows->cash_in;
        $totalIn += $indirectExpenseCashFlows->cash_in;
        $totalIn += $purchaseCashFlows->cash_in;
        $totalIn += $directIncomeCashFlows->cash_in;
        $totalIn += $indirectIncomeCashFlows->cash_in;
        $totalIn += $salesAccountCashFlows->cash_in;

        $totalOut += $capitalAccountCashFlows->cash_out;
        $totalOut += $branchAndDivisionCashFlows->cash_out;
        $totalOut += $suspenseAccountCashFlows->cash_out;
        $totalOut += $currentLiabilitiesCashFlows->cash_out;
        $totalOut += $loanLiabilitiesCashFlows->cash_out;
        $totalOut += $currentAssetsCashFlows->cash_out;
        $totalOut += $fixedAssetsCashFlows->cash_out;
        $totalOut += $investmentsCashFlows->cash_out;
        $totalOut += $directExpenseCashFlows->cash_out;
        $totalOut += $indirectExpenseCashFlows->cash_out;
        $totalOut += $purchaseCashFlows->cash_out;
        $totalOut += $directIncomeCashFlows->cash_out;
        $totalOut += $indirectIncomeCashFlows->cash_out;
        $totalOut += $salesAccountCashFlows->cash_out;

        $balance = 0;
        $balanceSide = 'in';

        if ($totalIn > $totalOut) {

            $balance = $totalIn - $totalOut;
            $balanceSide = 'in';
        } elseif ($totalOut > $totalIn) {

            $balance = $totalOut - $totalIn;
            $balanceSide = 'out';
        }

        return view('accounting.reports.cash_flow.ajax_view.print_cash_flow',
            compact(
                'capitalAccountCashFlows',
                'branchAndDivisionCashFlows',
                'suspenseAccountCashFlows',
                'currentLiabilitiesCashFlows',
                'loanLiabilitiesCashFlows',
                'currentAssetsCashFlows',
                'fixedAssetsCashFlows',
                'investmentsCashFlows',
                'directExpenseCashFlows',
                'indirectExpenseCashFlows',
                'purchaseCashFlows',
                'directIncomeCashFlows',
                'indirectIncomeCashFlows',
                'salesAccountCashFlows',
                'totalIn',
                'totalOut',
                'balance',
                'balanceSide',
                'formatOfReport',
                'ownOrParentBranch',
                'filteredBranchName',
                'filteredChildBranchName',
                'fromDate',
                'toDate',
            )
        );
    }


}
