<?php

namespace App\Services\Setups;

class CashCounterService
{
    public function cashCounterListTable($request)
    {

        $generalSettings = config('generalSettings');
        $cashCounters = '';

        $query = DB::table('cash_counters')
                ->leftJoin('branches', 'cash_counters.branch_id', 'branches.id')
                ->select(
                    'branches.name as br_name',
                    'branches.branch_code as br_code',
                    'cash_counters.id',
                    'cash_counters.counter_name',
                    'cash_counters.short_name'
                )->where('branch_id', auth()->user()->branch_id)->get();

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $cashCounters = DB::table('cash_counters')->orderBy('id', 'DESC')
                ->leftJoin('branches', 'cash_counters.branch_id', 'branches.id')
                ->select(
                    'branches.name as br_name',
                    'branches.branch_code as br_code',
                    'cash_counters.id',
                    'cash_counters.counter_name',
                    'cash_counters.short_name'
                )->get();
        } else {


        }

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
                if ($row->br_name) {
                    return $row->br_name . '/' . $row->br_code . '(<b>BR</b>)';
                } else {
                    return $generalSettings['business__shop_name'] . '(<b>HO</b>)';
                }
            })
            ->rawColumns(['branch', 'action'])
            ->make(true);
    }
}
