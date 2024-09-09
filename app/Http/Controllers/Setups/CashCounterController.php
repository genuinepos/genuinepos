<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Setups\CashCounterService;
use App\Http\Requests\Setups\CashCounterStoreRequest;
use App\Http\Requests\Setups\CashCounterDeleteRequest;
use App\Http\Requests\Setups\CashCounterUpdateRequest;

class CashCounterController extends Controller
{
    public function __construct(private CashCounterService $cashCounterService, private BranchService $branchService) {}

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('cash_counters_index'), 403);

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
        abort_if(!auth()->user()->can('cash_counters_add'), 403);

        return view('setups.cash_counter.ajax_view.create');
    }

    public function store(CashCounterStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $addCashCounter = $this->cashCounterService->addCashCounter(branchId: auth()->user()->branch_id, cashCounterName: $request->counter_name, shortName: $request->short_name);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addCashCounter;
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('cash_counters_edit'), 403);

        $cashCounter = $this->cashCounterService->singleCashCounter(id: $id);

        return view('setups.cash_counter.ajax_view.edit', compact('cashCounter'));
    }

    public function update($id, CashCounterUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $this->cashCounterService->updateCashCounter(id: $id, request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Cash counter updated Successfully.'));
    }

    public function delete($id, CashCounterDeleteRequest $request)
    {
        try {
            DB::beginTransaction();

            $deleteCashCounter = $this->cashCounterService->deleteCashCounter($id);

            if ($deleteCashCounter['pass'] == false) {

                return response()->json(['errorMsg' => $deleteCashCounter['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Cash counter deleted Successfully.'));
    }
}
