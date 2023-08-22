<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Setups\CashCounterService;

class CashCounterController extends Controller
{
    public function __construct(private CashCounterService $cashCounterService, private BranchService $branchService)
    {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('cash_counters')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->cashCounterService->cashCounterListTable($request);
        }

        $branches = $this->branchService->branches()->get();

        return view('setups.cash_counter.index', compact('branches'));
    }

    public function create()
    {
        if (!auth()->user()->can('cash_counters')) {

            abort(403, 'Access Forbidden.');
        }

        return view('setups.cash_counter.ajax_view.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'counter_name' => ['required', Rule::unique('cash_counters')->where(function ($query) {
                return $query->where('branch_id', auth()->user()->branch_id);
            })],
            'short_name' => ['required', Rule::unique('cash_counters')->where(function ($query) {
                return $query->where('branch_id', auth()->user()->branch_id);
            })],
        ]);

        try {

            DB::beginTransaction();

            $generalSettings = config('generalSettings');

            $restriction = $this->cashCounterService->restriction($generalSettings);

            if ($restriction['pass'] == false) {

                return response()->json(['errorMsg' => $restriction['msg']]);
            }

            $addCashCounter = $this->cashCounterService->addCashCounter($request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addCashCounter;
    }

    public function edit($id)
    {
        $cashCounter = $this->cashCounterService->singleCashCounter(id: $id);

        return view('setups.cash_counter.ajax_view.edit', compact('cashCounter'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'counter_name' => ['required', Rule::unique('cash_counters')->where(function ($query) use ($id) {
                return $query->where('branch_id', auth()->user()->branch_id)->where('id', '!=', $id);
            })],
            'short_name' => ['required', Rule::unique('cash_counters')->where(function ($query) use ($id) {
                return $query->where('branch_id', auth()->user()->branch_id)->where('id', '!=', $id);
            })],
        ]);

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
