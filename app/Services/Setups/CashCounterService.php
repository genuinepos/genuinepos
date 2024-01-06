<?php

namespace App\Services\Setups;

use App\Models\Setups\CashCounter;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CashCounterService
{
    public function cashCounterListTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $cashCounters = '';

        $query = DB::table('cash_counters')
            ->leftJoin('branches', 'cash_counters.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');

        if (isset($request)) {

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('cash_counters.branch_id', null);
                } else {

                    $query->where('cash_counters.branch_id', $request->branch_id);
                }
            }
        }

        if (auth()->user()->is_belonging_an_area == 1) {

            $query->where('cash_counters.branch_id', auth()->user()->branch_id);
        }

        $cashCounters = $query->select(
            'branches.name as branch_name',
            'branches.branch_code',
            'branches.area_name',
            'parentBranch.name as parent_branch_name',
            'cash_counters.id',
            'cash_counters.branch_id',
            'cash_counters.counter_name',
            'cash_counters.short_name'
        )->orderBy('branch_id', 'asc')->get();

        return DataTables::of($cashCounters)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = '<div class="dropdown table-dropdown">';

                $html .= '<a href="' . route('cash.counters.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                $html .= '<a href="' . route('cash.counters.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')-' . $row->branch_code;
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')-' . $row->branch_code;
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })
            ->rawColumns(['branch', 'action'])
            ->make(true);
    }

    public function restriction(array|object $generalSettings): array
    {
        $cashCounterLimit = $generalSettings['addons__cash_counter_limit'];

        $cashCounters = DB::table('cash_counters')
            ->where('branch_id', auth()->user()->branch_id)
            ->count();

        if ($cashCounterLimit <= $cashCounters) {

            return ['pass' => false, 'msg' => __("Cash counter limit is ${cashCounterLimit}")];
        }

        return ['pass' => true];
    }

    public function addCashCounter(int $branchId, string $cashCounterName, string $shortName): object
    {
        return CashCounter::create([
            'branch_id' => $branchId,
            'counter_name' => $cashCounterName,
            'short_name' => $shortName,
        ]);
    }

    public function updateCashCounter($id, $request)
    {
        $updateCashCounter = $this->singleCashCounter($id);
        $updateCashCounter->counter_name = $request->counter_name;
        $updateCashCounter->short_name = $request->short_name;
        $updateCashCounter->save();
    }

    public function deleteCashCounter(): void
    {
        $delete = CashCounter::find($id);

        if (!is_null($delete)) {

            $delete->delete();
        }
    }

    public function singleCashCounter(int $id, array $with = null)
    {
        $query = CashCounter::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function cashCounters(array $with = null)
    {
        $query = CashCounter::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
