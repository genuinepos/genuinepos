<?php

namespace App\Http\Controllers\Setups;

use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Setups\CashCounterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashCounterController extends Controller
{
    public function __construct(private CashCounterService $cashCounterService, private BranchService $branchService)
    {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('cash_counters_index') || config('generalSettings')['subscription']->features['cash_counter_count'] == 0, 403);

        if ($request->ajax()) {

            return $this->cashCounterService->cashCounterListTable($request);
        }

        $count = $this->cashCounterService->cashCounters()->where('branch_id', auth()->user()->branch_id)->count();
        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('setups.cash_counter.index', compact('branches', 'count'));
    }

    public function create()
    {
        abort_if(!auth()->user()->can('cash_counters_add') || config('generalSettings')['subscription']->features['cash_counter_count'] == 0, 403);

        return view('setups.cash_counter.ajax_view.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('cash_counters_add') || config('generalSettings')['subscription']->features['cash_counter_count'] == 0, 403);

        $this->cashCounterService->cashCounterStoreValidation(request: $request);

        try {
            DB::beginTransaction();

            $generalSettings = config('generalSettings');

            $restriction = $this->cashCounterService->restriction($generalSettings);

            if ($restriction['pass'] == false) {

                return response()->json(['errorMsg' => $restriction['msg']]);
            }

            $addCashCounter = $this->cashCounterService->addCashCounter(branchId: auth()->user()->branch_id, cashCounterName: $request->counter_name, shortName: $request->short_name);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addCashCounter;
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('cash_counters_edit') || config('generalSettings')['subscription']->features['cash_counter_count'] == 0, 403);

        $cashCounter = $this->cashCounterService->singleCashCounter(id: $id);

        return view('setups.cash_counter.ajax_view.edit', compact('cashCounter'));
    }

    public function update(Request $request, $id)
    {
        abort_if(!auth()->user()->can('cash_counters_edit') || config('generalSettings')['subscription']->features['cash_counter_count'] == 0, 403);

        try {
            DB::beginTransaction();

            $this->cashCounterService->updateCashCounter(id: $id, request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Cash counter updated Successfully.'));
    }

    public function delete(Request $request, $id)
    {
        abort_if(!auth()->user()->can('cash_counters_delete') || config('generalSettings')['subscription']->features['cash_counter_count'] == 0, 403);

        try {
            DB::beginTransaction();

            $this->cashCounterService->deleteCashCounter($id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Cash counter deleted Successfully.'));
    }
}
