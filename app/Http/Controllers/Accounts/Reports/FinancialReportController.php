<?php

namespace App\Http\Controllers\Accounts\Reports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\FinancialAmountsUtil;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\Reports\FinancialReport\AssetAmountsService;

class FinancialReportController extends Controller
{
    public function __construct(
        private AssetAmountsService $assetAmountsService,
        private BranchService $branchService,
        private FinancialAmountsUtil $financialAmountsUtil
    ) {

        $this->financialAmountsUtil = $financialAmountsUtil;
    }

    public function index()
    {
        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return view('accounting.reports.financial_report.index', compact('branches'));
    }

    public function financialAmounts(Request $request)
    {
        $generalSettings = config('generalSettings');
        $accountStartDate = date('Y-m-d', strtotime($generalSettings['business_or_shop__account_start_date']));

        $assetDetails = $this->assetAmountsService->details(request: $request, accountStartDate: $accountStartDate);
        // $allFinancialAmounts = $this->financialAmountsUtil->allFinancialAmounts($request);
        // $from_date = $request->from_date;

        return view('accounting.reports.financial_report.ajax_view.financial_amounts', compact('assetDetails'));
    }

    public function print(Request $request)
    {
        $expenses = '';
        $branch_id = $request->branch_id;
        $fromDate = '';
        $toDate = '';

        if ($request->from_date) {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
        }

        $allFinancialAmounts = $this->financialAmountsUtil->allFinancialAmounts($request);
        // $loans = DB::table('loans')->select(
        //     DB::raw("sum(IF(type = '1', loan_amount, 0)) as total_pay_loan"),
        //     DB::raw("sum(IF(type = '1', total_paid, 0)) as total_pay_loan_paid"),
        //     DB::raw("sum(IF(type = '1', due, 0)) as total_pay_loan_due"),
        //     DB::raw("sum(IF(type = '2', loan_amount, 0)) as total_receive_loan"),
        //     DB::raw("sum(IF(type = '2', total_paid, 0)) as total_receive_loan_paid"),
        //     DB::raw("sum(IF(type = '2', due, 0)) as total_receive_loan_due"),
        // )->get();

        return view('reports.financial_report.ajax_view.print', compact('allFinancialAmounts', 'branch_id', 'fromDate', 'toDate'));
    }
}
